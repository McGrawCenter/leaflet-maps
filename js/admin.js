jQuery( document ).ready(function() {

console.log(leafletvars);


	/*************************
	* check if map data is active
	*************************/
	if(leafletvars.mapdata.active == 1) { 
	 var coords = L.latLng(leafletvars.mapdata.lat, leafletvars.mapdata.lng);
	 var center = coords;
	 var zoom = leafletvars.mapdata.zoom;
	}
	else { 
	  var coords = [0,0];
	  var center = L.latLng(39.54529201504656, -97.13666451083057);
	  var zoom = 4;
	}


	/*************************
	* basemap
	*************************/
	var base = L.tileLayer( '//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'});

	// create map
	var map = L.map( 'MapLocation', {
	  center: center,
	  minZoom: 0,
	  zoom: zoom,
	  layers: [base]
	});

	/*************************
	* add marker
	*************************/
        var marker = new L.marker( coords, { draggable: 'true' } ).addTo(map);



	/***************************
	* double click the map 
	***************************/

	map.on('dblclick', function(e) {
	
	  var position = [e.latlng.lat,e.latlng.lng];
	  var zoom = map.getZoom();

	  marker.setLatLng(position, {
	    draggable: 'true'
	  }).bindPopup(position).update();
	  
	  jQuery("#Active").val('1');	 
	  jQuery("#Zoom").val(zoom);
	  jQuery("#Latitude").val(position.lat);
	  jQuery("#Longitude").val(position.lng);
       });



	/***************************
	* finish dragging  ********/
	
	marker.on('dragend', function(event) {
	  var position = marker.getLatLng();
	  var zoom = map.getZoom();

	  marker.setLatLng(position, {
	    draggable: 'true'
	  }).bindPopup(position).update();

	  jQuery("#Zoom").val(zoom);
	  jQuery("#Latitude").val(position.lat);
	  jQuery("#Longitude").val(position.lng).keyup();
	  jQuery('#Show').prop('checked', true);
	});
	
	
	
	/***************************
	* finish zooming **********/
	
	map.on('zoomend', function() {
	var position = marker.getLatLng();
	var zoom = map.getZoom();

	jQuery("#Zoom").val(zoom);
	jQuery("#Latitude").val(position.lat);
	jQuery("#Longitude").val(position.lng);
	jQuery('#Show').prop('checked', true);      
	});


});


