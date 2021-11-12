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
        <div class="container-fluid col-10">
          <div id="collection" class="d-flex flex-wrap col-12">

            <!-- ##### ligne-w ecrit en DUR !!!!  ##### -->
            <div class="card m-2" style="width: 18rem;">
              <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/origine/'); ?>">
                <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-origine-tbe.png'); ?>" alt="Produc image cap">
                <div class="card-body">
                  <h5 class="card-title"><strong>ORIGINE</strong></h5>
                  <p class="card-text">Grands crus j’élevais, grands crus j’ouvrirai</p>
                </div>
              </a>
            </div>

          <div class="card m-2" style="width: 18rem;">
            <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/origine/'); ?>">
              <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/11/lw-origine-prestige-tbe.png'); ?>" alt="Produc image cap">
              <div class="card-body">
                <h5 class="card-title">ORIGINE <strong>PRESTIGE</strong></h5>
                <p class="card-text">lw-origine-prestige-tbe.png</p>
              </div>
            </a>
          </div>

        

   

  



        <!-- ------  -->
        <div class="card m-2" style="width: 18rem;">
          <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/LW-prestige-corne.jpeg'); ?>" alt="Produc image cap">
          <div class="card-body">
            <h5 class="card-title">Prestige</h5>
            <p class="card-text">Tire-bouchon, bois noble, corne, finition miroir, double levier, écrin en bois make up the bulk of the product's content.</p>
            <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/ligne-w/prestige/'); ?>" class="btn btn-dark">Voir les produits</a>
          </div>
        </div>
        <div class="card m-2" style="width: 18rem;">
          <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/LW-signature.jpeg'); ?>" alt="Produc image cap">
          <div class="card-body">
            <h5 class="card-title">Signature</h5>
            <p class="card-text">J’aime mon lieu de vie, on s’y sent bien et j’aime y partager des bons moments avec mes amis bulk of the product's content.</p>
            <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/signature/'); ?>" class="btn btn-dark">Voir les produits</a>
          </div>
        </div>

        <div class="card m-2" style="width: 18rem;">
          <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/LW-iroquois-color-vinyard-scaled-1.jpeg'); ?>" alt="Produc image cap">
          <div class="card-body">
            <h5 class="card-title">L'Iroquois "colors"</h5>
            <p class="card-text">Originalité, culture urbaine/street art, inspirations de voyage up the bulk of the product's content.</p>
            <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/ligne-w/iroquois-colors/'); ?>" class="btn btn-dark">Voir les produits</a>
          </div>
        </div>
        <div class="card m-2" style="width: 18rem;">
          <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/LW-iroquois-zinc-pack-scaled-1.jpeg'); ?>" alt="Produc image cap">
          <div class="card-body">
            <h5 class="card-title">L'Iroquois "zinc"</h5>
            <p class="card-text">Originalité, culture urbaine/street art, inspirations de voyage up the bulk of the product's content.</p>
            <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/ligne-w/iroquois-colors/'); ?>" class="btn btn-dark">Voir les produits</a>
          </div>
        </div>
        <div class="card m-2" style="width: 18rem;">
          <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/LW-iroquois-wood-pack-scaled-1.jpeg'); ?>" alt="Produc image cap">
          <div class="card-body">
            <h5 class="card-title">L'Iroquois "wood"</h5>
            <p class="card-text">Originalité, culture urbaine/street art, inspirations de voyage up the bulk of the product's content.</p>
            <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/ligne-w/iroquois-wood/'); ?>" class="btn btn-dark">Voir les produits</a>
          </div>
        </div>
        <div class="card m-2" style="width: 18rem;">
          <img class="card-img-top" src="<?php echo do_shortcode('[homeurl]/wp-content/uploads/2021/07/LW-iroquois-URBAN-COMICS-pack-scaled.jpeg'); ?>" alt="Produc image cap">
          <div class="card-body">
            <h5 class="card-title">L'Iroquois "urban"</h5>
            <p class="card-text">Originalité, culture urbaine/street art, inspirations de voyage up the bulk of the product's content.</p>
            <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/ligne-w/iroquois-urban/'); ?>" class="btn btn-dark">Voir les produits</a>
          </div>
        </div>
        <div class="card m-2" style="width: 18rem;">
          <img class="card-img-top" src="https://www.layole.com/58554-medium_default/laguiole-pliant-12-cm-abeille-forgee-lame-seule-manche-bois-de-rose-avec-finition-inox-mat.jpg" alt="Produc image cap">
          <div class="card-body">
            <h5 class="card-title">L'Essentiel</h5>
            <p class="card-text">Consommateurs ou professionnels en quête d’un objet efficace avec un confort d’utilisation optimale up the bulk of the product's content.</p>
            <a href="<?php echo do_shortcode('[homeurl]/categorie-produit/essentiel/'); ?>" class="btn btn-dark">Voir les produits</a>
          </div>
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
