var map;
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var waypoints = [];

document.getElementById("rota-btn").addEventListener("click", event => {
	event.preventDefault();
	event.target.setAttribute("disabled", "disabled");
});

function criarRota(address = "") {	
	setMarkers(address, "Partida & Destino", 0, "images/marker-route.png");
	var request = {
		origin: address,
		destination: address,
		waypoints,
		travelMode: google.maps.TravelMode.DRIVING
	};
   
	directionsService.route(request, function(result, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(result);
		}
	});

	directionsDisplay.setMap(map);
}

function initMap() {
	directionsDisplay = new google.maps.DirectionsRenderer({ suppressMarkers: true });

	var styles = {
		default: null,
		hide: [
			//{ featureType: 'transit', stylers: [{visibility: 'off'}] },
			{ featureType: 'poi.school', stylers: [{visibility: 'off'}] },
			{ featureType: 'poi.medical', stylers: [{visibility: 'off'}] },
			{ featureType: 'poi.government', stylers: [{visibility: 'off'}] },
			{ featureType: 'poi.business', stylers: [{visibility: 'off'}] },
			{ featureType: 'poi.place_of_worship', stylers: [{visibility: 'off'}] },
			{ featureType: 'poi.park', stylers: [{visibility: 'off'}] },
			{ featureType: 'poi.sports_complex', stylers: [{visibility: 'off'}] },
			{ featureType: 'poi.attraction', stylers: [{visibility: 'off'}] }
		]
	};

	let element = document.getElementById('mapCanvas');
	let mapOpt = {
		center: {lat: 0, lng: 0},
		zoom: 13,
		mapTypeId: 'roadmap', // roadmap, satellite, hybrid, terrain, osm
		mapTypeControlOptions: { mapTypeIds: [ 'roadmap', 'osm' ] },
		streetViewControl: false,
		// zoomControl: false,
		// disableDefaultUI: false
	};

	map = new google.maps.Map(element, mapOpt);

	map.setOptions({styles: styles['default']});

	let osm = new google.maps.ImageMapType({
		tileSize: new google.maps.Size(256, 256),
		name: 'OSM',
		getTileUrl: function(coord, zoom) {
			return `https://tile.openstreetmap.org/${zoom}/${coord.x}/${coord.y}.png`;
		},
		maxZoom: 18
	});

	map.mapTypes.set('osm', osm);
}

function setPosition(address = "brasil, sao paulo") {
	let geocoder = new google.maps.Geocoder();
	geocoder.geocode({address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			let lat = results[0].geometry.location.lat();
			let lng = results[0].geometry.location.lng();
			map.panTo({lat, lng});
		}
	});
}



async function setMarkers(address = "sao paulo", msg = "0 litros", tm = 0, icon = "images/marker.png") {
	await sleep(tm)
	let geocoder = new google.maps.Geocoder();	
	geocoder.geocode({address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			let lat = results[0].geometry.location.lat();
			let lng = results[0].geometry.location.lng();

			let marcadorOpt = {
				position: {lat, lng},
				map,
				icon,
				animation: google.maps.Animation.BOUNCE,
				zIndex: 1
				//title: "",
				//label: "",
				//draggable: true
			}	
		
			if (icon == "images/marker.png") {
				waypoints.push({location: address});
				marcadorOpt.zIndex = 100;
			}

			let marcador = new google.maps.Marker(marcadorOpt);

			let infoW = new google.maps.InfoWindow({
				content: `<p class='balao-map-p'>${msg}</p>`,
				maxWidth: 200
			});
			
			marcador.addListener('click', () => {
				infoW.open(map, marcador);
			});
		} else
			console.log("Nao foi possivel obter localizacao: " + status);
	});
}
