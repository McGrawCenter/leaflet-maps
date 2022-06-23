<?php 
    /*
    Plugin Name: PU Leaflet Maps
    Plugin URI: http://www.princeton.edu
    Description: Add Leaflet maps to posts
    Author: Ben Johnston - benj@princeton.edu
    Version: 1.0
    */


/**
 * Main Blicki class.
 */
class PULeaflet_Metabox {

    public function __construct() {
	/************************************
	* Create a metabox
	************************************/
        add_action( 'add_meta_boxes', array( $this , 'metabox' ) );
        add_action('save_post', array( $this , 'metabox_save' ) );
        
    }
    
    function metabox() {
	 add_meta_box('puleaf-map-editor',  esc_html__( 'Add a location', 'add-a-location' ), array( $this, 'metabox_content'),  array('post'), 'normal','default');
    }
    
    

    function metabox_content() {

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


    function metabox_save($post_id) {
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




    

}

new PULeaflet_Metabox();


