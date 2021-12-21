<?php

/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 */

if (!is_active_sidebar('sidebar-1')) {
  return;
}
?>
<div class="sidebar-1 col-md-3 col-xxl-2 mt-4 mt-md-0 ms-1">
  <aside id="secondary" class="widget-area">
    <?php dynamic_sidebar('sidebar-1'); ?>
  </aside>
  <!-- #secondary -->
</div>