<?php 

/************************************
* Big map shortcode
************************************/


function puleaf_insert_map( $atts ){

  wp_enqueue_script('leaflet-map-js');

  if(isset($atts['width'])) { $width = $atts['width']; } else { $width = '500px'; }
  if(isset($atts['height'])) { $height = $atts['height']; } else { $height = '350px'; }
  if(isset($atts['zoom'])) { $zoom = $atts['zoom']; } else { $zoom = '4'; }
  if(isset($atts['center'])) { $center = $atts['center']; } else { $center = '[0,0]'; }
  
  $content = "<div id='LeafletMap' style='width:{$width}; height:{$height};background:grey;'></div>";
  return $content;
}
add_shortcode( 'bigmap', 'puleaf_insert_map' );









