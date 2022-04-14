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
      <header class="entry-header d-flex featured-full-width-img-brand text-light" style="background-image: url('<?php echo $thumb['0']; ?>')">
        <div class="container entry-header d-flex justify-content-center">
          <div id="brand-logo-header" class="d-flex entry-title">
            <?php include get_stylesheet_directory() . '/img/svg/cl-box.svg';?>
          </div>
        </div>
      </header>


      <div class="container-fluid pb-5">

        <!-- Hook to add something nice -->
        <?php bs_after_primary(); ?>
        <!-- nothing in there for the moment , change it in function.php -->

        <div class="entry-content">
          <div class="row">
            <!-- sidebar -->
            <?php #get_sidebar(); 
            ?>
            <div class="col">
              <div class="container  mt-5 mb-2">
                <!-- content from wordpress 'modifier la page' -->
                <?php the_content(); ?>

              </div><!-- container  -->

              <!-- collection from Chateau Laguiole -->
              <div class="container-fluid d-flex justify-content-center">
                <!-- <div id="collection" class="d-flex justify-content-center justify-content-sm-around flex-wrap"> -->
                <div id="collection" class="row">

                  <!-- #####   ##### -->
                  <div class="col-sm-6 col-xl-3 mb-5">
                    <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/chateau-laguiole/classique/'); ?>">
                      <!-- <img class="card-img-top" src="<?php #echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/CL-classique-bois-erable-scaled.jpeg'); 
                                                          ?>" alt="Produc image cap"> -->
                      <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-iroquois-colors-tbe.png'); ?>" alt="Produc image cat">
                      <div class="card-body">
                        <h5 class="card-title"><strong>Classique</strong></h5>
                        <p class="card-text">Some quick example text</p>
                      </div>
                    </a>
                  </div>

                  <div class="col-sm-6 col-xl-3 mb-5">
                    <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/chateau-laguiole/grand-cru/'); ?>">
                      <!-- <img class="card-img-top" src="<?php #echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/CL-Grand-Cru-bois-débène-entretoise-jaune--scaled.jpeg'); 
                                                          ?>" alt="Produc image cap"> -->
                      <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-iroquois-colors-tbe.png'); ?>" alt="Produc image cat">
                      <div class="card-body">
                        <h5 class="card-title"><strong>Grand Cru</strong></h5>
                        <p class="card-text">Some quick example text</p>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-6 col-xl-3 mb-5">
                    <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/chateau-laguiole/versailles/'); ?>">
                      <!-- <img class="card-img-top" src="<?php #echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/CL-Versailles-Genévrier-600x400.jpeg'); 
                                                          ?>" alt="Produc image cap"> -->
                      <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-iroquois-colors-tbe.png'); ?>" alt="Produc image cat">
                      <div class="card-body">
                        <h5 class="card-title"><strong>Versailles</strong></h5>
                        <p class="card-text">Some quick example text</p>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-6 col-xl-3 mb-5">
                    <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/chateau-laguiole/meilleur-sommelier/'); ?>">
                      <!-- <img class="card-img-top" src="<?php #echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/CLME3033Enrico-Bernardo--scaled.jpeg'); 
                                                          ?>" alt="Produc image cap"> -->
                      <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-iroquois-colors-tbe.png'); ?>" alt="Produc image cat">
                      <div class="card-body">
                        <h5 class="card-title"><strong>Meilleur Sommelier</strong></h5>
                        <p class="card-text">Some quick example text</p>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-6 col-xl-3 mb-5">
                    <a href="<?php #echo do_shortcode('[homeurl]/categorie-produit/chateau-laguiole/opus/'); 
                              ?>">
                      <!-- <img class="card-img-top" src="<?php #echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/CLSOG0201-Grand-Cru-Opus-N°-2-Cocobolo-wood-scaled.jpeg'); 
                                                          ?>" alt="Produc image cap"> -->
                      <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-iroquois-colors-tbe.png'); ?>" alt="Produc image cat">
                      <div class="card-body">
                        <h5 class="card-title"><strong>Opus</strong></h5>
                        <p class="card-text">Some quick example text</p>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-6 col-xl-3 mb-5">
                    <a href="<?php #echo do_shortcode('[homeurl]/categorie-produit/chateau-laguiole/melchior/'); 
                              ?>">
                      <!-- <img class="card-img-top" src="<?php #echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/CL-melchior-ironwood.jpeg'); 
                                                          ?>" alt="Produc image cap"> -->
                      <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-iroquois-colors-tbe.png'); ?>" alt="Produc image cat">
                      <div class="card-body">
                        <h5 class="card-title"><strong>Melchior</strong></h5>
                        <p class="card-text">Some quick example text</p>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-6 col-xl-3 mb-5">
                    <a href="<?php echo do_shortcode('[homeurl]/chateau-laguiole/couteaux-pliants/'); ?>">
                      <!-- <img class="card-img-top" src="<?php #echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/COPGC7102-Corne-blonde-White-horn-1-scaled.jpg'); 
                                                          ?>" alt="Produc image cap"> -->
                      <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-iroquois-colors-tbe.png'); ?>" alt="Produc image cat">
                      <div class="card-body">
                        <h5 class="card-title">Couteaux <strong>Pliant</strong></h5>
                        <p class="card-text">Some quick example text</p>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-6 col-xl-3 mb-5">
                    <a href="<?php #echo do_shortcode('[homeurl]/chateau-laguiole/couteaux-de-table/'); 
                              ?>">
                      <!-- <img class="card-img-top" src="<?php #echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/chateau-laguiole-steak-knives-brazilian-rosewood-set-of-6-chateau-laguioler-made-in-france_5000x.jpg'); 
                                                          ?>" alt="Produc image cap"> -->
                      <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-iroquois-colors-tbe.png'); ?>" alt="Produc image cat">
                      <div class="card-body">
                        <h5 class="card-title">Couteaux de <strong>table</strong></h5>
                        <p class="card-text">Some quick example text</p>
                      </div>
                    </a>
                  </div>
                </div><!-- #collection -->
              </div><!-- container-fluid -->
            </div><!-- col -->
          </div><!-- row -->
        </div><!-- entry-content -->



        <footer class="entry-footer">

        </footer>

        <?php comments_template(); ?>

      </div><!-- container-fluid -->

    </main><!-- #main -->

  </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
