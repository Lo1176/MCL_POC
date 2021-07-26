<?php
/*

 * Post/Page slider template.
 *
 * This template can be overriden by copying this file to your-theme/bs5-post-product-slider/sc-post-slider.php
 *
 * @author 		Bastian Kreiter
 * @package 	bS5 Post/Product Slider
 * @version     1.0.0


Post Slider Shortcode 
[bs-post-slider type="post" category="blog, equal-height" order="DESC" orderby="date" posts="12"]

Page Slider Shortcode
[bs-post-slider type="page" post_parent="1891" order="ASC" orderby="title" posts="6"]

*/


// Post Slider Shortcode
add_shortcode( 'bs-post-slider', 'bootscore_post_slider' );
function bootscore_post_slider( $atts ) {
	ob_start();
	extract( shortcode_atts( array (
		'type' => 'post',
		'order' => 'date',
		'orderby' => 'date',
		'posts' => -1,
		'category' => '',
        'post_parent'    => '',
        
	), $atts ) );
	$options = array(
		'post_type' => $type,
		'order' => $order,
		'orderby' => $orderby,
		'posts_per_page' => $posts,
		'category_name' => $category,
        'post_parent' => $post_parent,
        
	);
	$query = new WP_Query( $options );
	if ( $query->have_posts() ) { ?>


<!-- Swiper -->

<div class="px-5 position-relative post-slider">

    <div class="swiper-container">

        <div class="swiper-wrapper">

            <?php while ( $query->have_posts() ) : $query->the_post(); ?>

            <div class="swiper-slide card h-auto mb-5">
                <!-- Featured Image-->
                <?php the_post_thumbnail('medium', array('class' => 'card-img-top')); ?>

                <div class="card-body d-flex flex-column">
                    
                    <?php bootscore_category_badge(); ?>
                    
                    <!-- Title -->
                    <h2 class="blog-post-title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>
                    <!-- Meta -->
                    <?php if ( 'post' === get_post_type() ) : ?>
                    <small class="text-muted mb-2">
                        <?php
				            bootscore_date();
				            bootscore_author();
				            bootscore_comments();
				            bootscore_edit();
				        ?>
                    </small>
                    <?php endif; ?>
                    <!-- Excerpt & Read more -->
                    <div class="card-text">
                        <?php the_excerpt(); ?>
                    </div>
                            
                    <div class="mt-auto">
                        <a class="read-more" href="<?php the_permalink(); ?>"><?php _e('Read more »', 'bootscore'); ?></a>
                    </div>
                    <!-- Tags -->
                    <?php bootscore_tags(); ?>

                </div>

            </div>

            <?php endwhile; wp_reset_postdata(); ?>
            
        </div> <!-- .swiper-wrapper -->
        
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        
    </div><!-- swiper-container -->

    <!-- Add Arrows -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>

</div><!-- px-5 position-relative mb-5 -->

<!-- Swiper End -->

<?php $myvariable = ob_get_clean();
	return $myvariable;
	}	
}

// Post Slider Shortcode End