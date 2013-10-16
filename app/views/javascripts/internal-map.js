
	 if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(
				successCallback, 
				displayError,
				{maximumAge: 750000 }
			);
	  } else {
			alert("Geolocation is not supported by this browser");
	  }

	  function successCallback(position) {
		    var myLatlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	  		getMap(myLatlng);
	  }
	  // InfoWindows inizialing
	  var opt = {
				disableAutoPan: false
                ,maxWidth: 0
                ,pixelOffset: new google.maps.Size(-200, -120)
                ,boxStyle: { 
                  background: "#FFFFFF"
                  ,width: "400px"
				  ,padding:"10px"
                 }
                ,infoBoxClearance: new google.maps.Size(1, 1)
                ,isHidden: false
                ,pane: "floatPane"
                ,enableEventPropagation: false
        	};
	  var ib = new InfoBox(opt);
	  var markersArray = [];
	  // Get the map and markers
	  function getMap(myLatlng) {
        var mapOptions = {
          center: myLatlng,
          zoom: 15,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

        google.maps.Map.prototype.clearOverlays = function() {
  for (var i = 0; i < markersArray.length; i++ ) {
    markersArray[i].setMap(null);
  }
}
		// Close infoWindow on map click
		google.maps.event.addListener(map, 'click', function() {
        	ib.close();
        });

          google.maps.event.addListener(map, 'idle', function() {
    var bounds = map.getBounds();

            var ne = bounds.getNorthEast();
            var sw = bounds.getSouthWest();
            console.log("toplat: " + ne.lat() + " \nbottomLat: " + sw.lat() + " \nleftLong: " + sw.lng() + " \nrightLong" + ne.lng());
            // ;

            $.ajax({
  url: "/projects/qcino/search/" + sw.lat() + "/" + ne.lat() + "/" + sw.lng() + "/"+ ne.lng(),
  beforeSend: function ( xhr ) {
    xhr.overrideMimeType("text/plain; charset=x-user-defined");
  }
}).done(function ( data ) {
	console.log(data);
  	map.clearOverlays();
  	var obj = jQuery.parseJSON(data);
  	setMarkers(map, obj);
  // if( console && console.log ) {
  //   var obj = jQuery.parseJSON(data);
  //   console.log(data);
  //   for(var k in obj) {

  //    var myLatlng = new google.maps.LatLng(obj[k].lat,obj[k].long);
  
  // var marker = new google.maps.Marker({
  //     position: myLatlng,
  //     map: map,
  //     title: 'test'
  // });
  // markersArray.push(marker);
  // }
  
  // }
});
  });

		
	  }
	  function setMarkers(map, locations) {
	  	var marker, i, boxText = [];
		var iconBase = 'app/views/images/shapes/';
		
		for(var i in locations) { 
		  	var siteLatLng = new google.maps.LatLng(locations[i].lat, locations[i].lng);
			
			marker = new google.maps.Marker({
                position: siteLatLng,
                map: map,
                draggable: false,
				icon: iconBase + 'premium.png',
                visible: true
            });
			
			boxText[i] = '<div class="miniuserbox">'+
                    ''+
                    	'<div class="three columns">'+
                        	'<img src="images/'+locations[i].name+'" alt="'+locations[i].name+'" width="100%" />'+
							'<h3>Score <span class="green">'+locations[i].id+'</span></h3>'+
                        '</div>'+
                        '<div class="nine columns">'+
                        	'<h2 style="margin-top: -15px">'+locations[i].title+'</h2>'+
                            '<p class="small">'+locations[i].description+'</p>'+
                            '<ul class="block-grid three-up mobile-five-up">'+
							  '<li><h3>Seat: <span class="green">'+locations[i].id+'</span> / '+locations[i].id+'</h3></li>'+
							  '<li><h3 class="orange">'+locations[i].name+'$ P.P</h3></li>'+
							  '<li><h3><a href="listingid='+locations[i].id+'">View detail</a></h3></li>'+
							'</ul>'+
						'</div>'+
						'<div class="twelve columns badges">'+
							'<h3>Badges</h3>'+
							'<ul class="block-grid six-up mobile-five-up">'+
							  '<li><span class="icon-coffee icon-2x"></span></li>'+
							  '<li><span class="icon-beer icon-2x"></span></li>'+
							  '<li><span class="icon-food icon-2x"></span></li>'+
							  '<li><span class="icon-magic icon-2x"></span></li>'+
							  '<li><span class="icon-beaker icon-2x"></span></li>'+
							  '<li><span class="icon-star icon-2x"></span></li>'+
							'</ul>'+
                        '</div>'+
                    '</div>';
			markersArray.push(marker);
		  google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
			  ib.setContent(boxText[i]);
			  ib.open(map, marker);
			}
		  })(marker, i));
		}
	  }
	
	  
	  
	  function displayError(error) {
		  var errors = { 
			1: 'Permission denied',
			2: 'Position unavailable',
			3: 'Request timeout'
		  };
		  alert("Error: " + errors[error.code]);
		}
      //google.maps.event.addDomListener(window, 'load', initialize);