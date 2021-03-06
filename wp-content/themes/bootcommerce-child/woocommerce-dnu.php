<?php

/**
 * The template for displaying all WooCommerce pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

get_header();
?>

<div id="content" class="site-content container-fluid pb-5 mt-1">
  <div id="primary" class="content-area">
    <?php
    /**
     * Display category image on category archive
     */
    add_action('woocommerce_show_page_title', 'woocommerce_category_image', 5);
    function woocommerce_category_image()
    {
      // verify that this is a product category page
      if (is_product_category()) {
        global $wp_query;

        // get the query object
        $cat = $wp_query->get_queried_object();

        // get the thumbnail id using the queried category term_id
        $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
        
        // get the image URL
        $image = wp_get_attachment_url($thumbnail_id);
        
        // print the IMG HTML
        if ($image) {
          echo '<img class="cat-image featured-full-width-img" src="' . $image . '" alt="' . $cat->name . '" />';
        }
        echo '<div class="category-product-title mt-5"><h1>' . $cat->name . '</h1></div>';
        #echo "<img src='{$image}' alt='' class='img-fluid mb-4' />";

      }
    }
    ?>
    <!-- Hook to add something nice -->
    <?php bs_after_primary(); ?>

    <main id="main" class="site-main">

      <!-- Breadcrumb -->
      <div class="text-danger container-fluid">
        <?php woocommerce_breadcrumb(); ?>
      </div>
      <div class="row">
        <div class="col order-md-last">
          <?php woocommerce_content(); ?>
        </div>

        <!-- sidebar -->
        <?php get_sidebar(); ?>
      </div>
    </main><!-- #main -->
  </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
