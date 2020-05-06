<?php
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../../../wp-config.php');

global $wpdb;

$row = $_POST['row'];
$rowperpage = 14;
$table_name = $wpdb->prefix . "schools";

    $results =  $wpdb->get_results("SELECT * FROM $table_name ORDER BY country_code limit ".$row.",".$rowperpage);  
    $html = '';
    foreach ($results as $key => $value) {
        $html .= '<div class="col-md-4 col-sm-12 post" style="cursor: pointer; margin-bottom: 10px;">';
        $html .= '<div class="card" >';
        $html .= '<img class="card-img-top target" style="width: 100%;height: 40vw;object-fit: cover;" src="'.$value->school_image.'" alt="Card image cap">';
        $html .= '<div class="card-body target" data_id="'.$value->id.'">';
        $html .= '<h5 class="card-title">'.$value->school_name.'</h5>';
        $table_name = $wpdb->prefix . "schools_rating";
        $res =  $wpdb->get_results("SELECT AVG(average_rating) AS average FROM ".$table_name." WHERE school_id =".$value->id);
        $average = round($res[0]->average);
        $rem_rat = 5-$average;
        for ($i=0; $i <$average ; $i++) {
            $html .= '<i class="fa fa-star" aria-hidden="true"></i>';
        } for ($i=0; $i <$rem_rat ; $i++) {
            $html .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
        }
        $html .= '<p class="card-text">Schulform: '.$value->type_of_school.'</p>';
        $html .= '<p class="card-text"><small class="text-muted">Adresse : '.$value->school_Address. ",".$value->country_code.'</small></p>';
        $html .= '</div>';
        $html .= '<form method="get" action="" class="bewertung_button" style="padding: 5px;">';
        $html .= '<input type="hidden" name="school_id" value="'.$value->id.'">';
        $html .= '<button id="ratings_lesen" type="submit" style="font-size: 16px; padding: 5px 20px;-webkit-border-radius: 4px;";>Bewertungen lesen</button>';
        $html .= '</form>';
        $html .= '</div>';
        $html .= '</div>';
}
echo $html;       

?>