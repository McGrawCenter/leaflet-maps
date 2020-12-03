jQuery( document ).ready(function() {


  // if there is a map

  if(jQuery("#LeafletMap").length > 0) {
  
  
  	var mymap = jQuery("#LeafletMap");
  
	if(mymap.attr('data-z')) {   var zoom = mymap.attr('data-z'); } else { var zoom = 20; }  
	if(mymap.attr('data-lat')) { var center = [mymap.attr('data-lat'),mymap.attr('data-lng')]; } else { var center = [0,0]; }
  
  
  	/*************************
	* basemap
	*************************/
	var base = L.tileLayer( leafletvars.basemap, {attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'});
	var layers = [base];
	
	
  	/*************************
	* overlays
	*************************/

	var overlays = []

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
	var map = L.map( 'LeafletMap', {
	  center: center,
	  minZoom: 0,
	  zoom: zoom,
	  layers: layers
	});
	
	
	if(leafletvars.overlays) { 
	   console.log(overlays[0]);
	   L.control.layers(null,overlays).addTo(map);
	}	







	/*************************
	* add markers
	*************************/
	  
	  
	  if(mymap.attr('data-id')) { // this is if we want one marker for one post
	    var d = {'action':'leaflet_markers', 'postid': mymap.attr('data-id')  }

	    jQuery.get(leafletvars.ajaxurl, d, function(data){
	  
	      jQuery.each(data, function(i,v){
		  var latlng = L.latLng(v.latitude,v.longitude);
	  	  var marker = new L.marker(latlng, {}).bindPopup("<h5><a href='"+v.url+"'>"+v.post_title+"</a></h5>"+v.post_excerpt);
		  marker.addTo(map);
	      });
	      map.setView(center, zoom);
	    });
	    
	    
	    
	    
	    
	  }
	  else { // multiple markers, i.e. big map
	  
	    var d = {'action':'leaflet_markers' }
	    var markers = [];
	    
	    jQuery.get(leafletvars.ajaxurl, d, function(data){
	  
	      jQuery.each(data, function(i,v){
		  var latlng = L.latLng(v.latitude,v.longitude);
	  	  var marker = new L.marker(latlng, {}).bindPopup("<h5><a href='"+v.url+"'>"+v.post_title+"</a></h5>"+v.post_excerpt);
		  markers.push(marker);
	      });
	      var group = new L.featureGroup(markers);
	      group.addTo(map);
	      map.fitBounds(group.getBounds(), {padding: [50,50]});
	    });
	    
	  }
  


  } // if jquery.length


});

