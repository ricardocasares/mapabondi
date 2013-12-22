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
    , $startGeo = $('#startGeo')
    , $endGeo = $('#endGeo')
    , $start = $('#start')
    , $end = $('#end')
    , map_div = document.getElementById("map_canvas")
    , startAuto = new google.maps.places.Autocomplete(startGeo)
    , endAuto = new google.maps.places.Autocomplete(endGeo)
    , startMarker = new google.maps.Marker()
    , endMarker = new google.maps.Marker()
    , line = []
    , lines = []
    , map
    , bounds
    , locationService = new google.maps.places.AutocompleteService()
    , geocodeService = new google.maps.Geocoder()
    , request = superagent;
  
  // configuration options
  var endpoints = {
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
      }
    , sel2Options = {
        placeholder: "Buscar una dirección",
        width: "100%",
        minimumInputLength: 3,
        query: function(options) {
          locationService.getPlacePredictions({
            input: options.term,
            radius: 200,
            location: mapOptions.center,
            types: ['geocode']
          },
          // callback
          function(predictions, status) {
            var results = {more: false};
            if (status != google.maps.places.PlacesServiceStatus.OK) {
              results.results = [];
            } else {
              results.results = predictions;
              results.results = $.map(predictions, function(obj) {
                return {'id': obj.description, 'text': obj.description, gid: obj.id};
              });
            }
            options.callback(results);
          });
        },
        initSelection: function(element, callback) {
          var val = $(element).val();
          if (val) {
            callback({id: val, text:val});
          }
        }
      };

  // handle sidebar hiding
  $menuToggle.click(function(e) {
    e.preventDefault();
    $wrapper.toggleClass("active");
  });

  // handle geolocation autocomplete
  $(startGeo).select2(sel2Options).change(function(val) { autoChanged(val,startMarker,$start)});
  $(endGeo).select2(sel2Options).change(function(val) { autoChanged(val,endMarker,$end)});

  // handle form submit
  $searchForm.submit(function(e){
    e.preventDefault();
    var start = btoa($start.val() || false);
    var end = btoa($end.val() || false);
    page('/search/from/' + start + '/to/' + end);
  })

  // MAP RELATED FUNCTIONS

  // instatiates the map
  var initMap = function() {
    map = new google.maps.Map(map_div, mapOptions);

    // on map idle get map bounds
    google.maps.event.addListener(map, 'idle', function() {
      bounds = map.getBounds();
    });
  }

  // autocomplete change handler
  var autoChanged = function (val,marker,hidden) {
    geocodeService.geocode({address: val.val}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          var location = results[0].geometry.location;
          
          updateHidden(hidden,location);    
          map.setCenter(location);
          map.setZoom(17); 

          marker.setDraggable(true);
          marker.setMap(map);
          marker.setPosition(location);
          marker.setVisible(true);
        }
      });
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
    clearOverlays();
    $('.transports').hide();
    $('.form-sidebar').hide();
  }

  // transports routes
  var transports = {
    
    all: function(ctx,next) {
      if(ctx.state.transports) {
        next();
      }
      else {
        request
          .get(endpoints.transports)
          .end(function(res){
            ctx.state.transports = res.body;
            ctx.save();
            next();
          });
      }
    },

    one: function(ctx,next) {
      if(ctx.state.transport) {
        next();
      }
      else {
        var url = endpoints.lines.replace(':id',ctx.params.id);
        request
          .get(url)
          .end(function(res){
            ctx.state.transport = res.body;
            ctx.save();
            next();
          });
      }
    },

    index: function (ctx,next) {
      $('.form-sidebar').hide();
      render('transports-tpl','.transports',ctx.state.transports);
    },

    show: function(ctx,next) {
      render('lines-tpl','.transports',ctx.state.transport);
    },

    plot: function(ctx,next) {
      var routes = endpoints.routes.replace(':id',ctx.params.lid);
      var transport  = endpoints.lines.replace(':id',ctx.params.tid);

      request
        .get(routes)
        .end(function(res){
          draw(res.body.routes);
        });

      request
        .get(transport)
        .end(function(res){
          render('lines-tpl','.transports',res.body);
        });
    }
  }

  var draw = function(points) {
    clearOverlays();
    $.each(points, function(index,route) {
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
  }

  var search = function(ctx,next) {
    $('.transports').hide();
    $('.form-sidebar').show();
    clearOverlays();
  }

  var fromTo = function(ctx,next) {
    var from = atob(ctx.params.from);
    var to = atob(ctx.params.to);
    request
      .get('/api/find')
      .query({
        start: from,
        end: to
      })
      .end(function(res){
        render('results-tpl','.transports',res.body);
      });
    next();
  }

  var searchResults = function(ctx,next) {
    var routes = endpoints.routes.replace(':id',ctx.params.id);
    request
      .get(routes)
      .end(function(res){
        draw(res.body.routes);
      });
  }

  // renders a template in the outlet
  var render = function(tpl,outlet,data) {
    var src = $('#' + tpl).html();
    var tpl = Handlebars.compile(src)(data);
    $(outlet).html(tpl).show();
  }

  // initialize map first
  initMap();

  // PAGES SETUP

  // index
  page('/', index);
  // show transports
  page('/transports', transports.all, transports.index);
  page('/transports/:id/lines', transports.one, transports.show);
  page('/transports/:tid/lines/:lid/plot', transports.plot);
  page('/search', search);
  page('/search/from/:from/to/:to', fromTo, search);
  page('/search/results/lines/:id/plot', searchResults);
  // init
  page();

})();