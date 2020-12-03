<?php 

class PULeaflet_Ajax {

    public function __construct() {
	add_action( 'wp_ajax_leaflet_markers', array( $this , 'ajax_output' ) );
	add_action( 'wp_ajax_nopriv_leaflet_markers', array( $this , 'ajax_output' ) );
        
    }
    

    function ajax_output() {

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
			if(isset($data->lat) && isset($data->lng)) {
			  if($data->show) {
			    $o = new StdClass();
			    $o->ID = $post->ID;		    
			    $o->post_title = $post->post_title;
			    $o->post_excerpt = get_the_excerpt($post->ID);
			    $o->latitude = $data->lat;
			    $o->longitude = $data->lng;
			    $o->url = get_permalink($post->ID);
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
	

}

new PULeaflet_Ajax();
 

