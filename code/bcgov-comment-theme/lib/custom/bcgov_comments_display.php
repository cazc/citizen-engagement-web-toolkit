<?php
  namespace Roots\Sage\Custom\CommentsDisplay;

  function _load_more($text, $after = null, $child_of = 0) {
    $fake_api_url = \add_query_arg([
      'comments_popup'      => get_the_ID(),
      'minimal'             => 1,
      'comments_only_after' => $after,
      'comments_child_of'   => $child_of,
    ], get_site_url() . '/');
    return "<li><button class='handles-load-more btn btn-xs btn-primary' data-src='$fake_api_url'>$text [after $after] </button></li>";
  }

  // Walker to add a 'load more' link to the end of each comment list.
  class Walker_AddsLoadMore extends \Walker_Comment {
    private $max_count = 0;
    private $after = null;
    private $count_stack = [ 0 ];

    function __construct() {
      $this->max_count = get_theme_mod('comments_per_set_initial', 20);
    }

    public function display_element( $element, &$children_elements, $max_depth,
                                     $depth, $args, &$output ) {
      $this->count_stack[$depth]++;
      if ( !$element || end($this->count_stack) > $this->max_count)
        return;

      \Walker::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

    public function end_el ( &$output, $comment, $depth = 0, $args = array() ) {
      parent::end_el($output, $comment, $depth, $args);
      $this->after = $comment->comment_ID;
      if (end($this->count_stack) == $this->max_count) {
        $this->append_load_more_link($output, $this->after);
      }
    }

    public function start_lvl( &$output, $depth = 0, $args = array() ) {
      parent::start_lvl($output, $depth, $args);
      array_push($this->count_stack, 0);
    }

    public function end_lvl( &$output, $depth = 0, $args = array() ) {
      // Call super to de-nest.
      parent::end_lvl($output, $depth, $args);
      array_pop($this->count_stack);
    }

    private function append_load_more_link( &$output, $after ) {
      if ($this->is_top_level()) {
        $output .= _load_more( __('Load more comments', 'sage'), $after );
      } else {
        $output .= _load_more( __('Load more replies', 'sage'), $after );
      }
    }
    private function is_top_level() {
      return (count( $this->count_stack ) == 1) ? true : false;
    }
  }

  class Walker_NoneBefore extends Walker_AddsLoadMore {
    private $look_for_id = 0;
    private $look_at_children_of = 0;
    private $has_after_id_been_found = false;

    function __construct( $only_comments_after, $only_children_of ) {
      $this->look_for_id = $only_comments_after;
      $this->look_at_children_of = $only_children_of;
      parent::__construct();
    }

    public function display_element( $element, &$children_elements, $max_depth,
                                     $depth, $args, &$output ) {
      if ( $element->comment_ID == $this->look_for_id ) {
        $this->has_after_id_been_found = true;
        return;
      }

      if ( $this->has_after_id_been_found && !($depth === 0 && $element->comment_parent != $this->look_at_children_of) )
        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
  }

  function walker_for_query_args() {
    global $wp_query;

    if ( isset($wp_query->query_vars['comments_only_after'] )) {
      if (isset($wp_query->query_vars['comments_child_of'])) {
        $child_of = intval( $wp_query->query_vars['comments_child_of'] );
      } else {
        $child_of = 0;
      }

      return new Walker_NoneBefore( intval( $wp_query->query_vars['comments_only_after'] ), $child_of );
    } else {
      return new Walker_AddsLoadMore();
    }
  }

  function set_up_comment_qvars() {
    // Will not be present if loading more top-level comments
    add_rewrite_tag('%comments_child_of%', '([1-9][0-9]*)');
    // Returns the next N comments after this one (id)
    add_rewrite_tag('%comments_only_after%', '([1-9][0-9]*)');
  }
  add_action('init', __NAMESPACE__ . '\\set_up_comment_qvars');
?>
