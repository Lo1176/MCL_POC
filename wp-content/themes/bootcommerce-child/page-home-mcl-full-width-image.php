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

      <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
      <header class="entry-header featured-full-width-img text-light mb-3" style="background-image: url('<?php echo $thumb['0']; ?>')">
        <div class="container entry-header d-flex justify-content-center pb-3">
          <h1 id="brand-logo-header" class="d-flex entry-title logo"><img id="mcl-logo" src=<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/10/mcl-white.svg'); ?> alt="Maison Château Laguiole image">
        </div>
        <!-- no title tbe -->
        <!-- no title tbe END -->
      </header>

      <div class="container-fluid pb-5">

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

          <div class="brand text-center">

            <a href=<?php echo esc_url(get_permalink(get_page_by_path('chateau-laguiole'))); ?>>
              <figure class="figure">
                <img src=<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/10/cl-uncolored.svg'); ?> id="cl-logo" class="figure-img img-fluid" alt="Château Laguiole image">
              </figure>
            </a>
            <a href=<?php echo esc_url(get_permalink(get_page_by_path('ligne-w'))); ?>>
              <figure class="figure">
                <img src=<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/10/lw-uncolored.svg'); ?> id="lw-logo" class="figure-img img-fluid" alt="Ligne W image">
              </figure>
            </a>
            <a href=<?php echo esc_url(get_permalink(get_page_by_path('mateo-gallud'))); ?>>
              <figure class="figure">
                <img src=<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/10/mg-uncolored.svg'); ?> id="mg-logo" class="figure-img img-fluid" alt="Mateo Gallud image">
              </figure>
            </a>
            <a href=<?php echo esc_url(get_permalink(get_page_by_path('guy-vialis'))); ?>>
              <figure class="figure">
                <img src=<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/10/gv-uncolored.svg'); ?> id="gv-logo" class="figure-img img-fluid" alt="Guy Vialis image">
              </figure>
            </a>

          </div> <!-- .brand -->

          <div class="d-flex justify-content-center">
            <h2 class="title-separation">Nos actualités</h2>
          </div>


          <div class="container">
            <?php echo do_shortcode('[bs-swiper-card type="post" category="non-classe" order="DESC" orderby="date" posts="8"]'); ?>
          </div>
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
