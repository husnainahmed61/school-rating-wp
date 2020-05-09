<?php
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../../../wp-config.php');

function wpac_settings_page_html()
{
?>
<?php 
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../../../wp-config.php');
	
//------------------------------------------------------------------- SHOW TABLE WITH RATINGS IN BACKEND ------------------------------------------------------------------- 

    global $wpdb;
    $schools_rating = $wpdb->prefix . "schools_rating";
    $schools = $wpdb->prefix . "schools";

    //print_r($wpdb->insert($tablename, $data)); exit();
    //$res = $wpdb->get_results( "SELECT * FROM ".$table_name."", OBJECT );
    $all_results = $wpdb->get_results(' 
    SELECT schools_rating.*,schools.school_name
    FROM '.$wpdb->prefix.'schools_rating AS schools_rating
    LEFT JOIN '. $wpdb->prefix.'schools AS schools
	ON schools_rating.school_id =  schools.id ORDER BY schools_rating.id ASC');

    $all_schools = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'schools');
    $all_words = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'school_res_words');

    $arr_word = [];
    foreach ($all_words as $key => $words) {
        array_push($arr_word, $words->res_words);
    }

if(isset($_POST['delete_rating']))
{
    $rating_id = $_POST['delete_rating'];
    $table = $wpdb->prefix . "schools_rating";
    $wpdb->delete( $table, array( 'id' => $rating_id ) );

}


    // echo "<pre>";
    // print_r($all_results);
    // exit();
    

?>  
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

<h1 style="position:center;">GradeYourSchool | Alle Bewertungen</h1>
<h6>Suchfeld Shortcode -> [my_form_shortcode]<br>Schulen Liste Shortcode -> [my_all_schools]</h6>
            <table id="example" class="display example" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Schulname</th>
                <th>Mail</th>
                <th>Lehrerkollegium</th>
                <th>&#9733;</th>
                <th>Ausstattung/Modernheit</th>
                <th>&#9733;</th>
                <th>Freizeitaktivitäten/Angebot:</th>
                <th>&#9733;</th>
                <th>Cafetaria/Nahrungsangebot</th>
                <th>&#9733;</th>
                <th>Gesamtwertung</th>
                <th>Ø &#9733;</th>
                <th>Datum</th>
                <th>Aktion</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            foreach ($all_results as $key => $value) { ?>
           
            <tr <?php if (in_array(strtolower($value->q1_comment), $arr_word) ||
                in_array(strtolower($value->q2_comment), $arr_word) ||
                in_array(strtolower($value->q3_comment), $arr_word) ||
                in_array(strtolower($value->q4_comment), $arr_word) ||
                in_array(strtolower($value->main_comment), $arr_word)
            ) { echo "style='background-color:#E57373;'";}  ?>>

                <th><?=$i?></th>
                <td><?=$value->school_name?></td>
                <td><?=$value->user_email?></td>
                <td ><?=$value->q1_comment?></td>
                <td><?=$value->q1_rating?></td>
                <td ><?=$value->q2_comment?></td>
                <td><?=$value->q2_rating?></td>
                <td ><?=$value->q3_comment?></td>
                <td><?=$value->q3_rating?></td>
                <td ><?=$value->q4_comment?></td>
                <td><?=$value->q4_rating?></td>
                <td ><?=$value->main_comment?></td>
                <td><?=$value->average_rating?></td>
                <td><?=$value->created_at?></td>
                <td><form method="post" action="">
                    <input type="hidden" name="delete_rating" value="<?=$value->id?>">
                    <button type="submit" value="Delete">Delete</button>
                </form></td>
            </tr>
            <?php $i++;  } ?>
        </tbody>
        <tfoot>
            <tr>
                <tr>
                <th>#</th>
                <th>Schulname</th>
                <th>Mail</th>
                <th>Lehrerkollegium</th>
                <th>&#9733;</th>
                <th>Ausstattung/Modernheit</th>
                <th>&#9733;</th>
                <th>Freizeitaktivitäten/Angebot:</th>
                <th>&#9733;</th>
                <th>Cafetaria/Nahrungsangebot</th>
                <th>&#9733;</th>
                <th>Gesamtwertung</th>
                <th>Ø &#9733;</th>
                <th>Datum</th>
                <th>Aktion</th>
            </tr>
            </tr>
        </tfoot>
    </table>
    <hr>
    
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('.example').DataTable({
				"order": [ 0, 'desc' ]
            });
        } );
    </script>
<?php
}
function wpac_register_menu_page()
{
    add_menu_page('Bewertungen', 'Alle Bewertungen', 'manage_options', 'offers-settings', 'wpac_settings_page_html', 'dashicons-megaphone', 2);
}

add_action('admin_menu', 'wpac_register_menu_page');





//------------------------------------------------------------------- SUB MENU PAGE ------------------------------------------------------------------- 

//---------- ADDING A SUB MENU ----------
function wpac_addNew_page_html()
{
    if (!is_admin())
    {
        return;
    }
?>
       <div class="wrap">
            <h1><?=esc_html(get_admin_page_title()); ?></h1>
            <h3><center><label id="successmessage"></label></center></h3>
            <form method="post" action="<?=WPAC_PLUGIN_DIR . 'inc/option.php'?>" id="myform" enctype="multipart/form-data">
<?php
		//------------------------------------------------------------------- ADD SCHOOLS ------------------------------------------------------------------- 

    settings_fields('Add-new');
    do_settings_sections('Add-new');
    //submit_button( 'Save' );
?>
            <input type="submit" value="Speichern">
            </form>
            </div>
            <script type="text/javascript">
                
function save_form_data(Offers_Title,offer_feature_value,where_location,service_type){
        jQuery("label#successmessage").text('');
        var submit = 'submit';
        jQuery.ajax({
            type : "post",
            url : "<?=WPAC_PLUGIN_DIR . 'inc/option.php' ?>",
            data : {
                    Offers_Title : Offers_Title,
                    offer_feature_value : offer_feature_value,
                    where_location : where_location,
                    service_type : service_type,
                    submit : submit
                  } ,
            success: function(data) {
                successmessage = 'Data was succesfully captured';
                jQuery("label#successmessage").text(successmessage);
                jQuery("label#successmessage").css("color", "green");

            },
            error: function(data) {
                successmessage = 'Error';
                jQuery("label#successmessage").text(successmessage);
                jQuery("label#successmessage").css("color", "red");
            },
        });
}
            </script>
<?php
}
function wpac_allSchools_page_html()
{
    if (!is_admin())
    {
        return;
    }
    require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../../../wp-config.php');

    global $wpdb;
    $schools_rating = $wpdb->prefix . "schools_rating";
    $schools = $wpdb->prefix . "schools";

    $all_schools = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'schools');
    if(isset($_POST['delete_school']))
    {
        $rating_id = $_POST['delete_school'];
        $table = $wpdb->prefix . "schools";
        $wpdb->delete( $table, array( 'id' => $rating_id ) );

    }
//------------------------------------------------------------------- TABLE WITH ALL SCHOOLS ------------------------------------------------------------------- 
?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script><br>
<table id="example" class="display example" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Schule</th>
                <th>Region</th>
                <th>Adresse</th>
                <th>PLZ Ort</th>
                <th>Schulform</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            foreach ($all_schools as $key => $value) { ?>
           
            <tr>
                <th><?=$i?></th>
                <td><?=$value->school_name?></td>
                <td><?=$value->region;?></td>
                <td><?=$value->school_Address?></td>
                <td><?=$value->country_code?></td>
                <td><?=$value->type_of_school?></td>
                
                <td><form method="post" action="">
                    <input type="hidden" name="delete_school" value="<?=$value->id?>">
                    <button type="submit" value="Delete">Delete</button>
                </form></td>
            </tr>
            <?php $i++;  } ?>
        </tbody>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Schule</th>
                <th>Region</th>
                <th>Adresse</th>
                <th>PLZ Ort</th>
                <th>Schulform</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
        <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('.example').DataTable();
        } );
    </script>
<?php
}
//------------------------------------------------------------------- SUB MENU PAGE2 ------------------------------------------------------------------- 
//---------- ADD RESTRICTED WORD PAGE ----------
function wpac_restrictedWords_page_html()
{
    if (!is_admin())
    {
        return;
    }
    require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../../../wp-config.php');

    global $wpdb;

    $all_words = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'school_res_words');
    if(isset($_POST['delete_word']))
    {
        $rating_id = $_POST['delete_word'];
        $table = $wpdb->prefix . "school_res_words";
        $wpdb->delete( $table, array( 'id' => $rating_id ) );

    }
//------------------------------------------------------------------- ADD RESTRICTED WORDS AND SHOW IN TABLE ------------------------------------------------------------------- 

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js" integrity="sha384-FzT3vTVGXqf7wRfy8k4BiyzvbNfeYjK+frTVqZeNDFl8woCbF0CYG6g2fMEFFo/i" crossorigin="anonymous"></script>

            <h3><center><label id="successmessage2"></label></center></h3>
            <form method="post" action="<?=WPAC_PLUGIN_DIR . 'inc/res_word.php'?>" id="myform2">
            <input type="text" name="res_word" required placeholder="Restricted Word">    
            <input type="submit" value="save">
            </form>
            </div>
            <script type="text/javascript">
$(function() {
       // bind 'myForm' and provide a simple callback function
       $('#myform2').ajaxForm(function() {
           alert("Word Added succesfully!");
           setTimeout(function () {
   window.location.href = '<?=admin_url()."admin.php?page=Restricted-words"?>'; //will redirect to your blog page (an ex: blog.html)
        }, 1000);
       });
     });

            </script>

<table id="example" class="display example" >
        <thead>
            <tr>
                <th>#</th>
                <th>Word</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            foreach ($all_words as $key => $value) { ?>
           
            <tr>
                <th><?=$i?></th>
                <td><?=$value->res_words?></td>
                
                <td><form method="post" action="">
                    <input type="hidden" name="delete_word" value="<?=$value->id?>">
                    <button type="submit" value="Delete">Delete</button>
                </form></td>
            </tr>
            <?php $i++;  } ?>
        </tbody>
    </table>
        
<?php
}

function wpac_register_submenu_page()
{
    add_submenu_page('offers-settings', 'Alle Schulen', 'Alle Schulen', 'manage_options', 'All-Schools', 'wpac_allSchools_page_html');
    add_submenu_page('offers-settings', 'Schule hinzufügen', 'Schule hinzufügen', 'manage_options', 'Add-new', 'wpac_addNew_page_html');
    add_submenu_page('offers-settings', 'Blacklist', 'Blacklist', 'manage_options', 'Restricted-words', 'wpac_restrictedWords_page_html');


}

add_action('admin_menu', 'wpac_register_submenu_page');


function wpac_plugin_settings()
{
    register_setting('Add-new', 'Offers_Title', $args = array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => NULL
    ));

    add_settings_section('wpac_form_Settings', 'Add New School', 'wpac_setting_section_cb', 'Add-new');

    add_settings_field('offer_name', 'Name: ', 'wpac_setting_field_name_cb', 'Add-new', 'wpac_form_Settings');

    add_settings_field('offer_title', 'Adresse: ', 'wpac_setting_field_title_cb', 'Add-new', 'wpac_form_Settings');


    add_settings_field('offer_feature_value', 'PLZ & Ort:', 'wpac_setting_field_offer_feature_cb', 'Add-new', 'wpac_form_Settings');

    add_settings_field('where_location', 'Schulform: ', 'wpac_setting_field_where_cb', 'Add-new', 'wpac_form_Settings');
    add_settings_field('image', 'Bild hochladen: ', 'wpac_setting_field_Image_cb', 'Add-new', 'wpac_form_Settings');
    //add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array )
    
}

add_action('admin_init', 'wpac_plugin_settings');

function wpac_setting_section_cb()
{

}

function wpac_setting_field_name_cb($value = '')
{

?>
    <input type="text" name="name" required="true" placeholder="BORG Feldbach">
<?php
}

function wpac_setting_field_title_cb($value = '')
{

?>
       <input type="text" name="school_Address" required="true" placeholder="Pfarrgasse 6">
<?php
}

function wpac_setting_field_offer_feature_cb($value = '')
{
?>
          <input type="text" name="country_code" required="true" placeholder="8330 Feldbach">
<?php
}

function wpac_setting_field_where_cb($value = '')
{
?>
       <input type="text" name="type_of_school" required="true" placeholder="Allgemein bildende höhere Schule (AHS)">
<?php
}
function wpac_setting_field_Image_cb($value = '')
{
?>
       <input type="file" name="file" accept="image/*">
<?php
}


function caption_shortcode( $atts, $content = null ) {
  return ;
}
add_shortcode( 'caption', 'caption_shortcode' );

function my_form_shortcode() {
    //print_r(dirname( __FILE__ ));
    include dirname( __FILE__ ) . '/my_template.php';
} // function my_form_shortcode
add_shortcode( 'my_form_shortcode', 'my_form_shortcode' );

function all_schools() {
    //print_r(dirname( __FILE__ ));
    include dirname( __FILE__ ) . '/all_schools.php';
} // function my_form_shortcode
add_shortcode( 'my_all_schools', 'all_schools' );

?>