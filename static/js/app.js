// setup vars
var $menuToggle = $("#menu-toggle")
  , $wrapper = $('#wrapper')
  , $transportsToggle = $('.transportsToggle')
  , $transports = $('.transports')
  , $searchToggle = $('.searchToggle')
  , $searchForm = $('.form-sidebar')
  , $formReset = $('.formReset')
  , $searchResults = $('.results')
  , startGeo = document.getElementById("startGeo")
  , endGeo = document.getElementById("endGeo")
  , $start = $('#start')
  , $end = $('#end')
  , startAuto = new google.maps.places.Autocomplete(startGeo)
  , endAuto = new google.maps.places.Autocomplete(endGeo)
  , startMarker = new google.maps.Marker()
  , endMarker = new google.maps.Marker()
  , line = []
  , lines = []
  , map
  , endpoints = {
      transports: "/api/transports",
      transport: "/api/transports/:id",
      lines: "/api/transports/:id/lines",
      routes: "/api/lines/:id/routes",
      find: "/api/find",
    };

// Event listeners
$menuToggle.click(function(e) {
  e.preventDefault();
  $wrapper.toggleClass("active");
});
$transportsToggle.click(function(e){
  e.preventDefault();
  $transports.toggle();
  if($transports.is(':visible')) getTransports(e);
});
$searchToggle.click(function(e){
  e.preventDefault();
  $searchForm.toggle();
});
$formReset.click(function(e){
  $start.val('');
  $end.val('');
  $searchResults.slideUp('fast');
  clearOverlays();
});
$searchForm.submit(function(e){
  clearOverlays();
  $.getJSON(endpoints.find,$(this).serialize()).done(function(data){
    var html = render('#results-tpl',data,'.results');
    html.slideDown('fast').find('.btn').click(function(e){
      getRoutes(e,$(this));
    });
  });
  event.preventDefault();
});

// initializes map and listeners
function initialize() {
  // set map options
  var mapOptions = {
    center: new google.maps.LatLng(-31.53714,-68.525462),
    zoom: 15,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    disableDefaultUI: true
  };
  
  // instantiate map
  map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

  google.maps.event.addListener(startAuto, 'place_changed', function(){
    autoChanged(startAuto,startMarker,$start);
  });
  google.maps.event.addListener(endAuto, 'place_changed', function(){
    autoChanged(endAuto,endMarker,$end);
  });

  // autocomplete change handler
  function autoChanged(autocomplete,marker,hidden) {
    marker.setVisible(false);
    var place = autocomplete.getPlace();
    
    if (!place.geometry) {
      return;
    }

    updateHidden(hidden,place.geometry.location);
    

    // if the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17); 
    }

    marker.setDraggable(true);
    marker.setMap(map);
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);
  }

  google.maps.event.addListener(startMarker, 'dragend', function() {
    updateHidden($start,startMarker.getPosition());
  });

  google.maps.event.addListener(endMarker, 'dragend', function() {
    updateHidden($end,endMarker.getPosition());
  });
}

// render handlebars templates
function render(template,data,outlet){
  var src = $(template).html();
  $(outlet).html(Handlebars.compile(src)(data));
  return $(outlet);
}

// shows transports
function getTransports(e,el) {
  e.preventDefault();
  $.getJSON(endpoints.transports).success(function(data){
    var html = render('#transports-tpl',data,'.transports');
    html.find('.getTransportLines').click(function(e){
      getTransportLines(e,$(this));
    });
  });
}

// get transports lines
function getTransportLines(e,el) {
  e.preventDefault();
  var id = el.attr('data-transport-id');
  var url = endpoints.lines.replace(':id',id);

  $.getJSON(url).success(function(data){
    var html = render('#lines-tpl',data,'.lines-'+id);
    html.toggle();
    html.find('.road').click(function(e){
      getRoutes(e,$(this));
    });
  });
}

// get routes from lines and plots them on the map
function getRoutes(e,el){
  e.preventDefault();
  clearOverlays();

  var id = el.attr('data-line-id')
    , color = getColor();

  var url = endpoints.routes.replace(':id',id);

  $.getJSON(url).success(function(data){
    $.each(data.routes, function(index,route) {
      line.push(new google.maps.LatLng(route.lat,route.lng));
    });

    lines.push(new google.maps.Polyline({
      map: map,
      path: line,
      strokeColor: "#a43796",
      strokeOpacity: 1.0,
      strokeWeight: 4
    }));
    zoomToObject(lines[lines.length-1]);
  });
}

function updateHidden(hidden,coords) {
  hidden.val([coords.lat(),coords.lng()].join(','));
}

// clears map overlays
function clearOverlays() {
  if(line.length > 0) {
    line = [];
  }
  if(lines.length > 0) {
    $.each(lines,function(i,l){
      lines[i].setMap(null);
      lines.pop();
    });
  }
}

function zoomToObject(obj){
    var bounds = new google.maps.LatLngBounds();
    var points = obj.getPath().getArray();
    for (var n = 0; n < points.length ; n++){
        bounds.extend(points[n]);
    }
    map.fitBounds(bounds);
}

// generates a random color
function getColor() {
  return '#'+Math.floor(Math.random()*16777215).toString(16);
}

// handlebars helper: truncates text
Handlebars.registerHelper('trunc', function(text,size) {
  if(text.length <= size) return text;
  return text.substring(0,size) + " ...";
});

// init map
$(function(){
  initialize();
});