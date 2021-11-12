<?php

/**
 * Template Name: Home page Ligne | W
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

get_header();
?>

<div id="content" class="site-content">
  <div id="primary" class="content-area">

    <main id="main" class="site-main">

      <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
      <header class="entry-header featured-full-width-img text-light mb-3" style="background-image: url('<?php echo $thumb['0']; ?>')">
        <div class="container entry-header d-flex justify-content-center pb-3">
          <h1 id="brand-logo-header" class="bg-primary d-flex p-5 entry-title lw-logo"><img src=<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/10/lw-uncolored.svg'); ?> alt="Maison Château Laguiole image">
        </div>
        <!-- no title tbe -->
        <!-- no title tbe END -->
      </header>

      <div class="container-fluid pb-5">

        <!-- Hook to add something nice -->
        <?php bs_after_primary(); ?>
        <!-- nothing in there for the moment , change it in function.php -->

        <div class="entry-content container">
          <!-- all the content for wordpress 'modifier la page' -->
          <?php the_content(); ?>
          <!-- all the content for wordpress 'modifier la page' END -->
        </div>
        <!-- actualités -->

      </div>


        <footer class="entry-footer">

        </footer>

        <?php comments_template(); ?>

      </div><!-- container -->

    </main><!-- #main -->

  </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
