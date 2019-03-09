<?php 

/**
 * @package wrm
 */
/*
Plugin Name: WordPress Role Management
Plugin URI: https://wrm.com/
Description: Manage roles admin, author, editor
Version: 1.0
Author: Sandipan
Author URI: 
Text Domain: wrm
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('WRM') ) :

/**
* 
*/
class WRM
{
    /**
     * WRM constructor.
     */
	function __construct()
	{
		
	}

    /**
     * @var string
     */
	var $version = '1.0';

    /**
     * Initialize basic vars
     */
	public function initialize()
	{
		// vars
		$version = $this->version;
		$basename = plugin_basename( __FILE__ );
		$path = plugin_dir_path( __FILE__ );
		$url = plugin_dir_url( __FILE__ );
		$slug = dirname($basename);

	}

    /**
     * Create WRM Table
     * @throws Exception
     */
	function createWRMTable() 
	{
		try {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            $table_name = $wpdb->prefix . "wrm";
            $this->wrmQuery($table_name, $charset_collate);

        } catch (Exception $e) {
            $this->wrmErrorHandle($e);
        }
	}

    /**
     * Query for WRM table
     * @param $table_name
     * @throws Exception
     */
	function wrmQuery($table_name, $charset_collate)
	{
		try {	
			$sql = "CREATE TABLE $table_name (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  name tinytext NOT NULL,
			  text text NOT NULL,
			  url varchar(55) DEFAULT '' NOT NULL,
			  PRIMARY KEY  (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		} catch (Exception $e) {
			$this->wrmErrorHandle($e);
		}
	}

    /**
     * Error handle
     * @param $e
     * @throws Exception
     */
	function wrmErrorHandle($e)
	{
		throw new Exception($e->getMessage());
	}

    /**
     * Activation and Deactivation Hook
     * Trigger Create WRM Table Function
     * Check if table already exists
     */

}

$wrm = new WRM;

endif;