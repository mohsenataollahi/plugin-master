<?php
/**
 * Plugin Name:     Example Plugin
 * Plugin URI:      https://www.veronalabs.com
 * Plugin Prefix:   EXAMPLE_PLUGIN
 * Description:     Example WordPress Plugin Based on Rabbit Framework!
 * Author:          VeronaLabs
 * Author URI:      https://veronalabs.com
 * Text Domain:     example-plugin
 * Domain Path:     /languages
 * Version:         1.0
 */

use Rabbit\Application;
use Rabbit\Redirects\RedirectServiceProvider;
use Rabbit\Database\DatabaseServiceProvider;
use Rabbit\Logger\LoggerServiceProvider;
use Rabbit\Plugin;
use Rabbit\Redirects\AdminNotice;
use Rabbit\Templates\TemplatesServiceProvider;
use Rabbit\Utils\Singleton;
use League\Container\Container;
use src\ConfigsBookStore;

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * Class ExamplePluginInit
 * @package ExamplePluginInit
 */
class ExamplePluginInit extends Singleton {
	/**
	 * @var Container
	 */
	private $application;

	/**
	 * ExamplePluginInit constructor.
	 */
	public function __construct() {
		$this->application = Application::get()->loadPlugin( __DIR__, __FILE__, 'config' );

		$this->init();
	}

	public function init() {
		try {

			/**
			 * Load service providers
			 */
			$this->application->addServiceProvider( RedirectServiceProvider::class );
			$this->application->addServiceProvider( DatabaseServiceProvider::class );
			$this->application->addServiceProvider( TemplatesServiceProvider::class );
			$this->application->addServiceProvider( LoggerServiceProvider::class );
			// Load your own service providers here...


			/**
			 * Activation hooks
			 */
			$this->application->onActivation( function () {
				// Create tables or something else

				/**
				 * Create Table books_info in DB.
				 */
				global $wpdb;
				$db           = $wpdb;
				$Table        = $db->prefix . 'books_info';
				$table_exists = $db->get_var( "SHOW TABLES LIKE '$Table'" ) === $Table;
				if ( ! $table_exists ) {
					$charset_collate = $db->get_charset_collate();
					$sql             = "CREATE TABLE {$Table} ( ID mediumint(9) NOT NULL AUTO_INCREMENT, post_id mediumint(9) NOT NULL, isbn varchar(255) NOT NULL, PRIMARY KEY  (ID) ) $charset_collate;";
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					dbDelta( $sql );
				}
			} );

			/**
			 * Deactivation hooks
			 */
			$this->application->onDeactivation( function () {
				// Clear events, cache or something else

			} );

			$this->application->boot( function ( Plugin $plugin ) {
				$plugin->loadPluginTextDomain();

				// load template
				$this->application->template( 'plugin-template.php', [ 'foo' => 'bar' ] );

				/// load bookstore main files
				$plugin->includes( __DIR__ . '/src' );

			} );

		} catch ( Exception $e ) {
			/**
			 * Print the exception message to admin notice area
			 */
			add_action( 'admin_notices', function () use ( $e ) {
				AdminNotice::permanent( [ 'type' => 'error', 'message' => $e->getMessage() ] );
			} );

			/**
			 * Log the exception to file
			 */
			add_action( 'init', function () use ( $e ) {
				if ( $this->application->has( 'logger' ) ) {
					$this->application->get( 'logger' )->warning( $e->getMessage() );
				}
			} );
		}
	}

	/**
	 * @return Container
	 */
	public function getApplication() {
		return $this->application;
	}
}

/**
 * Returns the main instance of ExamplePluginInit.
 *
 * @return ExamplePluginInit
 */
function examplePlugin() {
	return ExamplePluginInit::get();
}

examplePlugin();