<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://oakenfold.ca
 * @since      1.0.0
 *
 * @package    Co_Mment_Sort
 * @subpackage Co_Mment_Sort/public/partials
 */

function co_mment_sort_inputs_hidden($params) {
  $inputs = array( 
    'com_date1' => array (
       'hidden' => '',
       'text' => ''
    ),
    'com_date2' => array (
       'hidden' => '',
       'text' => ''
    ),
    'com_search' => array (
       'hidden' => '',
       'text' => ''
    ),
    'com_sort' => array (
       'hidden' => '',
       'text' => ''
    ),
    'com_dir' => array (
       'hidden' => '',
       'text' => ''
    )
  );

  $input_a_hidden = "<input type='hidden' name='";
  $input_a_text = "<input type='text' name='";
  $input_b = "' value='";
  $input_c = "'>";
  $inputDir = 'desc';
  $inputSort = 'date';
  $inputTextComDate1 = '';
  $inputTextComDate2 = '';
  $inputTextComSearch = '';
  
  if (array_key_exists('com_date1', $params)) {
    $inputs['com_date1']['hidden'] = $input_a_hidden .'com_date1'. $input_b . $params['com_date1'] . $input_c;
    $inputTextComDate1 = $params['com_date1'];
  }
  if (array_key_exists('com_date2', $params)) {
    $inputs['com_date2']['hidden'] = $input_a_hidden .'com_date2'. $input_b . $params['com_date2'] . $input_c;
    $inputTextComDate2 = $params['com_date2'];
  }
  if (array_key_exists('com_search', $params)) {
    $inputs['com_search']['hidden'] = $input_a_hidden .'com_search'. $input_b . $params['com_search'] . $input_c;
    $inputTextComSearch = $params['com_search'];
  }

  
  if (array_key_exists('com_dir', $params)) {
    $inputDir = $params['com_dir'];
  }
  if (array_key_exists('com_sort', $params)) {
    $inputSort = $params['com_sort'];
  }
  $inputs['com_sort']['hidden'] = "<input class='js-co-input-sort' type='hidden' name='com_sort' value='" . $inputSort ."'>";
  $inputs['com_dir']['hidden'] = "<input class='js-co-input-dir' type='hidden' name='com_dir' value='". $inputDir ."'>";
  
  $inputs['com_date1']['text'] = "<input type='text' class='input-sm form-control' name='com_date1' value='" .$inputTextComDate1. "' placeholder='yyyy-mm-dd' />";
  $inputs['com_date2']['text'] = "<input type='text' class='input-sm form-control' name='com_date2' value='" .$inputTextComDate2. "' placeholder='yyyy-mm-dd' />";
  $inputs['com_search']['text'] = "<input type='search' value='".$inputTextComSearch."' name='com_search' class='search-field form-control co_search__input' placeholder='Search Comments'>";
  return $inputs;
}

function co_mment_sort_display_sort($params, $hasComments) { 
  
  $direction = 'is-desc';
  $dateState = $direction;
  $repliesState = 'is-inactive';

  if (array_key_exists('com_dir', $params)) {
    if ($params['com_dir'] === 'asc') {
        $direction = 'is-asc';
    }
  }
  if ($params['com_sort'] === 'replies') {
    $repliesState = $direction;
    $dateState = 'is-inactive';
  } else {
    $repliesState = 'is-inactive';
    $dateState = $direction;
  }

  $inputs = co_mment_sort_inputs_hidden($params);

?>
<form class='co_sort__container js-co-form' method='get' action='<?php echo the_permalink(); ?>'>
  <?php 
    echo $inputs['com_date1']['hidden'];
    echo $inputs['com_date2']['hidden'];
    echo $inputs['com_search']['hidden'];
    echo $inputs['com_sort']['hidden'];
    echo $inputs['com_dir']['hidden'];
  ?>
  <button type='button' class='btn co_btn-sort co_btn-sort--date js-co-btn-sort-date' data-state='<?php echo $dateState; ?>'>Date</button>
  <button type='button' class='btn co_btn-sort co_btn-sort--replies js-co-btn-sort-replies' data-state='<?php echo $repliesState; ?>'>Replies</button>
</form>
<?php }

function co_mment_sort_display_filter_date($params, $hasComments) { 
  $inputs = co_mment_sort_inputs_hidden($params);
  if (array_key_exists('com_date1', $params)) {
    $date1 = $params['com_date1'];
  } else {
    $date1 = '';
  }
  if (array_key_exists('com_date2', $params)) {
    $date2 = $params['com_date2'];
  } else {
    $date2 = '';
  }
?>
<form class='co_filter__container js-co-form-filter' method='get' action='<?php echo the_permalink(); ?>'>
  <?php 
    echo $inputs['com_search']['hidden'];
    echo $inputs['com_sort']['hidden'];
    echo $inputs['com_dir']['hidden'];
  ?>
  <div class='co_filter__dates input-daterange input-group js-input-daterange'>
    <?php echo $inputs['com_date1']['text']; ?>
    <span class='input-group-addon'>to</span>
    <?php echo $inputs['com_date2']['text']; ?>
  </div>
  <button type='submit' class='btn co_filter__btn'>Filter</button>
</form>
<?php }


function co_mment_sort_display_filter_search($params, $hasComments) { 
  $inputs = co_mment_sort_inputs_hidden($params);
?>
<form method='get' class='search-form form-inline co_search__container js-co-form-search' action='<?php echo the_permalink(); ?>'>
  <?php 
    echo $inputs['com_date1']['hidden'];
    echo $inputs['com_date2']['hidden'];
    echo $inputs['com_sort']['hidden'];
    echo $inputs['com_dir']['hidden'];
    echo $inputs['com_search']['text'];
  ?>
  <button type='submit' class='btn co_search__btn'>Search</button>
</form>
<?php }