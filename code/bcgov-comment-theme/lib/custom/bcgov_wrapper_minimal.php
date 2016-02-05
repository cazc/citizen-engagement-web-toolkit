<?php

namespace Roots\Sage\Custom\WrapperMinimal;
use Roots\Sage\Wrapper;

/**
 * This hook undoes the changes made by Sage's template wrapper.
 * Not the greatest code but it works
 */
function minimal_template( $template ) {
  global $wp_query;

  if (isset($wp_query->query_vars['minimal'])) {
    return Wrapper\template_path();
  } else {
    return $template;
  }
}

add_filter('template_include', __NAMESPACE__ . '\\minimal_template', 100);

function add_minimal_base_query() {
  add_rewrite_tag('%minimal%', '.*');
}

add_action('init', __NAMESPACE__ . '\\add_minimal_base_query');

?>