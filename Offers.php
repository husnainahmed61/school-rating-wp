<?php
/*
Plugin Name:  Bewertungssystem GradeYourSchool
Description:  Stellt das System, welches zur Bewertung auf GradeYourSchool.at genutzt wird, zur verfügung.
Version:      3.0.0
Author:       Hasnain ahmed & OrangeFerdi
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('WPINC')) {
    die();
}

if (!defined('WPAC_PLUGIN_DIR')) {
    define('WPAC_PLUGIN_DIR', plugin_dir_url(__FILE__));
}

require plugin_dir_path(__FILE__) . 'inc/settings.php';


function wpac_plugin_scripts()
{
    wp_enqueue_style('wpac-style', WPAC_PLUGIN_DIR . 'assets/js/bootstrap.min.css');
    wp_enqueue_style('bootstrap3-style', WPAC_PLUGIN_DIR . 'assets/js/bootstrap3.min.css');
    wp_enqueue_style('datatable-style', WPAC_PLUGIN_DIR . 'assets/datatable/jquery.dataTables.min.css');
    wp_enqueue_style('vactorMap-style', WPAC_PLUGIN_DIR . 'assets/jquery-jvectormap/jquery-jvectormap-2.0.3.css');
    //wp_enqueue_style('wpac-style', WPAC_PLUGIN_DIR . 'assets/EasyAutocomplete/easy-autocomplete.css');
    wp_register_script( 'bootstrap3-script', WPAC_PLUGIN_DIR .'assets/js/bootstrap3.min.js', array( 'jquery' ) );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'bootstrap3-script' );
    wp_register_script( 'bootstrap-script', WPAC_PLUGIN_DIR .'assets/js/bootstrap.min.js', array( 'jquery' ) );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'bootstrap-script' );
    wp_register_script( 'jquery-script', WPAC_PLUGIN_DIR .'assets/js/jquery.min.js', array( 'jquery' ) );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'jquery-script' );
    wp_register_script( 'jquery-1-script', WPAC_PLUGIN_DIR .'assets/js/jquery-1.min.js', array( 'jquery' ) );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'jquery-1-script' );
    // Register the script like this for a plugin:
    
    wp_register_script( 'autocomplete-script', WPAC_PLUGIN_DIR .'assets/EasyAutocomplete/jquery.easy-autocomplete.js', array( 'jquery' ) );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'autocomplete-script' );
     wp_register_script( 'datatable-script', WPAC_PLUGIN_DIR .'assets/datatable/jquery.dataTables.min.js', array( 'jquery' ) );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'datatable-script' );

    wp_register_script( 'custom-script', WPAC_PLUGIN_DIR .'assets/js/main.js', array( 'jquery' ) );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'custom-script' );
    wp_register_script( 'vectorMap-script', WPAC_PLUGIN_DIR .'assets/jquery-jvectormap/jquery-jvectormap-2.0.3.min.js', array( 'jquery' ) );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'vectorMap-script' );
    wp_register_script( 'vectorMapAustria-script', WPAC_PLUGIN_DIR .'assets/jquery-jvectormap/jquery-jvectormap-at-mill.js', array( 'jquery' ) );
 
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'vectorMapAustria-script' );

}

add_action('wp_enqueue_scripts', 'wpac_plugin_scripts');
//creating table for offer
//require plugin_dir_path( __FILE__ ).'inc/db.php';
function wpac_offers_table()
{
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    $table_name      = $wpdb->prefix . "schools";
    $rating_table    = $wpdb->prefix . "schools_rating";
    //$fk =  $wpdb->prefix . "posts";
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      school_name varchar(255),
      school_Address varchar(255),
      country_code varchar(255),
      type_of_school varchar(255),
      school_image varchar(255),
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY  (id)
    ) $charset_collate";
    
    $sql2 = "CREATE TABLE IF NOT EXISTS $rating_table (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      school_id varchar(255),
      user_ip varchar(255),
      q1_rating varchar(255),
      q1_comment varchar(255),
      q2_rating varchar(255),
      q2_comment varchar(255),
      q3_rating varchar(255),
      q3_comment varchar(255),
      q4_rating varchar(255),
      q4_comment varchar(255),
      average_rating varchar(255),
      main_comment varchar(255),
      user_email varchar(255),
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY  (id)
    ) $charset_collate";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    dbDelta($sql2);

    file_put_contents( __DIR__ . '/my_loggg.txt', ob_get_contents() );
    
}

register_activation_hook(__FILE__, 'wpac_offers_table');

function wpac_pluginprefix_deactivation()
{
    //Offers_Title
    
    // clear the permalinks to remove post type's rules from the database
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'wpac_pluginprefix_deactivation');
?>