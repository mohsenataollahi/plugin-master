<?php

/**
 * Register Menu ToShow Table
 */

use src\AdminDisplay;

add_action('admin_menu', 'registerMenuForAdminDisplay',10);
function registerMenuForAdminDisplay(): void {
	add_menu_page(
		__('Books Info', 'example-plugin'),
		__('Books Info', 'example-plugin'),
		'manage_options',
		'books-info-admin',
		'MenuForAdminDisplayHandler',
		'dashicons-book-alt',
		6
	);
}

function MenuForAdminDisplayHandler(): void
{
	// include template
	$books_info_list_table = new AdminDisplay();
	$books_info_list_table->prepare_items();
	?>
	<div class="wrap">
		<h2><?php _e('Books Info', 'example-plugin'); ?></h2>
		<!-- نمایش جدول -->
		<form method="post">
			<?php $books_info_list_table->display(); ?>
		</form>
	</div>
	<?php

}