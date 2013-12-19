var $menuToggle = $("#menu-toggle")
  , $wrapper = $('#wrapper')
  , $searchToggle = $('.searchToggle')
  , $searchForm = $('.form-sidebar')
  , $searchResults = $('.results');

$menuToggle.click(function(e) {
  e.preventDefault();
  $wrapper.toggleClass("active");
});
$searchToggle.click(function(e){
  e.preventDefault();
  $searchForm.toggle();
});
$searchForm.submit(function(e){
  $.getJSON('/api/find',$(this).serialize()).done(function(data){
    $searchResults.html('<img height="50px" class="thumbnail" src="'+data.lines[0].image+'"/>');
  });
  event.preventDefault();
});

function initialize() {
  // set map options
  var mapOptions = {
    center: new google.maps.LatLng(-31.53714,-68.525462),
    zoom: 15,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    disableDefaultUI: true
  };
  
  // instantiate map
  var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
  
  // setup vars
  var start = document.getElementById("start")
    , end = document.getElementById("end")
    , startGeo = $('#startGeo')
    , endGeo = $('#endGeo')
    , startAuto = new google.maps.places.Autocomplete(start)
    , endAuto = new google.maps.places.Autocomplete(end)
    , startMarker = new google.maps.Marker({ map: map })
    , endMarker = new google.maps.Marker({ map: map });

  google.maps.event.addListener(startAuto, 'place_changed', function(){
    autoChanged(startAuto,startMarker,startGeo);
  });
  google.maps.event.addListener(endAuto, 'place_changed', function(){
    autoChanged(endAuto,endMarker,endGeo);
  });

  // autocomplete change handler
  function autoChanged(autocomplete,marker,hidden) {
    marker.setVisible(false);
    var place = autocomplete.getPlace();
    
    if (!place.geometry) {
      return;
    }

    hidden.val(place.geometry.location.nb + ',' + place.geometry.location.ob);

    // if the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17); 
    }
    marker.setIcon({
      url: place.icon,
      size: new google.maps.Size(71, 71),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(17, 34),
      scaledSize: new google.maps.Size(35, 35)
    });
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);    
  }
}

$(function(){
  initialize();
});