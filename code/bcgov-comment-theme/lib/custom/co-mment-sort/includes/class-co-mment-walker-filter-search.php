<?php 
/**
 * A user can filter information through a search field, by:
 * - keywords from within comment fields
 * - keywords associated with author name
 *
 * @since    1.0.1
 */
class Co_Mment_Walker_Filter_Search extends Walker {
  private $comments_output = array();

  function contains_word($str, $word){
    $str_lower = strtolower($str);
    $word_lower = strtolower($word);
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
