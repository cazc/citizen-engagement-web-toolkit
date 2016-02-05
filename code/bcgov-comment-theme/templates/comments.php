<?php

use Roots\Sage\Custom\CommentsDisplay;

if (post_password_required()) {
  return;
}

?>

<section id="comments" class="comments">

  <?php comment_form(); ?>


  <?php if (have_comments()) : ?>
    <h3><?php printf(_nx('One response to &ldquo;%2$s&rdquo;', '%1$s responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'sage'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>'); ?></h3>

    <ol class="comment-list">
      <?php
      wp_list_comments( [
        'style' => 'ol',
        'short_ping' => true,
        'callback' => 'bcgov_comments',
        'walker' => CommentsDisplay\walker_for_query_args(),
        'max_depth' => 2,
      ] );
      ?>
    </ol>
  <?php endif; // have_comments() ?>

  <?php if (!comments_open() && get_comments_number() != '0' && post_type_supports(get_post_type(), 'comments')) : ?>
    <div class="alert alert-warning">
      <?php _e('Comments are closed.', 'sage'); ?>
    </div>
  <?php endif; ?>


</section>
