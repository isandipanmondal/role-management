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

            /*
             *
             */

            /** Step 2 (from text above). */
            add_action( 'admin_menu', array($this, 'wpdocs_register_my_custom_menu_page') );


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
                $table_name = $wpdb->prefix . "wrm";

                if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {

                    $charset_collate = $wpdb->get_charset_collate();
                    $this->wrmQuery($table_name, $charset_collate);

                } else {

                    //echo "The table name is exists!";
                    //$this->activation_notice();
                    add_action( 'admin_notices', array($this, 'activation_notice'));

                }

            } catch (Exception $e) {
                $this->wrmErrorHandle($e);
            }
        }

        function activation_notice(){
            ?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e( 'WRM table already exists!'); ?></p>
            </div>
            <?php
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
         * Remove database table
         */

        function removeWRMtable ()
        {
            /*
             * Making WPDB as global
             * to access database information.
             */
            global $wpdb;

            /*
             * @var $table_name
             * name of table to be dropped
             * prefixed with $wpdb->prefix from the database
             */
            $table_name = $wpdb->prefix . 'wrm';

            // drop the table from the database.
            $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
        }



        /**
         * New admin menu
         */
        /**
         * Register a custom menu page.
         */
        function wpdocs_register_my_custom_menu_page(){
            add_menu_page(
                __( 'Custom Menu Title', 'textdomain' ),
                'WRM',
                'manage_options',
                'wrm',
                array($this,'wrm_menu_page'),
                'dashicons-groups',
                6
            );
        }


        /**
         * Display a custom menu page
         */
        function wrm_menu_page(){
            echo "Hello world";
        }



        /**
         * Activation hook
         */

        function active()
        {
            $this->createWRMTable();
            flush_rewrite_rules();
        }

        /**
         * Deactivation hook
         */

        function deactivate()
        {
           // $this->removeWRMtable();
            flush_rewrite_rules();
        }

        /**
         * Activation and Deactivation Hook / Done
         * Trigger Create WRM Table Function / Done
         * Check if table already exists / Done
         * Add main and sub menu for wrm
         * Options are general(3 sections admin, author, editor), settings
         */

    }

    $wrm = new WRM;

endif;

/**
 * This is activation hook
 */

register_activation_hook( __FILE__, array( $wrm, 'active' ) );

/**
 * This is deactivation hook
 */
register_deactivation_hook( __FILE__, array( $wrm, 'deactivate') );