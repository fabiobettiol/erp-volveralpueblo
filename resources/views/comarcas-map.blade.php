<!DOCTYPE html>
<html>
<body>

<div id="map" style="width:100%;height:800px;"></div>

<script>
	var map;
	var infoWindows = [];

	function initMap() {

		var comarcas = @json($comarcas);
		var cdrs = @json($cdrs);

	    var styles = [
	      {
	        "featureType": "administrative.province",
	        "elementType": "geometry.stroke",
	        "stylers": [
	          { "visibility": "on" },
	          { "weight": 1 },
	          { "color": "#000" }
	        ]
	      },{
	        "featureType": "road",
	        "elementType": "geometry",
	        "stylers": [
	          { "visibility": "off" }
	        ]
	      },{
	        "featureType": "administrative.locality",
	        "stylers": [
	          { "visibility": "on" }
	        ]
	      },{
	        "featureType": "road",
	        "elementType": "labels",
	        "stylers": [
	          { "visibility": "on" }
	        ]
	      }
	    ];   

	    var options = {
	        zoom:6,
	        maxZoom:12,
	        styles:styles,
	        center:{lat:39.86338991167967,lng:-4.027926176082693}
	    }

		map = new google.maps.Map(document.getElementById("map"), options);
	
		$.each(comarcas, function(index, comarca) {

			var coordinates = eval(comarca.polygon); // - Google Maps expects an array here. It is a string.

			var p = new google.maps.Polygon({
				paths: coordinates,
				strokeColor: '#000000',
				strokeOpacity: 1,
				strokeWeight: 0.25,
				fillColor: '#0090ff',
				fillOpacity: 0.5
			});  

			p.setMap(map);

			attachPolygonInfoWindow(p, comarca);

		}); 

	    var cdrIcon = {
	        path: "M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 256c-35.3 0-64-28.7-64-64s28.7-64 64-64s64 28.7 64 64s-28.7 64-64 64z",
	        fillColor: '#E32831',
	        fillOpacity: 1,
	        strokeWeight: 0,
	        scale: 0.04    
	    }

		$.each(cdrs, function(index, cdr)  {
			var marker = new google.maps.Marker({
		        position: {lat: Number(cdr.lat), lng: Number(cdr.lng)},
		        icon: cdrIcon,
		        map:map,
		        id: cdr.id,
		        index: index
		    });

		    attachMarkerInfoWindow(marker, cdr);
		});


	}

	function attachPolygonInfoWindow(p, comarca) {

		var infoWindow = new google.maps.InfoWindow();

	    google.maps.event.addListener(p, 'click', function (e) {
	    	closeInfoWindows();
	        infoWindow.setContent(infoComarca(comarca));
	        var latLng = e.latLng;
	        infoWindow.setPosition(latLng);
	        infoWindow.open(map);
	    });

	    infoWindows.push(infoWindow);
	}	

	function attachMarkerInfoWindow(marker, cdr) {

		var infoWindow = new google.maps.InfoWindow();

	    google.maps.event.addListener(marker, 'click', function (e) {
	    	closeInfoWindows();
	        infoWindow.setContent(infoCdr(cdr));
	        var latLng = e.latLng;
	        infoWindow.setPosition(latLng);
	        infoWindow.open(map);
	    });

	    infoWindows.push(infoWindow);
	}	


	function infoComarca(comarca) {	

		var c = `
			<div style="color: #000;font-size: 12px; padding: 5px; min-width: 150px;">
			    Comarca: <span style="font-weight: bold">${comarca.name}</span>
			    <br>
			    <span style="color: ##595959">Provincia: ${comarca.province.name} <br>CCAA: ${comarca.community.name}<br></span>
			</div>
		`;

		return c;
	}

	function infoCdr(cdr) {	

		console.log(cdr);

		var c = `
			<div style="color: #000;font-size: 12px; padding: 5px; min-width: 150px;">
			    <div style="width: 100%; text-align: center">
			    	<img style="width: 80px" src="storage/${cdr.logo}">
			    </div>			
			    <span style="font-weight: bold">${cdr.name}</span>
			    <br>
			    <span>${cdr.cdrtype.name}</span>
			    <hr>
			    <span>${cdr.address}, <br> ${cdr.city}, ${cdr.pc} <br> ${cdr.province.name}, ${cdr.community.name}</span>
			    <hr>
			    <span>Teléfono: ${cdr.phone} <br> <a target="_blank" href="${cdr.web}">${cdr.web}</a></span>
			    <br><br>
		`;

		if (cdr.link) {
			c = c + `<span><a target="_blank" href="${cdr.link}">${cdr.link_title}</a></span>`;
		} 

		c = c + '</div>'

		return c;
	}	

	function closeInfoWindows() {

		$.each(infoWindows, function(index, window) {
			window.close();
		});

	}


</script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB6jHWMPyq9CDtJ2ldR_mOaAtHfdvUPAJw&callback=initMap"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
