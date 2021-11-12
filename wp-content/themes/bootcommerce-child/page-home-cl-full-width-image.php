<?php

/**
 * Template Name: Home page Chateau Laguiole
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
          <h1 id="brand-logo-header" class="d-flex entry-title"><img id="cl-logo" src=<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/10/cl-uncolored.svg'); ?> alt="Maison Château Laguiole image">
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
        <!-- end content from WP  -->

        <!-- collection from Chateau Laguiole -->
        <div class="container-fluid d-flex justify-content-center">
          <div id="collection" class="d-flex justify-content-center flex-wrap col-8">

            <!-- ##### ecrit en DUR !!!!  ##### -->
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/chateau-laguiole/classique/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/CL-classique-bois-erable-scaled.jpeg'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title"><strong>Classique</strong></h5>
                  <p class="card-text">Some quick example text</p>
                </div>
              </a>
            </div>

            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/chateau-laguiole/grand-cru/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/CL-Grand-Cru-bois-débène-entretoise-jaune--scaled.jpeg'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title"><strong>Grand Cru</strong></h5>
                  <p class="card-text">Some quick example text</p>
                </div>
              </a>
            </div>
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/chateau-laguiole/versailles/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/CL-Versailles-Genévrier-600x400.jpeg'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title"><strong>Versailles</strong></h5>
                  <p class="card-text">Some quick example text</p>
                </div>
              </a>
            </div>
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/chateau-laguiole/meilleurs-sommeliers-du-monde/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/CLME3033Enrico-Bernardo--scaled.jpeg'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title"><strong>Meilleur Sommelier</strong></h5>
                  <p class="card-text">Some quick example text</p>
                </div>
              </a>
            </div>
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php #echo do_shortcode('[homeurl]/categorie-produit/chateau-laguiole/opus/'); 
                        ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/CLSOG0201-Grand-Cru-Opus-N°-2-Cocobolo-wood-scaled.jpeg'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title"><strong>Opus</strong></h5>
                  <p class="card-text">Some quick example text</p>
                </div>
              </a>
            </div>
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php #echo do_shortcode('[homeurl]/categorie-produit/chateau-laguiole/melchior/'); 
                        ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/CL-melchior-ironwood.jpeg'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title"><strong>Melchior</strong></h5>
                  <p class="card-text">Some quick example text</p>
                </div>
              </a>
            </div>
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/chateau-laguiole/couteaux-pliants/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/COPGC7102-Corne-blonde-White-horn-1-scaled.jpg'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title"><strong>Couteaux Pliant</strong></h5>
                  <p class="card-text">Some quick example text</p>
                </div>
              </a>
            </div>
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php #echo do_shortcode('[homeurl]/chateau-laguiole/couteaux-de-table/'); 
                        ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/chateau-laguiole-steak-knives-brazilian-rosewood-set-of-6-chateau-laguioler-made-in-france_5000x.jpg'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title"><strong>Couteaux de table</strong></h5>
                  <p class="card-text">Some quick example text</p>
                </div>
              </a>
            </div>

          </div>

        </div>
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
