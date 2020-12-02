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
}


/********************************
 * Prints out the settings page
 ********************************/
function puleafletmaps_options_page() {
 ?>
 <div class='wrap'>
   <h1>Leaflet Maps</h1>
   <p>Base Map:</p>
   <p>Overlay:</p>
   <p>Allow maps on: <input type='checkbox' checked='checked'/> Posts <input type='checkbox'/> Pages</p>
   <p>Show map on single blog post pages:</p>
 </div>
  <?php
}
