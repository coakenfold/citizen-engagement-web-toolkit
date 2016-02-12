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

function co_mment_sort_display_sort($inputSort, $inputDir, $dateState, $repliesState) { ?>
<form class='co_sort__container js-co-form' method='get' action=''>
  <input class='js-co-input-sort' type='hidden' name='com_sort' value='<?php echo $inputSort; ?>'>
  <input class='js-co-input-dir' type='hidden' name='com_dir' value='<?php echo $inputDir; ?>'>
  <button type='button' class='btn co_btn-sort co_btn-sort--date js-co-btn-sort-date' data-state='<?php echo $dateState; ?>'>Date</button>
  <button type='button' class='btn co_btn-sort co_btn-sort--replies js-co-btn-sort-replies' data-state='<?php echo $repliesState; ?>'>Replies</button>
</form>
<?php }

function co_mment_sort_display_filter_date($date1='', $date2='') { ?>
<form class='co_filter__container js-co-form-filter' method='get' action=''>
  <div class="co_filter__dates input-daterange input-group js-input-daterange">
      <input type="text" class="input-sm form-control" name="com_date1" value='<?php echo $date1; ?>' placeholder='yyyy-mm-dd' />
      <span class="input-group-addon">to</span>
      <input type="text" class="input-sm form-control" name="com_date2" value='<?php echo $date2; ?>' placeholder='yyyy-mm-dd' />
  </div>
  <button type='submit' class='btn co_filter__btn'>Filter</button>
</form>
<?php }


function co_mment_sort_display_filter_search() { ?>
<form method="get" class="search-form form-inline co_search__container js-co-form-search" action="">
  <input type="search" value="" name="com_search" class="search-field form-control co_search__input" placeholder="Search Comments">
  <button type="submit" class="btn co_search__btn">Search</button>
</form>
<?php }