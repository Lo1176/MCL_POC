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
      <header class="entry-header d-flex featured-full-width-img-brand text-light" style="background-image: url('<?php echo $thumb['0']; ?>')">
        <div class="container entry-header d-flex justify-content-center">
          <div id="brand-logo-header" class="d-flex entry-title">
            <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/svg/mcl-box.svg" id="mcl-box" class="figure-img bg-black img-fluid" alt="Maison Château Laguiole image">
          </div>
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

          <div class="brand d-flex flex-wrap justify-content-center">

            <a href=<?php echo esc_url(get_permalink(get_page_by_path('chateau-laguiole'))); ?>>
              <figure class="figure">
                <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/svg/cl-box.svg" id="cl-box" class="figure-img bg-black img-fluid" alt="Château Laguiole image">
              </figure>
            </a>
            <a href=<?php echo esc_url(get_permalink(get_page_by_path('ligne-w'))); ?>>
              <figure class="figure">
                <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/svg/lw-box.svg" id="lw-box" class="figure-img img-fluid" alt="Ligne W image">
              </figure>
            </a>
            <a href=<?php echo esc_url(get_permalink(get_page_by_path('soon'))); ?>>
              <figure class="figure">
                <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/svg/mg-box.svg" id="mg-box" class="figure-img img-fluid" alt="Mateo Gallud image">
              </figure>
            </a>
            <a href=<?php echo esc_url(get_permalink(get_page_by_path('soon'))); ?>>
              <figure class="figure">
                <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/svg/gv-box.svg" id="gv-box" class="figure-img img-fluid" alt="Guy Vialis image">
              </figure>
            </a>

          </div> <!-- .brand -->

          <div class="d-flex justify-content-center">
            <h2 id="mcl-actuality" class="title-separation">Nos actualités</h2>
          </div>


          <div class="container-fluid mx-sm-2">
            <?php echo do_shortcode('[bs-swiper-card type="post" category="non-classe" order="DESC" orderby="date" posts="8"]'); ?>
          </div>
          <!-- actualités END -->

          <footer class=" entry-footer">

          </footer>

          <?php comments_template(); ?>

        </div><!-- container -->

    </main><!-- #main -->

  </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
