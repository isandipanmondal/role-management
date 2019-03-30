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
            add_menu_page('My Page Title', 'WRM', 'manage_options', 'my-menu', array($this,'wrm_menu_page'));
            add_submenu_page('my-menu', 'Submenu Page Title', 'General', 'manage_options', 'my-menu' );
            add_submenu_page('my-menu', 'Submenu Page Title2', 'Settings', 'manage_options', 'settings', array($this,'wrm_sub_1_callback'));

        }

        /**
         * Display a custom menu page
         */
        function wrm_menu_page(){ ?>



            <!-- Font Awesome link -->
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

            <!-- Latest compiled and minified CSS -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

            <!-- Optional theme -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

            <!-- Latest compiled and minified JavaScript -->
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>



            <div class="wrap">
                <h1>WordPress Role Management <hr></h1>



                <?php

                global $wpdb;
                /* $latestresult = $wpdb->get_results(
                 "SELECT wp_users.*, wp_usermeta.meta_value as roles FROM wp_users LEFT JOIN wp_usermeta ON wp_users.ID = wp_usermeta.user_id WHERE wp_usermeta.meta_key = 'wp_capabilities'"
                 );*/

                $latestresult = $wpdb->get_results(
                    "SELECT wp_users.*, 

                            R.meta_value as roles, 
                            L.meta_value as last_name

                         FROM wp_users

                            LEFT JOIN wp_usermeta as R ON wp_users.ID = R.user_id   
                            LEFT JOIN wp_usermeta as L ON wp_users.ID = L.user_id 

                         WHERE 

                         R.meta_key = 'wp_capabilities' 
                         AND 
                         L.meta_key = 'last_name' "
                );


                /*echo "<pre>";
                print_r($latestresult);
                echo "</pre>";*/

                ?>


                <ul class="nav nav-tabs">

                    <?php $i=1;  foreach ($latestresult as $result => $value) {
                        $main_role = $latestresult[$result]->roles;
                        $var = array_keys(unserialize($main_role));
                        /*echo "<pre>";
                        print_r($var[0]);
                        echo "</pre>";*/
                        ?>
                        <li class="<?php echo ($i == 1) ? 'active' : '' ?>">
                            <a data-toggle="tab" href="#<?php echo $var[0]; ?>"><?php echo ucfirst($var[0]); ?></a>
                        </li>
                        <?php $i++; } ?>

                </ul>



                <div class="tab-content">
                    <div id="administrator" class="tab-pane fade in active">


                        <h3>Administrator</h3>

                        <div class="inner-table">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Setting</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php

                                foreach($latestresult as $result =>$value){




                                    $main_role = $latestresult[$result]->roles;

                                    $var = array_keys(unserialize($main_role));

                                    /* echo "<pre>";
                                     print_r($var);
                                     print_r($var[0]);
                                     echo "</pre>";*/

                                    //echo $main_role;

                                    if ($var[0] == 'administrator') { ?>

                                        <tr>
                                            <td><?php echo ucfirst($latestresult[$result]->user_nicename); ?></td>
                                            <td><?php echo $latestresult[$result]->last_name; ?></td>
                                            <td><?php echo $latestresult[$result]->user_email; ?></td>
                                            <td><?php echo ucfirst($var[0]); ?></td>
                                            <td> <center> <a href=""><i class="fas fa-edit"></i></a></center></td>
                                        </tr>

                                    <?php }
                                }

                                ?>



                                </tbody>
                            </table>
                        </div> <!-- //inner-table -->

                    </div>
                    <div id="author" class="tab-pane fade">
                        <h3>Author</h3>
                        <div class="inner-table">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Setting</th>
                                </tr>
                                </thead>
                                <tbody>


                                <?php

                                foreach($latestresult as $result =>$value){

                                    $main_role = $latestresult[$result]->roles;

                                    $var = array_keys(unserialize($main_role));

                                    // echo "<pre>";
                                    // print_r($var);
                                    // print_r($var[0]);
                                    // echo "</pre>";

                                    //echo $main_role;

                                    if ($var[0] == 'author') { ?>

                                        <tr>
                                            <td><?php echo ucfirst($latestresult[$result]->user_nicename) ; ?></td>
                                            <td><?php echo $latestresult[$result]->last_name; ?></td>
                                            <td><?php echo $latestresult[$result]->user_email; ?></td>
                                            <td><?php echo ucfirst($var[0]); ?></td>
                                            <td> <center> <a href=""><i class="fas fa-edit"></i></a></center></td>
                                        </tr>

                                    <?php }
                                }

                                ?>

                                </tbody>
                            </table>
                        </div> <!-- //inner-table -->
                    </div>
                    <div id="editor" class="tab-pane fade">
                        <h3>Editor</h3>
                        <div class="inner-table">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Setting</th>
                                </tr>
                                </thead>
                                <tbody>


                                <?php

                                foreach($latestresult as $result =>$value){

                                    $main_role = $latestresult[$result]->roles;

                                    $var = array_keys(unserialize($main_role));

                                    // echo "<pre>";
                                    // print_r($var);
                                    // print_r($var[0]);
                                    // echo "</pre>";

                                    //echo $main_role;

                                    if ($var[0] == 'editor') { ?>

                                        <tr>
                                            <td><?php echo ucfirst($latestresult[$result]->user_nicename); ?></td>
                                            <td><?php echo $latestresult[$result]->last_name; ?></td>
                                            <td><?php echo $latestresult[$result]->user_email; ?></td>
                                            <td><?php echo ucfirst($var[0]); ?></td>
                                            <td> <center> <a href=""><i class="fas fa-edit"></i></a></center></td>
                                        </tr>

                                    <?php }
                                }

                                ?>

                                </tbody>
                            </table>
                        </div> <!-- //inner-table -->
                    </div>
                </div>


            </div> <!-- // end wrap -->






        <?php }



        function wrm_sub_1_callback() { ?>

            <div class="wrap">
                <h1>Setting <hr></h1>
            </div>

        <?php }


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
         * Add main and sub menu for wrm / Done
         * Options are general(3 sections admin, author, editor), settings / Done
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