<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://oakenfold.ca
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
    // returns array[0]= reply count, array[1]=date stamps
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
   * @since     1.0.0
   * @return    
   */
  public function get_comment_filter_date_range($comments) {
    if (count($comments) == 0) {
      return $comments;
    };

    $dates = $this->get_dates();
    $date1 = $dates[0];
    $date2 = $dates[1];


    if ($date1 && $date2) {
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
   * @since     1.0.0
   * @return    
   */
  public function get_comment_filter_search($comments, $args) {
    // input: $comments ['search', 'search term(s)']
    $walk = new Walker_Co_Mment_Filter_Search();

    $walkOutput = $walk->walk( $comments, 0, ['search', $args[0]]);
    return $walkOutput;
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
  public function comments_pagenum_link($content) {
    

    $dir = 'desc';
    $sort = 'date';

    if (isset($_GET['com_dir'])) {
      if ($_GET['com_dir'] == "asc") {
        $dir = 'asc';
      }
    }
    if (isset($_GET['com_sort'])) {
      if ($_GET['com_sort'] == "replies") {
        $sort = 'replies';
      }
    }

    $urlParts = parse_url($content);

    $urlPre = $urlParts['scheme'] ."://". $urlParts['host'] ."/". $urlParts['path'];

    $urlParams = 'com_sort='.$sort.'&com_dir='.$dir;

    if ($urlParts['query']) {
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
  public function format_date($date) {
    // 2016-01-27 00:02:17
    $date = new DateTime($date);
    return $date->format('Y-m-d H:i:s');
  }

  /**
   * 
   *
   * @since     1.0.0
   * @return    array    
   */
  public function get_dates() {

    // if($_GET["com_date1"] === "")    echo "com_date1 is an empty string\n";
    // if($_GET["com_date1"] === false) echo "com_date1 is false\n";
    // if($_GET["com_date1"] === null)  echo "com_date1 is null\n";
    // if(isset($_GET["com_date1"]))    echo "com_date1 is set\n";
    // if(!empty($_GET["com_date1"]))   echo "com_date1 is not empty\n";


    $dates = [false, false];
    $today = $this->get_today();

    if (isset($_GET['com_date1'])) {
      if ($_GET['com_date1'] === "") {
        $dates[0] = $this->format_date($today[0]);
      } else {
        $dates[0] = $this->format_date($_GET['com_date1']);
      };
    }
    if (isset($_GET['com_date2'])) {
      if ($_GET['com_date2'] === "") {
        $dates[1] = $this->format_date($today[1]);
      } else {
        $dates[1] = $this->format_date($_GET['com_date2']);
      };
    }
    return $dates;
  }
  /**
   * 
   *
   * @since     1.0.0
   * @return    array    
   */
  public function get_today() {
    //2016-02-10 13:25:08
    $timestamp = time()+date("Z");
    $start = gmdate("Y-m-d 00:00:00", $timestamp);
    $now = gmdate("Y-m-d H:i:s",$timestamp);
    return [$start, $now];
  }


  /**
   * 
   *
   * @since     1.0.0
   * @return    array    
   */
  public function comments_ui() {

    $dates = $this->get_dates();
    $date1 = $dates[0];
    $date2 = $dates[1];

    if (have_comments()){
      // default sort options
      $dateState = 'is-desc';
      $direction = 'is-desc';
      $inputDir = 'desc';
      $inputSort = 'date';
      $repliesState = 'is-inactive';
      $sort = 'date';

      if (isset($_GET['com_dir'])) {
        if ($_GET['com_dir'] == "asc") {
          $direction = 'is-asc';
          $inputDir = 'asc';
        }
      }


      // change defaults
      if (isset($_GET['com_sort'])) {
          if ($_GET['com_sort'] == "replies") {
            $repliesState = $direction;
            $dateState = 'is-inactive';
            $inputSort = 'replies';
          } else {
            $repliesState = 'is-inactive';
            $dateState = $direction;
            $inputSort = 'date';
          }
      }
      co_mment_sort_display_sort($inputSort, $inputDir, $dateState, $repliesState );

      co_mment_sort_display_filter_date($date1,$date2);
      
      //co_mment_sort_display_filter_search();
    } else {
      // show ui to undo if no comments due to date or search param
      if (isset($_GET['com_date1']) || isset($_GET['com_date2'])) {
        co_mment_sort_display_filter_date($date1,$date2);
      }
      if (isset($_GET['com_search'])) {
        co_mment_sort_display_filter_search();
      }

    }


  }





}
