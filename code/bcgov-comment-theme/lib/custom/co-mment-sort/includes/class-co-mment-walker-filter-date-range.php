<?php 
/*
A user can filter information through a date field:

  every element add if between date range
  input: $comments ['date', 'date 1', 'date 2']
  output: $commentsFiltered

*/
/*
[0] => stdClass Object
  (
    [comment_ID] => 12
    [comment_post_ID] => 4
    [comment_author] => @coak
    [comment_author_email] => web@oakenfold.ca
    [comment_author_url] => 
    [comment_author_IP] => 127.0.0.1
    [comment_date] => 2016-01-27 00:02:17
    [comment_date_gmt] => 2016-01-27 00:02:17
    [comment_content] => asdfasd
    [comment_karma] => 0
    [comment_approved] => 1
    [comment_agent] => Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36
    [comment_type] => 
    [comment_parent] => 0
    [user_id] => 1
  )
*/
class Co_Mment_Walker_Filter_Date_Range extends Walker {
  private $comments_output = array();

  // Set the properties of the element which give the ID of the current item and its parent
  var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );

  function date1EqGtDate2($date1, $date2){
      // check date
      $d1 = new DateTime($date1);
      $d2 = new DateTime($date2);

      if ($d1 >= $d2) {
        $to_return = true;
      } else {
        $to_return = false;
      };

      return $to_return;
  }

  // Displays start of an element. E.g '<li> Item Name'
  // @see Walker::start_el()
  function start_el(&$output, $item, $depth=0, $args=array()) {
    // $args[0]//type: date, search
    // $args[1]//data1: date start, search term
    // $args[2]//data2: date end

    // gmt strings
    $date_start = $args[1];
    $date_end = $args[2];

    $comment_date = $item->comment_date_gmt;
    $above_start = $this->date1EqGtDate2($comment_date, $date_start);
    $below_end = $this->date1EqGtDate2($date_end, $comment_date);

    if ($above_start && $below_end) {
      array_push($this->comments_output, $item);
    };

  }

  function end_el( &$output, $item, $depth = 0, $args = array()) {
    // $commentsFiltered
    $output = $this->comments_output;

  }
}
