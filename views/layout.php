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
          	<a href="#">
          		<i class="fa fa-building-o"></i> Empresas de transporte
          	</a>
          </li>
          <li>
            <a class="searchToggle" href="#">
              <i class="fa fa-search"></i> Buscar recorrido
            </a>
            <!-- Search form -->
            <form class="form-sidebar">
              <div class="form-group">
                <label for="start">
                  <i class="fa fa-map-marker"></i> Dirección inicial
                </label>
                <input type="text" class="form-control" id="start" placeholder="Mendoza Sur 360">
                <input type="hidden" name="startGeo" id="startGeo">
              </div>
              <div class="form-group">
                <label for="end">
                  <i class="fa fa-flag-checkered"></i> Dirección final
                </label>
                <input type="text" class="form-control" id="end" placeholder="Libertador Oeste 2420">
                <input type="hidden" name="endGeo" id="endGeo">
              </div>
              <button class="btn btn-default">
                Buscar
              </button>
            </form>
          </li>
        </ul>

        <!-- Search results -->
        <div class="results">
          <h4>Resultados</h4>
          <div class="media">
            <a class="pull-left" href="#">
              <img height="50px" class="media-object thumbnail" src="https://fbcdn-sphotos-f-a.akamaihd.net/hphotos-ak-frc3/417814_174169409408323_1027566801_n.jpg" >
            </a>
            <div class="media-body">
              <h4 class="media-heading">50 <small>Empresa Mayo S.R.L.</small></h4>
              <button class="btn btn-xs btn-default">
                <i class="fa fa-road"></i> Ver recorrido
              </button>
            </div>
          </div>

          <div class="media">
            <a class="pull-left" href="#">
              <img height="50px" class="media-object thumbnail" src="https://fbcdn-sphotos-f-a.akamaihd.net/hphotos-ak-frc3/417814_174169409408323_1027566801_n.jpg" >
            </a>
            <div class="media-body">
              <h4 class="media-heading">15 <small>Empresa Mayo S.R.L.</small></h4>
              <button class="btn btn-xs btn-default">
                <i class="fa fa-road"></i> Ver recorrido
              </button>
            </div>
          </div>

        </div>

      </div>
          
      <!-- Page content -->
      <div id="page-content-wrapper">
      	<a id="menu-toggle" href="#" class="btn btn-default"><i class="fa fa-bars"></i></a>
      	<div id="map_canvas"></div>
        <!-- Keep all page content within the page-content inset div! -->
        <div class="page-content">
          <!-- <div class="row">
            <div class="col-md-12">
              <p class="lead">This simple sidebar template has a hint of JavaScript to make the template responsive. It also includes Font Awesome icon fonts.</p>
            </div>
            <div class="col-md-6">
              <p class="well">The template still uses the default Bootstrap rows and columns.</p>
            </div>
            <div class="col-md-6">
              <p class="well">But the full-width layout means that you wont be using containers.</p>
            </div>
            <div class="col-md-4">
              <p class="well">Three Column Example</p>
            </div>
            <div class="col-md-4">
              <p class="well">Three Column Example</p>
            </div>
            <div class="col-md-4">
              <p class="well">You get the idea! Do whatever you want in the page content area!</p>
            </div>
          </div>
        </div> -->
      </div>
      
    </div>
	
    <!-- JavaScript -->
    <script src="static/js/libs/jquery.min.js"></script>
    <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCNOS0iZYdnhM-3pbOwV-z8f-3NfRG2zM0&sensor=true&libraries=places">
    </script>
    <script src="static/js/libs/bootstrap.min.js"></script>

    <!-- Custom JavaScript for the Menu Toggle -->
    <script src="static/js/libs/handlebars-v1.1.2.js"></script>
    <script src="static/js/app.js"></script>
  </body>
</html>