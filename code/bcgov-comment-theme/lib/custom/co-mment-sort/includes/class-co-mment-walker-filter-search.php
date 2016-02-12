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

  function contains_word($str, $word){
    $str_lower = strtolower($str);
    $word_lower = strtolower($word);
    //echo $word_lower;
    //echo ' vs ';
    //echo $str_lower;
    //echo '<br />';
    //echo preg_match('#\b' . preg_quote($word_lower, '#') . '\b#i', $str_lower);
    //echo '<br />';
    return !!preg_match('#\b' . preg_quote($word_lower, '#') . '\b#i', $str_lower);
  }
  
  // Set the properties of the element which give the ID of the current item and its parent
  var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );

  // Displays start of an element. E.g '<li> Item Name'
  // @see Walker::start_el()
  function start_el(&$output, $item, $depth=0, $args=array(), $current_object_id=0) {
    $content_match = false;

    // check comment content
    foreach ($args as $key => $val) {
      if ($key !== 'has_children') {
        if ($this->contains_word($item->comment_content, $val)) {
          array_push($this->comments_output, $item);
          $content_match = true;
          break;
        }
      }
    }
    
    // check user name
    if ($content_match === false) {
      foreach ($args as $key => $val) {
        if ($key !== 'has_children') {
          if ($this->contains_word($item->comment_author, $val)) {
            array_push($this->comments_output, $item);
            break;
          }
        }
      }
    }
  }

  function end_el( &$output, $item, $depth = 0, $args = array()) {
    // $commentsFiltered
    $output = $this->comments_output;

  }

}
