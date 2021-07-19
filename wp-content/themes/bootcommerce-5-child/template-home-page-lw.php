<?php

/**
 * Template Name: Home page Ligne | W
 */

use Automattic\WooCommerce\Blocks\BlockTypes\SingleProduct;
use Automattic\WooCommerce\Blocks\StoreApi\Routes\ProductTags;

get_header();
?>

<div id="content" class="site-content">
    <div id="primary" class="content-area">

        <!-- Hook to add something nice -->
        <?php bs_after_primary(); ?>

        <main id="main" class="site-main">
            <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
            <header class="entry-header featured-full-width-img text-light mb-3" style="background-image: url('<?php echo $thumb['0']; ?>')">
                <div class="container-fluid px-0 pb-5">

                </div><!-- container-fluid full width -->
            </header> <!-- end header -->
            <div class="entry-content">
                <?php #the_content(); 
                ?>

                <!-- test banner -->
                <div class="p-5 rounded-3" style="background-color: #71110f;">
                    <div class="container-fluid text-white py-5">
                        <h1 class="display-5 fw-bold">Ligne | W</h1>
                        <p class="col-md-8 fs-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint commodi est ut pariatur, voluptates, libero id maxime tenetur laudantium esse cumque recusandae error neque ratione et odit veritatis, magnam possimus.</p>
                        <a href="#lw-edito" class="btn btn-outline-light btn-lg" role="button" aria-pressed="true">En savoir plus</a>
                    </div>
                </div> <!-- end banner -->
                <!-- buttons gamme de produit -->
                <div class="container my-3 text-center">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio0" autocomplete="off" checked>
                        <label class="btn btn-outline-secondary" for="btnradio0">Tous</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="btnradio1">Couteau sommelier</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="btnradio2">Couteau de table</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="btnradio3">Autre gamme; etc ...</label>

                        <!-- 3D link -->
                        <a href="https://lignew.clients.arkima.io/configurator/app/index.html" target="_blank" class="btn btn-dark">Personalisation 3D</a>


                    </div>
                </div>
                <div class="container-fluid d-flex">
                    <!-- test LEFT SEARCH  -->
                    <div class="container-fluid col-2">
                        <div class="">

                            <button class="btn btn-outline-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Rechercher</button>
                            <!-- opened OFFCANVAS search -->
                            <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
                                <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Menu ...</h5>
                                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <?php #get_search_form(); 
                                    ?>

                                    <div class="input-group">
                                        <div class="form-outline mb-2 d-flex">
                                            <select class="form-select" aria-label="Default select example" onchange="window.location.href=this.value;">

                                                <optgroup label="- Produit -" data-max-options="1">
                                                    <!-- <option selected></option> -->
                                                    <option value="" disabled selected hidden>- Produit -</option>

                                                    <option value="http://localhost/www/MCL_POC/categorie-produit/prestige/">Prestige</option>
                                                    <option value="http://localhost/www/MCL_POC/categorie-produit/signature">Signature</option>
                                                    <option value="http://localhost/www/MCL_POC/categorie-produit/origine">L'Origine</option>
                                                    <option value="http://localhost/www/MCL_POC/categorie-produit/iroquois">L'Iroquois</option>
                                                    <option value="http://localhost/www/MCL_POC/categorie-produit/essentiel">L'Essentiel</option>
                                                </optgroup>
                                                <!-- <option value="Ligne W">
                                                    <option value="Matéo Gallud">
                                                        <option value="Guy Vialis"> -->

                                                <input type="search" id="searchForm1" class="form-control" placeholder="bois, Ligne W, ..." />
                                                <!-- <label class="form-label" for="form1"></label> -->
                                            </select>
                                            <button type="button" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- option radio button à définir -->
                                    <div class="border-top my-3"> </div>

                                    <div class="btn-group d-flex my-2 gap-3">
                                        <input type="radio" class="btn-check" name="options" id="option1" autocomplete="off" checked />
                                        <label class="btn btn-outline-dark btn-sm rounded" for="option1">option1</label>

                                        <input type="radio" class="btn-check" name="options" id="option2" autocomplete="off" />
                                        <label class="btn btn-outline-dark btn-sm rounded" for="option2">option2</label>

                                        <input type="radio" class="btn-check" name="options" id="option3" autocomplete="off" />
                                        <label class="btn btn-outline-dark btn-sm rounded" for="option3">option3</label>
                                    </div>

                                    <div class="border-top my-3"> </div>

                                    <!-- <input type="checkbox" class="btn-check" id="btn-check-2-outlined" checked autocomplete="off">
                                    <label class="btn btn-outline-dark" for="btn-check-2-outlined">Bois</label><br>
                                    <input type="checkbox" class="btn-check" id="btn-check-2-outlined" checked autocomplete="off">
                                    <label class="btn btn-outline-dark" for="btn-check-2-outlined">Métal</label><br>
                                    <input type="checkbox" class="btn-check" id="btn-check-2-outlined" checked autocomplete="off">
                                    <label class="btn btn-outline-dark" for="btn-check-2-outlined">Bois</label><br>

                                    <input type="radio" class="btn-check" name="options-outlined" id="success-outlined" autocomplete="off" checked>
                                    <label class="btn btn-outline-success" for="success-outlined">Checked success radio</label>

                                    <input type="radio" class="btn-check" name="options-outlined" id="danger-outlined" autocomplete="off">
                                    <label class="btn btn-outline-danger" for="danger-outlined">Danger radio</label> -->

                                    <div class="border-top my-3"> </div>

                                    <!-- tags search https://stackoverflow.com/questions/52320010/add-a-product-filter-for-product-tags -->
                                    <label><?php _e('Tags'); ?></label>
                                    <form action="<?php bloginfo('url'); ?>/" method="get">
                                        <div>
                                            <?php /*
                                            $args = array(
                                                'taxonomy' => 'product_tag', // Taxonomy to return. Valid values are 'category', 'post_tag' or any registered taxonomy.
                                                'show_option_none' => 'Select tag',
                                                'show_count' => 1,
                                                'orderby' => 'name',
                                                'value_field' => 'slug',
                                                'echo' => 0,
                                                'name' => 'tag_product'
                                            );
                                            $select = wp_dropdown_categories($args);
                                            $select = preg_replace("#<select([^>]*)>#", "<select$1 onchange='return this.form.submit()'>", $select);
                                            echo $select;
                                            */
                                            ?>
                                            <noscript>
                                                <div><input type="submit" value="Filter" /></div>
                                            </noscript>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END SEARCH -->

                    <!-- test gammes  (PRODUCTS CATEGORIES per BRAND) -->
                    <div class="container-fluid col-10">
                        <div class="d-flex flex-wrap col-12">
                            <?php
                            // echo do_shortcode('[products category="ligne-w"]');
                            ?>
                            <div class="card m-2" style="width: 18rem;">
                                <img class="card-img-top" src="https://www.layole.com/58554-medium_default/laguiole-pliant-12-cm-abeille-forgee-lame-seule-manche-bois-de-rose-avec-finition-inox-mat.jpg" alt="Produc image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Prestige</h5>
                                    <p class="card-text">Tire-bouchon, bois noble, corne, finition miroir, double levier, écrin en bois make up the bulk of the product's content.</p>
                                    <a href="http://localhost/www/MCL_POC/categorie-produit/prestige/" class="btn btn-dark">Voir le produit</a>
                                </div>
                            </div>
                            <div class="card m-2" style="width: 18rem;">
                                <img class="card-img-top" src="https://www2.artetsignature.com/940-large_default/sommelier-en-bois-de-genevrier-forge-de-laguiole.jpg" alt="Produc image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Signature</h5>
                                    <p class="card-text">J’aime mon lieu de vie, on s’y sent bien et j’aime y partager des bons moments avec mes amis bulk of the product's content.</p>
                                    <a href="http://localhost/www/MCL_POC/categorie-produit/signature/" class="btn btn-dark">Voir le produit</a>
                                </div>
                            </div>
                            <div class="card m-2" style="width: 18rem;">
                                <img class="card-img-top" src="https://www.toc.fr/910-thickbox_default/sommelier-origine-bois.jpg" alt="Produc image cap">
                                <div class="card-body">
                                    <h5 class="card-title">L'Origine</h5>
                                    <p class="card-text">L’originalité, la simplicité, l’histoire et le retour aux origines du vin and make up the bulk of the product's content.</p>
                                    <a href="http://localhost/www/MCL_POC/categorie-produit/origine/" class="btn btn-dark">Voir le produit</a>
                                </div>
                            </div>
                            <div class="card m-2" style="width: 18rem;">
                                <img class="card-img-top" src="https://www.mon-droguiste.com/media/catalog/product/cache/a10f16d04b06e97a621331e929457fb9/s/o/sommelier_iroquois_tendance.jpg" alt="Produc image cap">
                                <div class="card-body">
                                    <h5 class="card-title">L'Iroquois</h5>
                                    <p class="card-text">Originalité, culture urbaine/street art, inspirations de voyage up the bulk of the product's content.</p>
                                    <a href="http://localhost/www/MCL_POC/categorie-produit/iroquois/" class="btn btn-dark">Voir le produit</a>
                                </div>
                            </div>
                            <div class="card m-2" style="width: 18rem;">
                                <img class="card-img-top" src="https://www.layole.com/58554-medium_default/laguiole-pliant-12-cm-abeille-forgee-lame-seule-manche-bois-de-rose-avec-finition-inox-mat.jpg" alt="Produc image cap">
                                <div class="card-body">
                                    <h5 class="card-title">L'Essentiel</h5>
                                    <p class="card-text">Consommateurs ou professionnels en quête d’un objet efficace avec un confort d’utilisation optimale up the bulk of the product's content.</p>
                                    <a href="http://localhost/www/MCL_POC/categorie-produit/essentiel/" class="btn btn-dark">Voir le produit</a>
                                </div>
                            </div>
                            <!-- <div class="card m-2" style="width: 18rem;">
                                <img class="card-img-top" src="https://www.layole.com/58554-medium_default/laguiole-pliant-12-cm-abeille-forgee-lame-seule-manche-bois-de-rose-avec-finition-inox-mat.jpg" alt="Produc image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Product title</h5>
                                    <p class="card-text">Some quick example text to build on the product title and make up the bulk of the product's content.</p>
                                    <a href="#" class="btn btn-dark">Go somewhere</a>
                                </div>
                            </div>
                            <div class="card m-2" style="width: 18rem;">
                                <img class="card-img-top" src="..." alt="Produc image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Product title</h5>
                                    <p class="card-text">Some quick example text to build on the product title and make up the bulk of the product's content.</p>
                                    <a href="#" class="btn btn-dark">Go somewhere</a>
                                </div>
                            </div>
                            <div class="card m-2" style="width: 18rem;">
                                <img class="card-img-top" src="..." alt="Produc image cap">
                                <div class="card-body">
                                    <h5 class="card-title">Product title</h5>
                                    <p class="card-text">Some quick example text to build on the product title and make up the bulk of the product's content.</p>
                                    <a href="#" class="btn btn-dark">Go somewhere</a>
                                </div>
                            </div> -->
                        </div>

                    </div>
                </div>

                <!-- test edito -->
                <div class="container col-xl-12 col-md-8 fs-4 bg-white rounded-3">
                    <h3 id="lw-edito" class="fs-1 text-center">Edito</h3>
                    <div class="container text-center py-5">
                        <p class="">
                            Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat...
                        </p>
                        <!-- read more -->
                        <div class="accordion accordion-flush" id="accordionReadMore">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="readMore">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="readMore" data-bs-parent="#accordionReadMore">
                                <div class="accordion-body">
                                    <p class="fs-4">ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <h2 class="text-dark">toto content</h2> -->

                </div>


            </div>


            <!-- <footer class="entry-footer">

                    </footer> -->

            <?php #comments_template(); 
            ?>




        </main><!-- #main -->

    </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
