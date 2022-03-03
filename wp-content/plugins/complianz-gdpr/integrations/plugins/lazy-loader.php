<?php
defined( 'ABSPATH' ) or die();
function cmplz_lazyloader_convert_data_src() {
	ob_start();
	?>
	<script>
		document.addEventListener("cmplz_cookie_banner_data", function() {
			document.querySelectorAll('.cmplz-placeholder-element').forEach(obj => {
				if (obj.classList.contains('cmplz-iframe')) {
					obj.setAttribute('data-src', obj.getAttribute('data-src-cmplz'));
				}
			});
		});
	</script>
	<?php
	$script = ob_get_clean();
	$script = str_replace(array('<script>', '</script>'), '', $script);
	wp_add_inline_script( 'cmplz-cookiebanner', $script);
}
add_action( 'wp_enqueue_scripts', 'cmplz_lazyloader_convert_data_src',PHP_INT_MAX );
