<?php

/**
 * Template Name: Home page MCL
 */

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
            </header>
            <div class="entry-content">
                <?php the_content(); ?>

                <!-- test banner -->
                <div class="p-5 bg-light rounded-3">
                    <div class="container-fluid py-5">
                        <h1 class="display-5 fw-bold">Ligne | W</h1>
                        <p class="col-md-8 fs-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint commodi est ut pariatur, voluptates, libero id maxime tenetur laudantium esse cumque recusandae error neque ratione et odit veritatis, magnam possimus.</p>
                        <a href="#lw-edito" class="btn btn-outline-dark btn-lg" role="button" aria-pressed="true">En savoir plus</a>
                    </div>
                </div>
                <!-- buttons gamme de produit -->
                <div class="container my-3 text-center">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="btnradio1">Couteau sommelier</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="btnradio2">Couteau de table</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="btnradio3">Autre gamme</label>

                        <!-- 3D link -->
                        <a href="https://lignew.clients.arkima.io/configurator/app/index.html" target="_blank" class="btn btn-dark">Personalisation 3D</a>


                    </div>
                </div>
                <div class="container-fluid d-flex bg-primary">
                    <!-- test recherche -->
                    <div class="container-fluid col-2 bg-light">
                        <div class="bg-warning">

                            <button class="btn btn-outline-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Rechercher</button>
                            <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
                                <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Colored with scrolling</h5>
                                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <p>Try scrolling the rest of the page to see this option in action.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- test gammes -->
                    <div class="container-fluid col-10">
                        <div class="d-flex flex-wrap col-12 bg-danger">
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
                            </div>
                        </div>

                    </div>
                </div>

                <!-- test edito -->
                <div class="p-5 mb-4 bg-light rounded-3">
                    <h3 id="lw-edito" class="display-1 text-center">Edito</h3>
                    <div class="container-fluid py-5">
                        <p class="col-md-8 fs-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint commodi est ut pariatur, voluptates, libero id maxime tenetur laudantium esse cumque recusandae error neque ratione et odit veritatis, magnam possimus.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint commodi est ut pariatur, voluptates, libero id maxime tenetur laudantium esse cumque recusandae error neque ratione et odit veritatis, magnam possimus
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint commodi est ut pariatur, voluptates, libero id maxime tenetur laudantium esse cumque recusandae error neque ratione et odit veritatis, magnam possimus
                        </p>
                        <button class="btn btn-outline-dark btn-lg" type="button">En savoir plus</button>

                    </div>
                </div>

                <!-- <h2 class="text-dark">toto content</h2> -->
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
