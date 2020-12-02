<?php 


/**
 * Adds link to settings admin menu
 */
 

 
 
 
 
add_action( 'admin_menu', 'puleafletmaps_custom_admin_menu' ); 

 
function puleafletmaps_custom_admin_menu() {
    add_options_page(
        'Leaflet Maps',
        'Leaflet Maps',
        'manage_options',
        'link-types',
        'puleafletmaps_options_page'
    );
    
    add_action( 'admin_init', 'register_puleafletmaps_settings' );  
}


function register_puleafletmaps_settings() {
	register_setting( 'leaflet_maps_settings', 'leaflet_maps_basemap' );
	register_setting( 'leaflet_maps_settings', 'leaflet_maps_overlay_image' );
	register_setting( 'leaflet_maps_settings', 'leaflet_maps_overlay_coords' );
	register_setting( 'leaflet_maps_settings', 'leaflet_maps_postpage' );
	register_setting( 'leaflet_maps_settings', 'leaflet_maps_show' );
}



/********************************
 * Prints out the settings page
 ********************************/
function puleafletmaps_options_page() {

	$basemaps = array(
	   "OpenStreetMaps Streets" => "//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
	   "Stamen Toner" => "//{s}.tile.stamen.com/toner/{z}/{x}/{y}.png",
	); 



  if ( !current_user_can( 'manage_options' ) )  {
 	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  
  if(!$basemap = get_option('leaflet_maps_basemap')) {  $basemap = ""; }
  if(!$overlay_image = get_option('leaflet_maps_overlay_image')) {  $overlay_image = ""; }
  if(!$overlay_coords = get_option('leaflet_maps_overlay_coords')) {  $overlay_coords = ""; }
  if(!$postpage = get_option('leaflet_maps_postpage')) {  $postpage = array('',''); }
  if(!$show = get_option('leaflet_maps_show')) {  $show = 0; }
 ?>
   <div class='wrap'>
   <h1>Leaflet Maps</h1>
   <form method="post" action="options.php"> 
   <?php 
     settings_fields( 'leaflet_maps_settings' ); 
     do_settings_sections( 'leaflet_maps_settings' );
   ?>
     <p><label for='basemap'>Base Map:</label>
      <input class='regular-text' type='text' id='basemap' name='leaflet_maps_basemap' value='<?php echo $basemap; ?>'/>
     </p>
     <p>//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png</p>
     <p>//{s}.tile.stamen.com/toner/{z}/{x}/{y}.png</p>
     <p><label for='overlay'>Overlay Image:</label> <input class='regular-text' type='text' id='overlay_image' name='leaflet_maps_overlay_image' value='<?php echo $overlay_image; ?>'/></p>
     <p><label for='overlay'>Overlay coordinates:</label> <input class='regular-text' type='text' id='overlay_coords' name='leaflet_maps_overlay_coords' value='<?php echo $overlay_coords; ?>'/></p>
          
     <p>Allow maps on: <input type='checkbox' name='leaflet_maps_postpage[]' <?php if($postpage[0]==1) { echo "checked='checked'";}?> value='1'/> Posts <input type='checkbox'  name='leaflet_maps_postpage[]' <?php if($postpage[0]==1) { echo "checked='checked'";}?> value='1'/> Pages</p>
     <p><input type='checkbox' name='leaflet_maps_show' <?php if($show==1) { echo "checked='checked'";}?> value='1'/> Show map on single blog post pages</p>
     <p class="submit">
	<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
	</p>
   </form>
 </div>
  <?php
}





