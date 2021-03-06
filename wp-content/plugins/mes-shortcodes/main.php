<?php
/*
  Plugin Name: mes shortcodes
  Description: Plugin fournissant des shortcodes
  Author: Laurent
  Version: 1.0.0
 */

/**
 *  Shortcode to display stock product
 */
function mcl_show_stock_shop() {
    global $product;
    echo wc_get_stock_html($product);
}
add_shortcode('stock-mcl', 'mcl_show_stock_shop');

/**
 *  to prevent break links between localhost and http
 */
function homeURLshortcode() {
    return home_url();
}
add_shortcode('homeurl', 'homeURLshortcode');


// // fonction pour afficher les 1er enfants de la category parent
// // get all product cats for the current post
// function mcl_get_first_child_product($atts)
// {
//     $categories = get_the_terms(get_the_ID(), 'product_cat');
//     extract(shortcode_atts(
//         array(
//             'category' => $categories
//         ),
//         $atts
//     ));


//     // wrapper to hide any errors from top level categories or products without category
//     if ($categories && !is_wp_error($category)) :

//         // loop through each cat
//         foreach ($categories as $category) :
//             // get the children (if any) of the current cat
//             $children = get_categories(array('taxonomy' => 'product_cat', 'parent' => $category->term_id));

//             if (count($children) == 0) {
//                 // if no children, then echo the category name.
//                 echo $category->name;
//             }
//         endforeach;

//     endif;
// }

function mcl_get_first_child_product()
{

    $taxonomy     = 'product_cat';
    $orderby      = 'name';
    $show_count   = 0;      // 1 for yes, 0 for no
    $pad_counts   = 0;      // 1 for yes, 0 for no
    $hierarchical = 1;      // 1 for yes, 0 for no  
    $title        = '';
    $empty        = 0;

    $args = array(
        'taxonomy'     => $taxonomy,
        'orderby'      => $orderby,
        'show_count'   => $show_count,
        'pad_counts'   => $pad_counts,
        'hierarchical' => $hierarchical,
        'title_li'     => $title,
        'hide_empty'   => $empty
    );
    $all_categories = get_categories($args);
    foreach ($all_categories as $cat) {
        if ($cat->category_parent == 0) {
            $category_id = $cat->term_id;
            echo '<br /><a href="' . get_term_link($cat->slug, 'product_cat') . '">' . $cat->name . '</a>';

            $args2 = array(
                'taxonomy'     => $taxonomy,
                'child_of'     => 0,
                'parent'       => $category_id,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty
            );
            $sub_cats = get_categories($args2);
            if ($sub_cats) {
                foreach ($sub_cats as $sub_category) {
                    echo  '<br/><a href="' . get_term_link($sub_category->slug, 'product_cat') . '">' . $sub_category->name . '</a>';
                }
            }
        }
    }
}
add_shortcode('mcl-children', 'mcl_get_first_child_product');

/**
 * R??cup??re la hierarchie compl??te de la taxonomie li??e au post
 *
 * @param $post L'ID du post 
 * @param string $taxonomy Nom de la taxonomie recherch??e
 * @return array
 */
function get_taxonomy_hierarchy($post, $taxonomy)
{
    // On ne veut qu'une taxonomie
    $taxonomy = is_array($taxonomy) ? array_shift($taxonomy) : $taxonomy;
    // On r??cup??re tous les terms li??s au post
    $terms = get_the_terms($post->ID, $taxonomy);
    // Je pr??pare l'array final qui contiendra la hierarchie des terms dans l'ordre
    $complete_hierarchy = array();
    //Je boucle sur les terms trouv??s
    foreach ($terms as $parent_term) {
        //Si ce n'est pas un term parent, on passe au suivant
        if ($parent_term->parent !== 0) {
            continue;
            //Si c'est un term parent
        } else {
            //On stocke ses infos 
            $parent_name = $parent_term->name;
            $parent_id = $parent_term->term_id;
            $parent_slug = $parent_term->slug;
            //On pr??pare un array qui va contenir le parent et ses enfants
            $current_hierarchy = array(
                'parent_name' => $parent_name,
                'parent_slug' => $parent_slug,
                'children' => array()
            );
            //On boucle de nouveau sur l'ensemble des terms, on utilise seulement ceux qui sont les enfants directs du parent en cours (de la premi??re boucle)
            foreach ($terms as $child_term) {
                if ($child_term->parent == $parent_id) {
                    $child_name = array(
                        'name' => $child_term->name
                    );
                    //on range le term enfant dans l'array pr??par?? juste avant
                    array_push($current_hierarchy['children'], $child_name);
                }
            }
            //On pousse l'array parent et enfants dans l'array final
            array_push($complete_hierarchy, $current_hierarchy);
        }
    }

    // On renvoie la hierarchie dans l'ordre des terms li??s au post
    return $complete_hierarchy;
}
add_shortcode('mcl-children2', 'get_taxonomy_hierarchy');

/**
 * Shortcode to get the title
 */
function mcl_get_the_page_title() {
    return get_the_title();
}
add_shortcode('page-title', 'mcl_get_the_page_title');


// //Warning
// : Attempt to read property "ID" on string in
// /Users/laurentbinder/Sites/www/MCL_POC/wp-content/plugins/mes-shortcodes/main.php
// on line
// 119


// Warning
// : foreach() argument must be of type array|object, bool given in
// /Users/laurentbinder/Sites/www/MCL_POC/wp-content/plugins/mes-shortcodes/main.php
// on line
// 123


// Warning
// : Array to string conversion in
// /Users/laurentbinder/Sites/www/MCL_POC/wp-includes/shortcodes.php
// on line
// 356

// Array