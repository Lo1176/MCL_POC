<?php
/*
  Plugin Name: mes shortcodes
  Description: Plugin fournissant des shortcodes
  Author: Laurent
  Version: 1.0.0
 */

// fonction d'essai pour afficher un titre et le changer
// en fonction de la langue
function shortcode_agence($atts)
{
    extract(shortcode_atts(
        array(
            'langue' => 'FR'
        ),
        $atts
    ));

    if ($langue == "EN") {
        $text = "karac, your digital communication agency ";
    } else {
        $text = "karac, votre agence de communication digitale";
    }
    return '<h2>' . $text . '</h2>';
}
add_shortcode('agence', 'shortcode_agence');

// fonction pour afficher les 1er enfants de la category parent
// get all product cats for the current post
function mcl_get_first_child_product($atts)
{
    $categories = get_the_terms(get_the_ID(), 'product_cat');
    extract(shortcode_atts(
        array(
            'category' => $categories
        ),
        $atts
    ));
    

    // wrapper to hide any errors from top level categories or products without category
    if ($categories && !is_wp_error($category)) :

        // loop through each cat
        foreach ($categories as $category) :
            // get the children (if any) of the current cat
            $children = get_categories(array('taxonomy' => 'product_cat', 'parent' => $category->term_id));

            if (count($children) == 0) {
                // if no children, then echo the category name.
                echo $category->name;
            }
        endforeach;

    endif;
}
add_shortcode('mcl-children', 'mcl_get_first_child_product');
