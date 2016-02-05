<?php

namespace Roots\Sage\Init;

use Roots\Sage\Assets;

/**
 * Theme setup
 */
function setup() {
  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('sage', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  add_theme_support('title-tag');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'sage')
  ]);

  // Add post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');

  // Add post formats
  // http://codex.wordpress.org/Post_Formats
  add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

  // Add HTML5 markup for captions
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list']);

  // Tell the TinyMCE editor to use a custom stylesheet
  add_editor_style(Assets\asset_path('styles/editor-style.css'));
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Register sidebars
 */
function widgets_init() {
  register_sidebar([
    'name'          => __('Primary', 'sage'),
    'id'            => 'sidebar-primary',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);

  register_sidebar([
    'name'          => __('Footer', 'sage'),
    'id'            => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);
}
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');

/**
 * Register custom comment loading settings.
 */
function comment_settings_init( $wp_customize ) {
  /* Setting: How many comments to display on the initial page load?
   * This also applies to children, so a value of 5 will give you
   * 5 comments with 5 immediate children each. */
  $wp_customize->add_setting( 'comments_per_set_initial' , array(
    'default'    => 20,
    'transport'  => 'refresh',
  ));

  /* Setting: How many comments to display when 'load more' is clicked?
   * When set to 0, it will use the value from comments_per_set_initial
   * instead. */
  $wp_customize->add_setting( 'comments_per_set_append' , array(
    'default'    => 0, // 0 = use comments_per_set_initial
    'transport'  => 'refresh',
  ));

  $wp_customize->add_section( 'bcgov_comments_config' , array(
    'title'      => __( 'Comment Loading', 'sage' ),
    'priority'   => 50,
  ));

  $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'comments_per_set_initial', [
    'label'      => __( 'Number of comments to display initially', 'sage' ),
    'section'    => 'bcgov_comments_config',
    'settings'   => 'comments_per_set_initial',
    'type'       => 'number',
  ]));

  $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'comments_per_set_append', [
    'label'      => __( 'Number of comments to load when requested', 'sage' ),
    'section'    => 'bcgov_comments_config',
    'settings'   => 'comments_per_set_append',
    'type'       => 'number',
  ]));
}
add_action('customize_register', __NAMESPACE__ . '\\comment_settings_init');

/**
 * Include custom comment callback function
 */
include_once 'custom/bcgov_comment.php';
