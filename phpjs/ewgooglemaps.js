var ewGoogleMaps = [];
var ewGoogleMapId = "ewGoogleMap";
var ewGoogleMap;
var ewGoogleMapBounds;

function ew_ShowGoogleMap(map) {
	var latlng = map["latlng"];
	if (!latlng)
		return $("#" + id).hide();
	var $ = jQuery, latlng, typeId, gmap,
		id = map["id"], width = map["width"], height = map["height"], latitude = map["latitude"], longitude = map["longitude"],
		address = map["address"], mapType = map["type"], zoom = map["zoom"], title = map["title"], icon = map["icon"], desc = map["description"],
		singleMap = map["use_single_map"], singMapWidth = map["single_map_width"], singleMapHeight = map["single_map_height"],
		mapOnTop = map["show_map_on_top"], showAllMarkers = map["show_all_markers"];
	switch (mapType.toLowerCase()) {		
		case "satellite":
			typeId = google.maps.MapTypeId.SATELLITE; break;
		case "hybrid":
			typeId = google.maps.MapTypeId.HYBRID; break;
		case "terrain":
			typeId = google.maps.MapTypeId.TERRAIN; break;
		default:
			typeId = google.maps.MapTypeId.ROADMAP;
	}
	var mapOptions = { zoom: parseInt(zoom, 10), center: latlng, mapTypeId: typeId };
	if (singleMap) { // Single map
		if (!ewGoogleMap) {
			var $div = $("<div></div>").attr("id", ewGoogleMapId).addClass("ewGoogleMap").height(singleMapHeight); // Do not specify width by style
			var $tbl = $(".ewReportTable").get(0) ? $(".ewReportTable") : $(".ewGrid");
			if (mapOnTop)
				$div.insertBefore($tbl.first());
			else
				$div.insertAfter($tbl.first());
			ewGoogleMap = new google.maps.Map($("#" + ewGoogleMapId)[0], mapOptions);
			ewGoogleMapBounds = new google.maps.LatLngBounds();
		}
		gmap = ewGoogleMap;		
	} else {
		map["map"] = gmap = new google.maps.Map($("#" + id)[0], mapOptions);
	}
	var marker = new google.maps.Marker({ // Marker
		position: latlng,
		map: gmap,
		icon: (icon) ? icon : null,
		title: (title) ? title : ""
	});	
	desc = $.trim(desc);
	if (desc) { // Info window
		var infowindow = new google.maps.InfoWindow({
			content: (desc) ? desc : ""
		});		
		google.maps.event.addListener(marker, "click", function() {
			infowindow.open(gmap, marker);
		});
	}
	if (singleMap && showAllMarkers) { // Fit bounds if single map
		ewGoogleMapBounds.extend(latlng);		
		ewGoogleMap.fitBounds(ewGoogleMapBounds);
	}
}
jQuery(function($) {
	$.each(ewGoogleMaps, function(i, map) {
		var id = map["id"], address = map["address"], latitude = map["latitude"], longitude = map["longitude"];
		if (address && $.trim(address) != "") {

			// Set a timer for better performance
			// *** change 250 to a larger number if still encountering OVER_QUERY_LIMIT error

			$.later(i * 250, null, function() {
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode({"address": address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						map["latlng"] = results[0].geometry.location;
						ew_ShowGoogleMap(map);
					} else {

						//alert("Geocode was not successful for the following reason: " + status);
						$("#" + id).css({width: "", height: "", display: "inline-block"}).html(status);
					}
				});
			});
		} else if (latitude && !isNaN(latitude) && longitude && !isNaN(longitude)) {
			map["latlng"] = new google.maps.LatLng(latitude, longitude);
			ew_ShowGoogleMap(map);
		} else {
			$("#" + id).hide();
		}	
	});	
});
