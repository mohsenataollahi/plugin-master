<?php

namespace src;
use WP_List_Table;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class AdminDisplay extends WP_List_Table{


	protected static array $instances = [];
	private $Table;
	private $db;
	public function __construct() {
		$args = [
			'singular'  => 'ISBN',
			'plural'    => 'ISBNs',
			'ajax'      => false
		];
		parent::__construct( $args );
		global $wpdb;
		$this->db    = $wpdb;
		$this->Table = $this->db->prefix . 'books_info';
		add_action('admin_menu', [$this,'registerMenu']);
	}

	/**
	 * Set Columns
	 */
	public function get_columns() {
		return [
			'cb'      => '<input type="checkbox" />',
			'post_id' => __('Post ID', 'example-plugin'),
			'isbn'    => __('ISBN', 'example-plugin'),
		];
	}
	/**
	* Get Data And Modify to show
	 ***/
	public function prepare_items() {
		$columns = $this->get_columns();
		$hidden = [];
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = [$columns, $hidden, $sortable];

		/**
		 * Get Data 
		 ***/
		$data = $this->getBooksInfoData();

		/**
		 *  Modify to show
		 ***/
		$per_page = 10;
		$current_page = $this->get_pagenum();
		$total_items = count($data);

		$this->set_pagination_args([
			'total_items' => $total_items,
			'per_page'    => $per_page,
		]);

		$this->items = array_slice($data, (($current_page - 1) * $per_page), $per_page);
	}

	/**
	 * Get Data From DB
	 */

	private function getBooksInfoData() {
		$query = "SELECT * FROM {$this->Table} ORDER BY ID DESC";
		$stmt = $this->db->get_results($query,'OBJECT');
		if ($stmt){
			return $stmt;
		}
		return false;
	}

	/**
	 * Register Menu ToShow Table
	 */
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











