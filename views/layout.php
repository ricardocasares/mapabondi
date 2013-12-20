<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mapabondi</title>

    <!-- Bootstrap core CSS -->
    <link href="static/css/bootstrap.min.css" rel="stylesheet">
    <link href="static/css/font-awesome.min.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="static/css/app.css" rel="stylesheet">

  </head>

  <body>
    <div id="wrapper">
      
      <!-- Sidebar -->
      <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
          <li class="sidebar-brand">
          	<a href="/">
          		<i class="fa fa-map-marker"></i> mapabondi
          	</a>
          </li>
          <li>
          	<a class="transportsToggle" href="#">
          		<i class="fa fa-building-o"></i> Empresas de transporte
          	</a>
          </li>
        </ul>
        
        <!-- Transports -->
        <div class="transports"></div>

        <ul class="sidebar-nav">
          <li>
            <a class="searchToggle" href="#">
              <i class="fa fa-search"></i> Buscar recorrido
            </a>
        </ul>

        <!-- Search form -->
        <form class="form-sidebar">
          <div class="form-group">
            <label for="start">
              <i class="fa fa-map-marker"></i> Dirección inicial
            </label>
            <input type="text" class="form-control" id="startGeo" placeholder="Mendoza Sur 360">
            <input type="hidden" name="start" id="start">
          </div>
          <div class="form-group">
            <label for="end">
              <i class="fa fa-flag-checkered"></i> Dirección final
            </label>
            <input type="text" class="form-control" id="endGeo" placeholder="Libertador Oeste 2420">
            <input type="hidden" name="end" id="end">
          </div>
          <button class="btn btn-sm btn-default">
            Buscar
          </button>
          <button type="reset" class="formReset btn btn-sm btn-default">
            <i class="fa fa-trash-o"></i> Reiniciar
          </button>
        </form>

        <!-- Search results -->
        <div class="results">

        </div>
        <div id="footer">
          <p>
            <span class="mapabondi">
              <i class="fa fa-map-marker"></i> mapabondi
            </span>
            &copy; <?php echo date('Y') ?> <a href="http://betamonster.com.ar/">Betamonster</a> 
          </p>
        </div>
      </div>
          
      <!-- Page content -->
      <div id="page-content-wrapper">
      	<a id="menu-toggle" href="#" class="btn btn-default"><i class="fa fa-bars"></i></a>
      	<div id="map_canvas"></div>
      </div>
      
    </div>

    <script id="results-tpl" type="text/x-handlebars-template">
      {{#if lines}}
        <div class="result-list">
          <ul class="list-unstyled">
            <li class="dropdown-header"><i class="fa fa-search"></i> Resultados de la búsqueda</li>
            {{#each lines}}
            <li title="{{name}}">
              {{trunc name 25}}
              <span class="pull-right">
                <a data-line-id="{{id}}" class="road btn btn-xs btn-default">
                  <i class="fa fa-road"></i> Ver recorrido
                </a>
              </span>
            </li>
            {{/each}}
          </ul>
        </div>
      {{else}}
        <div class="alert alert-info">
          <p class="text-center"><i class="fa fa-warning fa-5x"></i></p>
          <p class="text-center">
            No hubo resultados
          </p>
        </div>
      {{/if}}
    </script>

    <script id="transports-tpl" type="text/x-handlebars-template">
      {{#if transports}}
        <ul class="transports-list media-list">
        {{#each transports}}
          <li class="media">
            <img class="media-object pull-left" src="{{image}}" />
            <div class="media-body">
              <h5 class="media-heading">{{name}}</h5>
              <ul class="fa-ul transport-info">
                {{#if address}}<li><i class="fa-li fa fa-home"></i> {{address}}</li>{{/if}}
                {{#if phone}}<li><i class="fa-li fa fa-phone"></i> {{phone}}</li>{{/if}}
                {{#if url}}<li><i class="fa-li fa fa-globe"></i> <a href="{{url}}">Ver sitio web</a></li>{{/if}}
              </ul>
              <a data-transport-id="{{id}}" class="getTransportLines btn btn-xs btn-default">
                Ver líneas
              </a>
            </div>
            <div class="result-list lines-{{id}}"></div>
          </li>       
        {{/each}}
        </ul>
      {{else}}
        <div class="alert alert-warning">
          No existen transportes todavía
        </div>
      {{/if}}
    </script>

    <script id="lines-tpl" type="text/x-handlebars-template">
      {{#if lines}}
        <ul class="list-unstyled">
        <li class="dropdown-header">Lineas</li>
        {{#each lines}}
          <li title="{{name}}">
            {{trunc name 25}}
            <span class="pull-right">
              <a data-line-id="{{id}}" class="road btn btn-xs btn-default">
                <i class="fa fa-road"></i> Ver recorrido
              </a>
            </span>
          </li>
        {{/each}}
        </ul>
      {{else}}
        <div class="alert alert-warning">
          No existen líneas todavía para esta empresa
        </div>
      {{/if}}
    </script>
	
    <!-- JavaScript -->
    <script src="static/js/libs/jquery.min.js"></script>
    <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCNOS0iZYdnhM-3pbOwV-z8f-3NfRG2zM0&sensor=true&libraries=places">
    </script>
    <script src="static/js/libs/bootstrap.min.js"></script>
    <script src="static/js/libs/handlebars-v1.1.2.js"></script>
    <script src="static/js/app.js"></script>
  </body>
</html>