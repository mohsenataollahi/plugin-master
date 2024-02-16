<?php

namespace src;
use WP_List_Table;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class AdminDisplay {


	protected static array $instances = [];
	private $Table;
	private $db;
	public function __construct() {
		$args = [

		];
//		parent::__construct( $args );
		global $wpdb;
		$this->db    = $wpdb;
		$this->Table = $this->db->prefix . 'books_info';
		add_action('admin_menu', [$this,'registerMenu']);
	}

	public function registerMenu(): void
	{
		$pageTitle = __('Admin Display','example-plugin');
		$menuTitle = __('Admin Display','example-plugin');

		add_menu_page(

			$pageTitle,
			$menuTitle,
			'manage_options',
			'book-store',
			[$this,'MenuHandler'],
			'dashicons-smiley',
			5,
		);
	}

	public function MenuHandler(): void {
		// include template
		echo '<h1>Hello World!</h1>';
	}

	public static function getInstance() {
		$class = get_called_class();
		$args  = func_get_args();

		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new static( ...$args );
		}

		return self::$instances[ $class ];
	}


}

function adminDisplay() {
	return AdminDisplay::getInstance();
}
adminDisplay();











