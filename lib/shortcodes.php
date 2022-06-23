<?php 

/************************************
* Big map shortcode
************************************/


function puleafletmaps_insert_map( $atts ){

  wp_enqueue_script('leaflet-map-js');

  if(isset($atts['width'])) { $width = $atts['width']; } else { $width = '500px'; }
  if(isset($atts['height'])) { $height = $atts['height']; } else { $height = '350px'; }
  if(isset($atts['zoom'])) { $zoom = $atts['zoom']; } else { $zoom = '4'; }
  if(isset($atts['center'])) { 
    $center = $atts['center'];
    $coords = explode(',',$atts['center']);
   } else { 
    $center = 'auto';
    $coords = array(0,0);
   }
  
  $content = "<div id='LeafletMap' data-z='{$zoom}' data-center='{$center}' data-lat='{$coords[0]}'  data-lng='{$coords[1]}' style='width:{$width};height:{$height};background:grey;'></div>";
  return $content;
}
add_shortcode( 'bigmap', 'puleafletmaps_insert_map' );









