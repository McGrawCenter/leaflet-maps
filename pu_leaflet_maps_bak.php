<?php 
    /*
    Plugin Name: PU Leaflet Maps
    Plugin URI: http://www.princeton.edu
    Description: Add Leaflet maps to posts
    Author: Ben Johnston - benj@princeton.edu
    Version: 1.0
    */


require_once('lib/settings.php');
require_once('lib/shortcodes.php');
require_once('lib/ajax.php');



/**************** REGISTER AND ENQUEUE SCRIPTS AND CSS ***************/

function puleaf_scripts()
{
  global $post;

  wp_register_script('leaflet-js', plugins_url('/js/leaflet.js', __FILE__), array('jquery'),'1.1', true);
  wp_enqueue_script('leaflet-js');

  wp_register_style('leaflet-css', plugins_url('css/leaflet.css',__FILE__ ));
  wp_enqueue_style('leaflet-css');
  
  wp_register_style('mystyle', plugins_url('css/style.css',__FILE__ ));
  wp_enqueue_style('mystyle');  

  // this will be enqueue'd in the shortcode
  global $post;
  wp_register_script('leaflet-map-js', plugins_url('/js/map.js', __FILE__), array('jquery'),'1.1', true);
  
  $data = array(
    'postid' => $post->ID,
    'ajaxurl' => admin_url( 'admin-ajax.php' )
  );
  
  if($basemap = get_option('leaflet_maps_basemap')) { $data['basemap'] = $basemap; }
  if($basemap_custom = get_option('leaflet_maps_basemap_custom')) { $data['basemap_custom'] = $basemap_custom; }
  if($overlay_image = get_option('leaflet_maps_overlay_image')) { $data['overlay_image'] = $overlay_image; }
  if($overlay_tl = get_option('leaflet_maps_overlay_coords_tl')) { $data['overlay_tl'] = $overlay_tl; }
  if($overlay_br = get_option('leaflet_maps_overlay_coords_br')) { $data['overlay_br'] = $overlay_br; }
  if($center = get_option('leaflet_maps_center')) { $data['center'] = $center; }
  if($zoom = get_option('leaflet_maps_zoom')) { $data['zoom'] = $zoom; }
 
  wp_localize_script( 'leaflet-map-js', 'leafletvars', $data);

}
add_action( 'wp_enqueue_scripts', 'puleaf_scripts' );







function puleaf_admin_scripts()
{
  global $post;

  wp_register_script('leaflet-js', plugins_url('/js/leaflet.js', __FILE__), array('jquery'),'1.1', true);
  wp_enqueue_script('leaflet-js');

  wp_register_style('leaflet-css', plugins_url('css/leaflet.css',__FILE__ ));
  wp_enqueue_style('leaflet-css');

  wp_register_style('puleaf-css', plugins_url('css/style.css',__FILE__ ));
  wp_enqueue_style('puleaf-css');
  

  $screen = get_current_screen();

  if( $screen->post_type == 'post'){
  
    wp_register_script('puleaf', plugins_url('/js/admin.js', __FILE__), array('jquery'),'1.1', true);
    wp_enqueue_script('puleaf');

    global $post;
    if(isset($post->ID)) {
    if(!$puleafletmapdata =  json_decode(get_post_meta($post->ID, '_puleafletmap', true))) {
      $puleafletmapdata = defaultMapObj();
    }
    wp_localize_script( 'puleaf', 'leafletvars', array( 'mapdata' => $puleafletmapdata ) );
    }

  }
}

add_action( 'admin_enqueue_scripts', 'puleaf_admin_scripts' ); 









/************************************
* Initial, default map object
************************************/
function defaultMapObj() {
  $data = new StdClass();
  $data->active = 0;
  $data->lat = "0";
  $data->lng = "0";  
  $data->zoom = 6;
  $data->title = "";
  $data->show = 1;
  return $data;
}






/************************************
* Create a metabox
************************************/
function puleaf_meta_boxes() {
  add_meta_box('puleaf-map-editor',  esc_html__( 'Add a location', 'add-a-location' ), 'puleaf_map_editor_metabox',  array('post'), 'normal','default');
}
add_action( 'add_meta_boxes', 'puleaf_meta_boxes' );




/************************************
* Callback generating the html of the metabox
************************************/
function puleaf_map_editor_metabox() {

global $post;


if($data = get_post_meta($post->ID, '_puleafletmap', true)) {
  $data = json_decode($data);
}
else {
  $data = new StdClass();
  $data->active = 0;
  $data->lat = "";
  $data->lng = "";
  $data->zoom = 6;
  $data->title = "";
  $data->show = 0;
}

?>


   <div id="MapLocation" style='width:100%; height:350px;background:grey;'></div>
   <div id="PU-Leaflet-WP-Form">
      
      <input type="hidden" id="Active" name="Location.Active" value='<?php echo $data->active; ?>'  />
      <input type='hidden' id="Zoom" name='Location.ZoomLevel' value='<?php echo $data->zoom; ?>' />
      
      <label for="Latitude">Latitude:</label><input type="text" class='coordinates' id="Latitude" placeholder="Latitude" name="Location.Latitude" value='<?php echo $data->lat; ?>'  />
      <label for="Longitude">Longitude:</label><input type="text" class='coordinates' id="Longitude" placeholder="Longitude" name="Location.Longitude" value='<?php echo $data->lng; ?>'  /><br />
<?php
  if(isset($data->show) && $data->show == 1) { $showchecked = "checked='checked'"; } else { $showchecked = ""; }
?>
    <input type='checkbox' name='Location.Show' id='Show' <?php echo $showchecked; ?> value='1'/> <label for="Show">Show on big map</label><br />

    <label for="markertitle">Marker title (optional):</label><input type='text' id="markertitle" name='Location.Title' value='<?php echo $data->title; ?>' /> &nbsp;&nbsp;
   <input type='button' class='puleaf-clear' value='Clear Location'/>
   <input type='button' class='puleaf-center' value='Center Map'/>

   </div>
<?php
}




/************************************
* Save the metabox
************************************/
function puleaf_save_postdata($post_id)
{

  if($_POST && $_POST['Location_Active'] == '1') {
    $post_id = $_POST['ID'];
    if ( array_key_exists('Location_Latitude', $_POST) && array_key_exists('Location_Longitude', $_POST) ) {
    
        $puleaf_info = array(
                "active"=>$_POST['Location_Active'],
        	"lat"=>$_POST['Location_Latitude'],
        	"lng"=>$_POST['Location_Longitude'],
        	"zoom"=>$_POST['Location_ZoomLevel'],
        	"title"=>$_POST['Location_Title'],
        	"show"=>$_POST['Location_Show'],
        );

        update_post_meta( $post_id, '_puleafletmap', json_encode($puleaf_info) );

    }
  }
}

add_action('save_post', 'puleaf_save_postdata');






/************************************
* Show map on post
************************************/
function puleafet_content_filter($content) {

  global $post;
  $digits = 5;
  $rand = rand(pow(10, $digits-1), pow(10, $digits)-1);

  if ($data = json_decode(get_post_meta($post->ID,'_puleafletmap', true))) {
    wp_enqueue_script('leaflet-map-js');
    $html = "<div id='LeafletMap' data-id='{$post->ID}' data-z='{$data->zoom}' data-lat='{$data->lat}' data-lng='{$data->lng}' style='width:100%; height:350px;background:grey;'></div>";    
    return $html.$content;
  }
  else {return $content; }
}

add_filter( 'the_content', 'puleafet_content_filter' );










