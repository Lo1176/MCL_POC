<?php

/**
 * Template Name: Home page
 */

get_header();
?>

<div id="content" class="site-content">
    <div id="primary" class="content-area">

        <!-- Hook to add something nice -->
        <?php bs_after_primary(); ?>

        <main id="main" class="site-main">

            <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
            <header class="entry-header featured-full-width-img height-75 text-light mb-3" style="background-image: url('<?php echo $thumb['0']; ?>')">
                <div class="container-fluid px-0 pb-5">
          
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>

                </div><!-- container-fluid full width -->
            </header>
                    <footer class="entry-footer">

                    </footer>

                    <?php comments_template(); ?>




        </main><!-- #main -->

    </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
