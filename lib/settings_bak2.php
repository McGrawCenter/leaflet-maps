<?php
/**
 * Class for registering a new settings page under Settings.
 */
class LeafletMaps_Options_Page {
 
    /**
     * Constructor.
     */
    function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ) );
    }
 
    /**
     * Registers a new settings page under Settings.
     */
    function admin_menu() {
    
        add_options_page(
            __( 'Leaflet Maps', 'textdomain' ),
            __( 'Leaflet Maps', 'textdomain' ),
            'manage_options',
            'leaflet_map',
            array(
                $this,
                'leaflet_maps_settings_page'
            )
        );
    }
 
 
     /**
     * Register other stuff.
     */
    function admin_init() {

	register_setting(
		'leaflet_map',                 // settings page
		'leaflet_map_options',          // option name
		array( $this, 'leaflet_map_validate')  // validation callback
	);
	
	add_settings_field(
		'leaflet_map_basemap',      // id
		'Basemap',              // setting title
		array( $this, 'basemap_display'),    // display callback
		'leaflet_map',                 // settings page
		'default'                  // settings section
	);
    }
 
 
    /**
     * Validate
     */
    function leaflet_map_validate($input) {
      return $input;
    }
    
    
    /**
     * Validate
     */
    function basemap_display() {
	?>
	<textarea style='width:100%;height:300px;' id='iiif_manifests' name='iiifmedia_options[iiif_manifests]'><?php echo esc_attr( $value ); ?></textarea>
	<?php
    }    
 
 
    /**
     * Settings page display callback.
     */
    function leaflet_maps_settings_page() {
        ?>
        
   <div class='wrap'>
   <h1>Leaflet Maps</h1>
   <form method="post" action="options.php"> 
<input type='text' id='iiif_manifests' name='iiifmedia_options[iiif_manifests]'><?php echo esc_attr( $value ); ?></textarea>
   </form>
   </div>   
        <?php
    }
}
 
 
new LeafletMaps_Options_Page;
