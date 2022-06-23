jQuery( document ).ready(function() {


	jQuery(".puleaf-clear").click(function(e){
	 jQuery("#Latitude").val('');
	 jQuery("#Longitude").val('');
	 var newLatLng = new L.LatLng(0, 0);
	 marker.setLatLng(newLatLng, { draggable: 'true'}).bindPopup(position).update();
	 
    
	    	 
	 
	 var d = {'action':'leaflet_clear','postid': postid }
	 jQuery.get(leafletvars.ajaxurl, d, function(data){
	   console.log(data);
	 });
	 e.preventDefault();
	});


	jQuery(".puleaf-center").click(function(e){
	 console.log('center');
	 e.preventDefault();
	});





	/*************************
	* check if map data is active
	*************************/
	if(leafletvars.active == 1) { 
	 var coords = L.latLng(leafletvars.lat, leafletvars.lng);
	 var center = coords;
	 var zoom = leafletvars.zoom;
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
	
	
	
  	/*************************
	* overlays
	*************************/

	var overlays = {}

	if(leafletvars.overlays) {
	  var o = leafletvars.overlays.split('\r\n');
	  jQuery.each(o, function(i,v){
	    var thiso = v.split(',');
	    var nw = [thiso[2],thiso[3]]
	    var se = [thiso[4],thiso[5]]
	    var coords = [nw,se];
	    var overlay = L.imageOverlay( thiso[1] , coords);
	    overlays[thiso[0]] = overlay;
	  })
	}

	var basemaps = {'My basemap': base };
	
	

	/*************************
	* create map
	*************************/
	var map = L.map( 'MapLocation', {
	  center: center,
	  minZoom: 0,
	  zoom: zoom,
	  layers: [base]
	});
	
  	/*************************
	* turn layers 'on' by default and add control if there are overlays
	*************************/
	if(leafletvars.overlays) { 
	  for (x in overlays) {
	    overlays[x].addTo(map);
	    // if you only want to show the first overlay by default put 'break;' here
	  }
	   L.control.layers(null,overlays).addTo(map);
	}
	

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


