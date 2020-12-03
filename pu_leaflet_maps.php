<?php 
    /*
    Plugin Name: PU Leaflet Maps
    Plugin URI: http://www.princeton.edu
    Description: Add Leaflet maps to posts
    Author: Ben Johnston - benj@princeton.edu
    Version: 1.0
    */


/**
 * Main class
 */
class PULeaflet {

    public function __construct() {
        register_activation_hook( __FILE__ , array( $this, 'activate' ) );
        add_action( 'init', array( $this, 'init' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        add_filter( 'the_content', array( $this , 'content_filter' ) );
    }
    
    function activate() {
	// setting defaults   
	
	update_option( 'leaflet_maps_basemap', '//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png' );
	update_option( 'leaflet_maps_basemap_custom' , '' );
	update_option( 'leaflet_maps_overlays' , '' );
	update_option( 'leaflet_maps_on_post' , '1' );
	update_option( 'leaflet_maps_on_page' , '' );
	update_option( 'leaflet_maps_center' , '39.833333, -98.583333' );
	update_option( 'leaflet_maps_zoom' , '5' ); 
	update_option( 'leaflet_maps_show' , '1' );

    }
    
    function init() {
     	require_once('lib/metabox.php');
 	require_once('lib/settings.php');
	require_once('lib/shortcodes.php');
	require_once('lib/ajax.php');
    }
    
    function scripts() {
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
	  if($overlays = get_option('leaflet_maps_overlays')) { $data['overlays'] = $overlays; }
	  if($center = get_option('leaflet_maps_center')) { $data['center'] = $center; }
	  if($zoom = get_option('leaflet_maps_zoom')) { $data['zoom'] = $zoom; }
	 
	  wp_localize_script( 'leaflet-map-js', 'leafletvars', $data);
    }
    
    function admin_scripts() {
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
	      $puleafletmapdata = array();
	    }
	    wp_localize_script( 'puleaf', 'leafletvars', array( 'mapdata' => $puleafletmapdata ) );
	    }

	  }
    }
    
    
    
    
    function content_filter($content) {
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
    

}

new PULeaflet();












