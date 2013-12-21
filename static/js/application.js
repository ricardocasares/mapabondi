;(function(){
  // SETUP VARS
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
    , request = superagent
    , endpoints = {
        transports: "/api/transports",
        transport: "/api/transports/:id",
        lines: "/api/transports/:id/lines",
        routes: "/api/lines/:id/routes",
        find: "/api/find",
      }
    , mapOptions = {
        center: new google.maps.LatLng(-31.53714,-68.525462),
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true
      };

  // MAP RELATED FUNCTIONS

  // instatiates the map
  var initMap = function() {
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
  }

  // autocomplete change handler
  var autoChanged = function (autocomplete,marker,hidden) {
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

  // updates hidden inputs for coordinates
  var updateHidden = function (hidden,coords) {
    hidden.val([coords.lat(),coords.lng()].join(','));
  }

  // clears map overlays
  var clearOverlays = function () {
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

  // zooms according to the object on map
  var zoomToObject = function (obj){
      var bounds = new google.maps.LatLngBounds();
      var points = obj.getPath().getArray();
      for (var n = 0; n < points.length ; n++){
          bounds.extend(points[n]);
      }
      map.fitBounds(bounds);
  }


  // EVENT LISTENERS

  // start input autocomplete
  google.maps.event.addListener(startAuto, 'place_changed', function(){
    autoChanged(startAuto,startMarker,$start);
  });
  // end input autocomplete
  google.maps.event.addListener(endAuto, 'place_changed', function(){
    autoChanged(endAuto,endMarker,$end);
  });
  // start marker drag end
  google.maps.event.addListener(startMarker, 'dragend', function() {
    updateHidden($start,startMarker.getPosition());
  });
  // end marker drag end
  google.maps.event.addListener(endMarker, 'dragend', function() {
    updateHidden($end,endMarker.getPosition());
  });


  // HANDLEBARS HELPERS

  // truncates text
  Handlebars.registerHelper('trunc', function(text,size) {
    if(text.length <= size) return text;
    return text.substring(0,size) + " ...";
  });

  // ROUTES RELATED FUNCTIONS

  // index route
  var index = function (ctx,next) {
    
  }

  // transports routes
  var transports = {
    
    load: function(ctx,next) {
      if(ctx.state.transports) {
        console.log(ctx.state.transports);
      }
      else {
        request
          .get(endpoints.transports)
          .end(function(res){
            ctx.transports = res.body.transports;
            next();
          });
      }
    },

    index: function (ctx,next) {
      console.log(ctx.state);
      render('transports-tpl','.transports',ctx.transports);
    },

    show: function(ctx,next) {
      
    }
  }

  var search = function(ctx,next) {
    
  }

  // renders a template in the outlet
  var render = function(tpl,outlet,data) {
    var src = $('#' + tpl).html();
    var tpl = Handlebars.compile(src);
    $(outlet).html(tpl).show();
  }

  // initialize map first
  initMap();

  // PAGES SETUP

  // index
  page('/', index);
  // show transports
  page('/transports', transports.load, transports.index);
  page('/search', search);
  // init
  page();

})();