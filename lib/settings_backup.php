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
        'leaflet-maps',
        'puleafletmaps_options_page',
        5
    );
    
    add_action( 'admin_init', 'register_puleafletmaps_settings' );  
}


function register_puleafletmaps_settings() {

  register_setting( 'leaflet_maps_settings','leaflet_maps_basemap');
  register_setting( 'leaflet_maps_settings','leaflet_maps_basemap_custom');
  register_setting( 'leaflet_maps_settings','leaflet_maps_overlay_image');
  register_setting( 'leaflet_maps_settings','leaflet_maps_overlay_coords_tl');
  register_setting( 'leaflet_maps_settings','leaflet_maps_overlay_coords_br');
  register_setting( 'leaflet_maps_settings','leaflet_maps_on_post');
  register_setting( 'leaflet_maps_settings','leaflet_maps_on_page');
  register_setting( 'leaflet_maps_settings','leaflet_maps_center');
  register_setting( 'leaflet_maps_settings','leaflet_maps_zoom');
  register_setting( 'leaflet_maps_settings','leaflet_maps_show');
}






/********************************
 * Prints out the settings page
 ********************************/
function puleafletmaps_options_page() {

	$basemaps = array(
	   "OpenStreetMap Streets" => "//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
	   "Stamen Toner" => "//{s}.tile.stamen.com/toner/{z}/{x}/{y}.png",
	   "Hillshading" => "//tiles.wmflabs.org/hillshading/{z}/{x}/{y}.png",
	   "None" => ""
	); 

////b.tile.stamen.com/terrain/{z}/{x}/{y}.png

  if ( !current_user_can( 'manage_options' ) )  {
 	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
 
  
  if(!$basemap = get_option('leaflet_maps_basemap')) {  $basemap = "//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"; }
  if(!$basemap_custom = get_option('leaflet_maps_basemap_custom')) {  $basemap_custom = ""; }
  if(!$overlay_image = get_option('leaflet_maps_overlay_image')) {  $overlay_image = ""; }
  if(!$overlay_coords_tl = get_option('leaflet_maps_overlay_coords_tl')) {  $overlay_coords_tl = ""; }
  if(!$overlay_coords_br = get_option('leaflet_maps_overlay_coords_br')) {  $overlay_coords_br = ""; } 
  if(!$on_post = get_option('leaflet_maps_on_post')) {  $on_post = 1; }
  if(!$on_page = get_option('leaflet_maps_on_page')) {  $on_page = 0; }
  echo $on_post;
  echo $on_page;
  
  if(!$center = get_option('leaflet_maps_center')) {  $center = "39.833333, -98.583333"; }
  if(!$zoom = get_option('leaflet_maps_zoom')) {  $zoom = "5"; }    
  if(!$show = get_option('leaflet_maps_show')) {  $show = 1; }
  
  print_r(get_option('leaflet_maps_postpage'));
 ?>
 
   <div class='wrap'>
   <h1>Leaflet Maps</h1>
   <form method="post" action="options.php"> 
   <?php 
     settings_fields( 'leaflet_maps_settings' ); 
     do_settings_sections( 'leaflet_maps_settings' );
   ?>

     <p><label for='basemap'>Base Map:</label><select name='leaflet_maps_basemap' id='basemap'> 
      <?php
      foreach($basemaps as $key=>$val) {
       if($val==$basemap) {  echo "<option value='{$val}' selected='selected'>{$key}</option>";  }
       else { echo "<option value='{$val}'>{$key}</option>"; }
       }
      ?>
     </select></p>
     
     <p><label for='basemap_custom'>Custom basemap:</label> <input class='regular-text' type='text' id='basemap_custom' name='leaflet_maps_basemap_custom' value='<?php echo $basemap_custom; ?>'/> (Must be an XYZ tileset)</p>
     
     <p><label for='overlay_image'>Overlay Image:</label> <input class='regular-text' type='text' id='overlay_image' name='leaflet_maps_overlay_image' value='<?php echo $overlay_image; ?>'/></p>
     
     <p><label for='overlay_coord_tl'>Overlay coordinates Top Left:</label> <input class='regular-text' type='text' id='overlay_coords_tl' name='leaflet_maps_overlay_coords_tl' value='<?php echo $overlay_coords_tl; ?>'/></p>
     
      <p><label for='overlay_coords_br'>Overlay coordinates Bottom Right:</label> <input class='regular-text' type='text' id='overlay_coords_br' name='leaflet_maps_overlay_coords_br' value='<?php echo $overlay_coords_br; ?>'/></p>
          
     <p><label>Allow maps on:</label> 
       <input type='checkbox' name='leaflet_maps_on_post' <?php if($on_post==1) { echo "checked='checked'";}?> value='1'/> Posts 
       <input type='checkbox' name='leaflet_maps_on_page' <?php if($on_page==1) { echo "checked='checked'";}?> value='1'/> Pages
     </p>

     <p>Default center point: <input type='text' name='leaflet_maps_center' value='<?php echo $center; ?>'/></p>
     
     <p>Default zoom: <input type='text' name='leaflet_maps_zoom' value='<?php echo $zoom; ?>'/></p>
     
     <p><input type='checkbox' name='leaflet_maps_show' <?php if($show==1) { echo "checked='checked'";}?> value='1'/> Show map on single blog post pages</p>
     
     <p class="submit">
	<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
	</p>
   </form>
 </div>
  <?php
}





