jQuery( document ).ready(function() {


  // if there is a map

  if(jQuery("#LeafletMap").length > 0) {
  
  
  	var mymap = jQuery("#LeafletMap");
  
	if(mymap.attr('data-z'))   	{ var zoom = mymap.attr('data-z'); } else { var zoom = 20; }
	if(mymap.attr('data-center') != 'auto')  {
	   var coords =  mymap.attr('data-center').split(',');
	   var center = [coords[0], coords[1]];
	} else { 
	   var center = [0,0];
	}  

  
  	/*************************
	* basemap
	*************************/
	var base = L.tileLayer( leafletvars.basemap, {attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'});
	var layers = [base];
	
	
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
	var map = L.map( 'LeafletMap', {
	  center: center,
	  minZoom: 0,
	  zoom: zoom,
	  layers: layers
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
	* add markers
	*************************/

	  
	  
	  if(mymap.attr('data-id')) { // this is if we want one marker for one post
	    var d = {'action':'leaflet_markers', 'postid': mymap.attr('data-id')  }

	    jQuery.get(leafletvars.ajaxurl, d, function(data){
	  
	      jQuery.each(data, function(i,v){
		  var latlng = L.latLng(v.latitude,v.longitude);
		  var popup = popupTemplate(v);
	  	  var marker = new L.marker(latlng, {}).bindPopup(popup);
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
		  var popup = popupTemplate(v);
	  	  var marker = new L.marker(latlng, {}).bindPopup(popup);
		  markers.push(marker);
	      });
	      
	      var group = new L.featureGroup(markers);
	      group.addTo(map);
	      if(center[0] == 0 && center[1] == 0) {
	        map.fitBounds(group.getBounds(), {padding: [50,50]});
	      }
	    });
	    
	  }
	  
	  
	  
	  function popupTemplate(obj) {
	    var html = "";
	    //if(obj.thumbnail) {
	    //  html = "<a href='"+obj.url+"'><img src='"+obj.thumbnail+"'/></a>";
	    //}
	    html +=     "<strong><a href='"+obj.url+"'>"+obj.post_title+"</a></strong>";
	    //html +=      obj.post_excerpt;
	    return html;
	  }
	  
  


  } // if jquery.length


});

