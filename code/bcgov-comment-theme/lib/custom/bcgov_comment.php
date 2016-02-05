<?php

function bcgov_comments( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;

    switch( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' : ?>
            <li <?php comment_class(); ?> id="comment<?php comment_ID(); ?>">
            <div class="back-link"><?php comment_author_link(); ?></div>
        <?php break;
        default : ?>
            <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                <article <?php comment_class(); ?> class="comment">
                    <div class="row">
                        <div class="comment-left col-md-1 col-sm-1 col-xs-1">
                            <div class="vcard">
                                <?php echo get_avatar( $comment, 32 ); ?>
                            </div><!-- .vcard -->
                        </div><!-- comment-body -->
                        <div class="comment-right col-md-11 col-sm-11 col-xs-11">
                            <div class="row comment-top">
                                <div class="comment-author col-md-12 col-sm-12 col-xs-12">
                                    <span class="author-name"><?php comment_author(); ?></span>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <time <?php comment_time( 'c' ); ?> class="comment-time">
                                        <span class="date">
                                            <?php comment_date(); ?>
                                        </span>
                                        <span class="time">
                                            <?php comment_time(); ?>
                                        </span>
                                    </time>
                                </div>
                            </div>
                            <div class="row">
                                <div class="comment-meta col-md-12 col-sm-12 col-xs-12">
                                    <?php 
                                    if ( $comment->comment_approved == 0 ) {
                                        echo '<p><em>Your comment is awaiting moderation.</em></p>';
                                    }
                                    comment_text(); ?>
                                </div>
                            </div>
                            <footer class="row">
                                <div class="reply col-md-8 col-sm-8 col-xs-12">
                                    <?php 
                                    comment_reply_link( array_merge( $args, array( 
                                    'reply_text' => 'Reply',
                                    'after' => ' <span></span>', 
                                    'depth' => $depth,
                                    'max_depth' => $args['max_depth'] 
                                    ) ) ); ?>
                                </div><!-- .reply -->
                                <div class="comment-metadata col-md-4 col-sm-4 col-xs-12">
                                    
                                </div>
                            </footer><!-- .comment-footer -->
                        </div>
                    </div>
                    
                </article><!-- #comment-<?php comment_ID(); ?> -->
        <?php //*/ // End the default styling of comment
        break;
    endswitch;
}