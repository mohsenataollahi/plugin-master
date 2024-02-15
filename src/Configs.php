<?php

namespace src;

use \Rabbit\Utils\Singleton;

class Configs extends Singleton {

	protected function __construct() {
		// hook into the init action and call registerBookStorePostType to add book post type
		add_action( 'init', [ $this, 'registerBookStorePostType' ] );
		// hook into the init action and call registerBookTaxonomies to add taxonomy to book
		add_action( 'init', [ $this, 'registerBookTaxonomies' ] );
	}

	/**
	 * Register a custom post type called "Book".
	 */
	function registerBookStorePostType(): void {
		$labels = [
			'name'                  => _x( 'Books', 'Post type general name', 'example-plugin' ),
			'singular_name'         => _x( 'Book', 'Post type singular name', 'example-plugin' ),
			'menu_name'             => _x( 'Books', 'Admin Menu text', 'example-plugin' ),
			'name_admin_bar'        => _x( 'Book', 'Add New on Toolbar', 'example-plugin' ),
			'add_new'               => __( 'Add New', 'example-plugin' ),
			'add_new_item'          => __( 'Add New Book', 'example-plugin' ),
			'new_item'              => __( 'New Book', 'example-plugin' ),
			'edit_item'             => __( 'Edit Book', 'example-plugin' ),
			'view_item'             => __( 'View Book', 'example-plugin' ),
			'all_items'             => __( 'All Books', 'example-plugin' ),
			'search_items'          => __( 'Search Books', 'example-plugin' ),
			'parent_item_colon'     => __( 'Parent Books:', 'example-plugin' ),
			'not_found'             => __( 'No books found.', 'example-plugin' ),
			'not_found_in_trash'    => __( 'No books found in Trash.', 'example-plugin' ),
			'featured_image'        => _x( 'Book Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'example-plugin' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'example-plugin' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'example-plugin' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'example-plugin' ),
			'archives'              => _x( 'Book archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'example-plugin' ),
			'insert_into_item'      => _x( 'Insert into book', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'example-plugin' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this book', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'example-plugin' ),
			'filter_items_list'     => _x( 'Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'example-plugin' ),
			'items_list_navigation' => _x( 'Books list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'example-plugin' ),
			'items_list'            => _x( 'Books list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'example-plugin' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => [ 'slug' => 'book' ],
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ],
		];

		register_post_type( 'book', $args );
	}

	/**
	 * Create two taxonomies, Publishers and Authors for the post type "book".
	 */
	function registerBookTaxonomies(): void {

		// Add 1st taxonomy: Publisher
		$labels = array(
			'name'                       => _x( 'Publishers', 'taxonomy general name', 'example-plugin' ),
			'singular_name'              => _x( 'Publisher', 'taxonomy singular name', 'example-plugin' ),
			'menu_name'                  => __( 'Publisher', 'example-plugin' ),
			'all_items'                  => __( 'All Publishers', 'example-plugin' ),
			'edit_item'                  => __( 'Edit Publisher', 'example-plugin' ),
			'view_item'                  => __( 'View Publisher', 'example-plugin' ),
			'update_item'                => __( 'Update Publisher', 'example-plugin' ),
			'add_new_item'               => __( 'Add New Publisher', 'example-plugin' ),
			'new_item_name'              => __( 'New Publisher Name', 'example-plugin' ),
			'parent_item'                => __( 'Parent Publisher', 'example-plugin' ),
			'parent_item_colon'          => __( 'Parent Publisher:', 'example-plugin' ),
			'search_items'               => __( 'Search Publishers', 'example-plugin' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_in_quick_edit' => true,
			'query_var'         => true,
			'sort'              => true,
			'rewrite'           => [ 'slug' => 'Publisher' ],
		);

		register_taxonomy( 'Publisher', [ 'book' ], $args );

		unset( $args );
		unset( $labels );

		// Add 2nd taxonomy: Author
		$labels = array(
			'name'                       => _x( 'Authors', 'taxonomy general name', 'example-plugin' ),
			'singular_name'              => _x( 'Author', 'taxonomy singular name', 'example-plugin' ),
			'search_items'               => __( 'Search Authors', 'example-plugin' ),
			'popular_items'              => __( 'Popular Authors', 'example-plugin' ),
			'all_items'                  => __( 'All Authors', 'example-plugin' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Author', 'example-plugin' ),
			'update_item'                => __( 'Update Author', 'example-plugin' ),
			'add_new_item'               => __( 'Add New Author', 'example-plugin' ),
			'new_item_name'              => __( 'New Author Name', 'example-plugin' ),
			'separate_items_with_commas' => __( 'Separate Authors with commas', 'example-plugin' ),
			'add_or_remove_items'        => __( 'Add or remove Authors', 'example-plugin' ),
			'choose_from_most_used'      => __( 'Choose from the most used Authors', 'example-plugin' ),
			'not_found'                  => __( 'No Authors found.', 'example-plugin' ),
			'menu_name'                  => __( 'Authors', 'example-plugin' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_in_quick_edit' => true,
			'query_var'         => true,
			'sort'              => true,
			'update_count_callback' => '_update_post_term_count',
			'rewrite'               => ['slug' => 'Author'],
		);

		register_taxonomy( 'Author', 'book', $args );
	}


}

function configs() {
	return Configs::get();
}

configs();