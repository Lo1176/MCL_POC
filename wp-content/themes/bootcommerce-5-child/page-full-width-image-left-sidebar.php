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
            <header class="entry-header featured-full-width-img height-75 bg-dark text-light mb-3" style="background-image: url('<?php echo $thumb['0']; ?>')">
                <div class="container entry-header h-100 d-flex align-items-end pb-3">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </div>
            </header>

            <div class="container-fluid pb-5">
                <div class="d-flex entry-content">
                    <?php get_sidebar(); ?>
                    <?php query_posts('cat=6,7,8&showposts=10');
                    while (have_posts()) : the_post(); ?> <?php $cpt += 1;
                                                        endwhile; ?>

                    <!-- test afficher produit de la categorie de la page  -->
                    <?php
                    $params = array(
                        'posts_per_page' => 5,
                        'posts_type' => 'product'
                    ); // (1)
                    $wc_query = new WP_Query($params); // (2)
                    ?>
                    <?php if ($wc_query->have_posts()) : // (3) 
                    ?>
                        <?php while ($wc_query->have_posts()) : // (4)
                            $wc_query->the_post(); // (4.1) 
                        ?>
                            <?php the_title(); // (4.2) 
                            ?>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); // (5) 
                        ?>
                    <?php else :  ?>
                        <p>
                            <?php _e('No Products'); // (6) 
                            ?>
                        </p>
                    <?php endif; ?>

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
