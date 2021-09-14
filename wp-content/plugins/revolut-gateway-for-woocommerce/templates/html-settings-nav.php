<?php
global $current_section;
$tabs       = apply_filters( 'wc_revolut_settings_nav_tabs', array() );
$last       = count( $tabs );
$idx        = 0;
$tab_active = false;
?>
<div class="wc-revolut-settings-logo">
    <img style="height:50px;margin-bottom: 15px;margin-left:15px" src="<?php echo WC_REVOLUT_PLUGIN_URL . '/assets/images/revolut_business_logo.png'; ?>"/>
</div>
<div class="revolut-settings-nav">
	<?php foreach ( $tabs as $id => $tab ) : $idx ++ ?>
        <a class="nav-tab <?php if ( $current_section === $id || ( ! $tab_active && $last === $idx ) ) {
			echo 'nav-tab-active';
			$tab_active = true;
		} ?>"
           href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . $id ); ?>"><?php echo esc_attr( $tab ); ?></a>
	<?php endforeach; ?>
</div>
<div class="clear"></div>