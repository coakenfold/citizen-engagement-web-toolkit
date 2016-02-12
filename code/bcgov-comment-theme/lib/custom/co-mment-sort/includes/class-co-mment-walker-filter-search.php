<?php 
/*
A user can filter information through a search field, by:
- keywords from within comment fields
- keywords associated with author name

We will be searching for words not letters using regex:
http://stackoverflow.com/a/25633879
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
class Co_Mment_Walker_Filter_Search extends Walker {
  private $comments_output = array();

  // Set the properties of the element which give the ID of the current item and its parent
  var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );

  // Displays start of an element. E.g '<li> Item Name'
  // @see Walker::start_el()
  function start_el(&$output, $item, $depth=0, $args=array(), $current_object_id=0) {
    // $args[0]//type: date, search
    // $args[1]//data1: date start, search term
    // $args[2]//data2: date end
    //if ($args[0])

    print_r($args);
  }

  function end_el( &$output, $item, $depth = 0, $args = array()) {
    // $commentsFiltered
    $output = $this->comments_output;
  }

}
