<?php

namespace src;

use WP_List_Table;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class AdminDisplay extends WP_List_Table {

	private $Table;
	private $db;

	public function __construct() {
		$args = [
			'singular' => 'ISBN',
			'plural'   => 'ISBNs',
			'ajax'     => false
		];
		parent::__construct( $args );
		global $wpdb;
		$this->db    = $wpdb;
		$this->Table = $this->db->prefix . 'books_info';
	}

	/**
	 * Set Columns
	 */
	public function get_columns() {
		return [
			'ID' => 'ID',
			'post_id' =>'Post ID',
			'isbn'    =>'ISBN'
		];
	}

	/**
	 * Get Data And Modify to show
	 ***/
	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = [];
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = [ $columns, $hidden, $sortable ];

		/**
		 * Get Data
		 ***/
		$data = $this->getBooksInfoData();

		/**
		 *  Modify to show
		 ***/
		$per_page     = 10;
		$current_page = $this->get_pagenum();
		$total_items  = is_array( $data ) ? count( $data ) : 0;

		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page,
		] );

		$this->items = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
	}

	/**
	* Display TAble Content
	 **/
	public function column_default( $item, $column_name ) {
		return match ( $column_name ) {
			'ID', 'post_id', 'isbn' => $item[ $column_name ],
			default => print_r( $item, true ),
		};
	}

	/**
	 * Get Data From DB
	 */
	private function getBooksInfoData() {
		$query   = "SELECT * FROM {$this->Table} ORDER BY ID DESC";
		$results = $this->db->get_results( $query, );
		if ( $results ) {
			$data = [];

			foreach ( $results as $result ) {
				$data[] = [
					'ID'      => $result->ID,
					'post_id' => $result->post_id,
					'isbn'    => $result->isbn,
				];
			}

			return $data;
		}

		return [];
	}
}



