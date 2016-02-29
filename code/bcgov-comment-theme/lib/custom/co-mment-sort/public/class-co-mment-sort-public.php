<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://oakenfold.ca/co-mment-sort
 * @since      1.0.0
 *
 * @package    Co_Mment_Sort
 * @subpackage Co_Mment_Sort/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Co_Mment_Sort
 * @subpackage Co_Mment_Sort/public
 * @author     Chad Oakenfold <web@oakenfold.ca>
 */
class Co_Mment_Sort_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $co_mment_sort    The ID of this plugin.
	 */
	private $co_mment_sort;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $co_mment_sort       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $co_mment_sort, $version ) {

		$this->co_mment_sort = $co_mment_sort;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Co_Mment_Sort_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Co_Mment_Sort_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

    wp_enqueue_style( $this->co_mment_sort . 'datepicker', plugin_dir_url( __FILE__ ) . 'libraries/bootstrap-datepicker-1.5.1-dist/css/bootstrap-datepicker.standalone.min.css', array(), $this->version, 'all' );
    wp_enqueue_style( $this->co_mment_sort, plugin_dir_url( __FILE__ ) . 'css/co-mment-sort.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Co_Mment_Sort_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Co_Mment_Sort_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

    wp_enqueue_script( $this->co_mment_sort . 'datepicker', plugin_dir_url( __FILE__ ) . 'libraries/bootstrap-datepicker-1.5.1-dist/js/bootstrap-datepicker.min.js', array( 'jquery' ), $this->version, false );
    wp_enqueue_script( $this->co_mment_sort, plugin_dir_url( __FILE__ ) . 'js/co-mment-sort-public.js', array( 'jquery' ), $this->version, false );
	}

  /**
   * 
   *
   * @since     1.0.0
   * @return    
   */
  public function get_comment_sort($comments) {
    if (count($comments) == 0) {
      return $comments;
    };

    $direction = true;
    if (isset($_GET['com_dir'])) {
      if ($_GET['com_dir'] == "asc") {
        $direction = false;
      }
    }

    if (isset($_GET['com_sort'])) {
        if ($_GET['com_sort'] == "replies") {
          $output = $this->get_comment_sort_replies($comments, $direction);
        } else {
          $output = $this->get_comment_sort_date($comments, $direction);
        }
    } else {
      $output = $this->get_comment_sort_date($comments, $direction);
    }

    return $output;
  }

  /**
   * 
   *
   * @since     1.0.0
   * @return    
   */
  public function get_comment_sort_date($comments, $direction_bool) {

    $walk = new Co_Mment_Walker_Sort();

    $walkOutput = $walk->walk( $comments, 0 );
    $walkOutputDate = $walkOutput[1];

    if ($direction_bool == true) {
      sasort($walkOutputDate);
    } else {
      sarsort($walkOutputDate);
    }
    $comment_root_sorted = $walkOutputDate;
    $comments_sorted = $this->merge_comment_array($comments, $comment_root_sorted);
    return $comments_sorted;
  }

  /**
   * 
   *
   * @since     1.0.2
   * @return    
   */
  public function get_comment_more($comments, $direction_bool) {

    $walk = new Co_Mment_Walker_More();

    $walkOutput = $walk->walk( $comments, 0 );
    echo 'EKJERLKEJRLKEJ';
    print_r($walkOutput);
    echo 'EKJERLKEJRLKEJ';
//    $walkOutputDate = $walkOutput[1];
//
//    if ($direction_bool == true) {
//      sasort($walkOutputDate);
//    } else {
//      sarsort($walkOutputDate);
//    }
//    $comment_root_sorted = $walkOutputDate;
//    $comments_sorted = $this->merge_comment_array($comments, $comment_root_sorted);
//    return $comments_sorted;
      return $comments;
  }

  /**
   * 
   *
   * @since     1.0.0
   * @return    array    Sorted comments array
   */
  public function get_comment_sort_replies($comments, $direction_bool) {
    // returns array[0]= reply count, array[1]=date stamps
    $walk = new Co_Mment_Walker_Sort();
    $walkOutput = $walk->walk( $comments, 0 );
    $walkOutputReplies = $walkOutput[0];

    if ($direction_bool == true) {
      sasort($walkOutputReplies);
    } else {
      sarsort($walkOutputReplies);
    }

    $comment_root_sorted = $walkOutputReplies;
    $comments_sorted = $this->merge_comment_array($comments, $comment_root_sorted);
    return $comments_sorted;
  }

  /**
   * 
   *
   * @since     1.0.1
   * @return    
   */
  public function get_comment_filter_date_range($comments) {
    if (count($comments) == 0) {
      return $comments;
    };

    $params = $this->get_params();

    if (array_key_exists('com_date1', $params)) {
      $date1 = $params['com_date1'];
    }
    if (array_key_exists('com_date2', $params)) {
      $date2 = $params['com_date2'];
    }


    if (isset($date1) && isset($date2)) {
      $walk = new Co_Mment_Walker_Filter_Date_Range();
      
      $walker_args = ['date', $date1, $date2];

      // check date order
      // $d_old = new DateTime($date1);
      // $d_new = new DateTime($date2);
      // if ($d_old > $d_new) {
      //   $walker_args = ['date', $date2, $date1];
      // }

      $walkOutput = $walk->walk( $comments, 0, $walker_args);
      
      return $walkOutput;
    } else {
      // pass through
      return $comments;
    }
  }

  /**
   * 
   *
   * @since     1.0.1
   * @return    
   */
  public function get_comment_filter_search($comments, $args=array()) {
    if (count($comments) == 0) {
      return $comments;
    };

    if (isset($_GET['com_search'])) {
      $g_com_search = $_GET['com_search'];
      if ($this->url_param_valid($g_com_search)) {
        // url decode
        $search = explode(" ", urldecode($g_com_search));
      };
    }

    if (isset($search)) {
      $walk = new Co_Mment_Walker_Filter_Search();

      $walkOutput = $walk->walk( $comments, 0, $search);
      
      return $walkOutput;
    } else {
      // pass through
      return $comments;
    }
  }

  /**
   * 
   *
   * @since     1.0.0
   * @return    array
   */
  public function merge_comment_array($comments, $comment_root_sorted) {
    // build an 'index' array of the original comments
    $commentRef = array();
    foreach ($comments as $comment) {
      $commentRef[$comment->comment_ID] = $comment;
    }

    // put the top levels first 
    $start_section = array();
    $end_section = array();

    foreach ($comment_root_sorted as $key => $val) {
      $start_section[] = $commentRef[$key];
    }
    // then the children
    foreach ($comments as $comment) {
       if ($comment->comment_parent != 0) {
         $end_section[] = $comment;
       }
    }
    // reunion
    $arr_merge = array_merge($start_section, $end_section);

    return $arr_merge;
  }

  /**
   * 
   *
   * @since     1.0.0
   * @return    array
   */
  public function get_url_params_array() {
    $url_params = array();

    $params = $this->get_params();
    foreach ($params as $key => $val) {
      array_push($url_params, "$key=$val");
    }

    return $url_params;
  }


  /**
   * 
   *
   * @since     1.0.0
   * @return    array
   */
  public function comments_pagenum_link($content) {

    $urlParts = parse_url($content);

    $urlPre = $urlParts['scheme'] ."://". $urlParts['host'] ."/". $urlParts['path'];

    $urlParams = join('&', $this->get_url_params_array());

    if (array_key_exists('query', $urlParts)) {
      $urlPost = $urlParts['query'] ."&". $urlParams;
    } else {
      $urlPost = "?". $urlParams;
    }
    
    return $urlPre . $urlPost . ($urlParts['fragment'] ? '#'.$urlParts['fragment'] : '');
  }

  /**
   * 
   *
   * @since     1.0.0
   * @return    array
   */
  public function date_format($date) {
    // 2016-01-27 00:02:17
    // 2015-01-01+00%3A00%3A00
    $date = new DateTime(urldecode($date));
    return $date->format('Y-m-d');
  }
  /**
   * 
   *
   * @since     1.0.0
   * @return    array
   */
  public function url_param_valid($param) {
    if($param === "" || $param === false || $param === null) {
      return false;
    } else {
      return true;
    }
  }

  /**
   * 
   *
   * @since     1.0.1
   * @return    array
   */
  public function get_params() {

    $params = array();
    
    // Sort direction
    if (isset($_GET['com_dir'])) {
      if ($_GET['com_dir'] == 'asc') {
        $params['com_dir'] = 'asc';
      } else {
        $params['com_dir'] = 'desc';
      }
    } else {
      $params['com_dir'] = 'desc';
    }

    // Sort by date or replies
    if (isset($_GET['com_sort'])) {
      if ($_GET['com_sort'] == 'replies') {
        $params['com_sort'] = 'replies';
      } else {
        $params['com_sort'] = 'date';
      }
    } else {
      $params['com_sort'] = 'date';
    }

    // Filter by date range
    if (isset($_GET['com_date1'])) {
      if ($this->url_param_valid($_GET['com_date1'])) {
        $params['com_date1'] = $this->date_format($_GET['com_date1']);
      };
    }
    if (isset($_GET['com_date2'])) {
      if ($this->url_param_valid($_GET['com_date2'])) {
        $params['com_date2'] = $this->date_format($_GET['com_date2']);
      };
    }

    // Filter by search term
    if (isset($_GET['com_search'])) {
      if ($this->url_param_valid($_GET['com_search'])) {
        $params['com_search'] = $_GET['com_search'];
      };
    }

    return $params;
  }

  /**
   * 
   *
   * @since     1.0.0
   * @return    array    
   */
  public function comments_ui() {

    $params = $this->get_params();
    $has_dates = false;
    $has_search = false;

    if (array_key_exists('com_search', $params)) {
        $search = $params['com_search'];
        $has_search = true;
    }
    if (array_key_exists('com_date1', $params)) {
        $date1 = $params['com_date1'];
        $has_dates = true;
    }
    if (array_key_exists('com_date2', $params)) {
        $date2 = $params['com_date2'];
        $has_dates = true;
    }

    if (have_comments()){
      co_mment_sort_display_sort($params, true);
      co_mment_sort_display_filter_date($params, true);
      co_mment_sort_display_filter_search($params, true);
    } else {
      if ($has_search === true || $has_dates === true) {
        // No comments,
        
        // show ui to undo if date or search params

        if (isset($date1) || isset($date2)) {
          co_mment_sort_display_filter_date($params, false);
        }
        if (isset($search)) {
          co_mment_sort_display_filter_search($params, false);
        }

        // display message with a clear date/search button
        co_mment_sort_display_no_results($has_dates, $has_search);
      }
    }
  }

}
