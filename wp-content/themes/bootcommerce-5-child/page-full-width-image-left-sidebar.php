<?php

/**
 * Template Name: Full Width Image with left sidebar
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

get_header();
?>

<div id="content" class="site-content">
    <div id="primary" class="content-area">

        <!-- Hook to add something nice -->
        <?php bs_after_primary(); ?>

        <main id="main" class="site-main">

            <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
            <header class="entry-header featured-full-width-img height-75 bg-dark text-white mb-3" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0.6) 0%,rgba(0,0,0,0.9) 100%), url('<?php echo $thumb['0']; ?>');">
                <div class="container entry-header h-100 d-flex align-items-end pb-3">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </div>
            </header>

            <div class="container-fluid pb-5">
                <div class="d-flex entry-content">
                    <?php get_sidebar(); ?>

                    <?php the_content(); ?>
                </div>

                <footer class="entry-footer">

                </footer>

                <?php comments_template(); ?>

            </div><!-- row -->

        </main><!-- #main -->

    </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
