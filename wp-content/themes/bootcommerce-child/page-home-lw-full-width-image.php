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
          <h1 id="brand-logo-header" class="d-flex entry-title"><img id="lw-logo" src=<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/10/lw-uncolored.svg'); ?> alt="Maison Château Laguiole image">
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

        <!-- collection from Ligne W -->
        <div class="container-fluid d-flex justify-content-center">
          <div id="collection" class="d-flex justify-content-center flex-wrap col-8">

            <!-- ##### ligne-w ecrit en DUR !!!!  ##### -->
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/origine/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-origine-scaled.jpg'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title"><strong>ORIGINE</strong></h5>
                  <p class="card-text">Grands crus j’élevais, grands crus j’ouvrirai</p>
                </div>
              </a>
            </div>

            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/prestige/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-origine-prestige-scaled.jpg'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title">ORIGINE <strong>PRESTIGE</strong></h5>
                  <p class="card-text">Raffiné, associant tradition et modernité</p>
                </div>
              </a>
            </div>
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/signature/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-signature.png'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title">LE <strong>W SIGNATURE</strong></h5>
                  <p class="card-text">Aux courbes raffinées</p>
                </div>
              </a>
            </div>
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/iroquois-urban/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-origine-prestige-scaled.jpg'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title">L'IROQUOIS <strong>URBAN</strong></h5>
                  <p class="card-text">Le street art s’invite à votre table</p>
                </div>
              </a>
            </div>
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/iroquois-colors/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-iroquois-colors-tbe.png'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title">L'IROQUOIS <strong>colors</strong></h5>
                  <p class="card-text">Convivial et respectueux de l'environnement</p>
                </div>
              </a>
            </div>
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/iroquois-zinc/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-iroquois-zinc-tbe.png'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title">L'IROQUOIS <strong>zinc</strong></h5>
                  <p class="card-text">Un cadeau très masculin</p>
                </div>
              </a>
            </div>
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/iroquois-wood/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-iroquois-wood-tbe.png'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title">L'IROQUOIS <strong>wood</strong></h5>
                  <p class="card-text">Noble et intemporel</p>
                </div>
              </a>
            </div>
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/essentiel/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-essentiel.jpg'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title">L'<strong>essentiel</strong></h5>
                  <p class="card-text">Une inspiration pop pour les professionnels et les particuliers</p>
                </div>
              </a>
            </div>
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/kit-sommelier/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-kit-scaled.jpg'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title">le kit <strong>sommelier</strong></h5>
                  <p class="card-text">Le duo indispensable pour les amoureux du vin</p>
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
