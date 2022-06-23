<?php 

class PULeaflet_Ajax {

    public function __construct() {
	add_action( 'wp_ajax_leaflet_markers', array( $this , 'ajax_leafletmaps_output' ) );
	add_action( 'wp_ajax_nopriv_leaflet_markers', array( $this , 'ajax_leafletmaps_output' ) );
	add_action( 'wp_ajax_leaflet_clear', array( $this , 'ajax_leafletmaps_clear' ) );
    }
    

    function ajax_leafletmaps_output() {

		$content = array();
		
		$args = array(
		'post_status' => 'publish',
		'numberposts' => -1
		);
		
		if(isset($_GET['postid'])) { $args['p'] = $_GET['postid']; }	
		
		if($posts = get_posts($args)) {
		  foreach($posts as $post) {
		    if($data = get_post_meta($post->ID,'_puleafletmap',true)) {
		    
		        

			$data = json_decode($data);
			$thumb = get_the_post_thumbnail_url($post->ID, 'thumbnail');
			if(isset($data->lat) && isset($data->lng)) {
			  if($data->show) {
			    $o = new StdClass();
			    $o->ID = $post->ID;		    
			    $o->post_title = $post->post_title;
			    $o->post_excerpt = get_the_excerpt($post->ID);
			    $o->thumbnail = $thumb;
			    $o->latitude = $data->lat;
			    $o->longitude = $data->lng;
			    $o->url = get_permalink($post->ID);
			    
			    // if the user has set a custom title for the popup in the editor screen
			    if(isset($data->title) && $data->title != '') { $o->post_title = $data->title;$o->post_excerpt = ""; }
			    
			    $content[] = $o;
		  	  }
			}
		   } // if
		 } // foreach
		} // end if

		header('Content-Type: application/json');
		echo json_encode($content);
		die();
	   wp_die();
    }
    
    
    
    function ajax_leafletmaps_clear() {

		if(isset($_GET['postid'])) { 
		   $postid = $_GET['postid'];
		   delete_post_meta($postid,'_puleafletmap');
		   $data = array('message'=>'success');
	   
		}
		else { 
		   $data = array('message'=>'failed to clear post meta');
		}
	   echo json_encode($data);
 	   die();			
	   wp_die();
    }    
	

}

new PULeaflet_Ajax();
 

