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

<div id="content" class="site-content container py-5 mt-4">
  <div id="primary" class="content-area">
    <?php
    /**
     * Display category image on category archive
     */
    add_action('woocommerce_show_page_title', 'woocommerce_category_image', 5);
    function woocommerce_category_image()
    {
      if (is_product_category()) {
        global $wp_query;
        $cat = $wp_query->get_queried_object();
        $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
        $image = wp_get_attachment_url($thumbnail_id);
        if ($image) {
          echo '<div class="container-fluid"><img class="featured-full-width-img" src="' . $image . '" alt="' . $cat->name . '" /></div>';
        }
        echo '<div class="container-fluid"><h1>' . $cat->name . '</h1></div>';

      }
    }

    ?>
    <!-- Hook to add something nice -->
    <?php bs_after_primary(); ?>

    <main id="main" class="site-main">

      <!-- Breadcrumb -->
      <?php woocommerce_breadcrumb(); ?>
      <div class="row">

        <div class="text-center col">
          <?php woocommerce_content(); ?>
        </div>
        <!-- sidebar -->
        <?php #get_sidebar(); 
        ?>
        <!-- row -->
      </div>
    </main><!-- #main -->
  </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
