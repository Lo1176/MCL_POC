<?php

/**
 * Template Name: Home MCL
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

      <header class="entry-header featured-full-width-img height-50 bg-dark text-light mb-3" style="background-image: url('<?php echo $thumb['0']; ?>')">
        <div class="container entry-header h-100 d-flex justify-content-center pb-3">
          <h1 class="text-light bg-secondary p-4 entry-title"><?php the_title(); ?></h1>
        </div>
        <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
        <!-- no title tbe -->
        <!-- no title tbe END -->
      </header>

      <div class="container pb-5">

        <!-- Hook to add something nice -->
        <?php bs_after_primary(); ?>
        <!-- nothing in there for the moment , change it in function.php -->

        <div class="entry-content">
          <!-- all the content for wordpress 'modifier la page' -->
          <?php the_content(); ?>
          <!-- all the content for wordpress 'modifier la page' END -->
          <!-- actualités -->
          <div class="d-flex justify-content-center">
            <h2 class="title-separation">Nos marques</h2>
          </div>
          <div class="brand">

            <a href=<?php echo esc_url(get_permalink(get_page_by_path('chateau-laguiole'))); ?>>
              <img class="brand-box border" alt="Qries" src=<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/10/CL.svg'); ?> style="background-color: #3C3C3B;">
            </a>
            <a href=<?php echo esc_url(get_permalink(get_page_by_path('ligne-w'))); ?>>
              <img class="brand-box border" alt="Qries" src=<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/10/logo-ligneW.svg'); ?> width="100" height="100" background-color="#00FF00">
            </a>
            <img alt="Mateo Gallud image" src=<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/10/logo-matteo.svg'); ?>>
            <figure>
              <div>
                <img alt="Mateo Gallud image" src=<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/10/logo-matteo.svg'); ?>>
              </div>
            </figure>

          </div>
          <div class="brand"></div>
          <div class="brand"></div>
          <div class="brand"></div>
          <div class="d-flex justify-content-center">
            <h2 class="title-separation">Nos actualités</h2>
          </div>

          <?php echo do_shortcode('[bs-post-slider type="post" category="non-classe" order="ASC" orderby="title" posts="8"]'); ?>
          <!-- actualités END -->

          <footer class="entry-footer">

          </footer>

          <?php comments_template(); ?>

        </div><!-- container -->

    </main><!-- #main -->

  </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
