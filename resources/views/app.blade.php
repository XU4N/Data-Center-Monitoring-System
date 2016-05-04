<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8'>
    <meta content='IE=edge' http-equiv='X-UA-Compatible'>
    <meta content='width=device-width, initial-scale=1  maximum-scale=1, user-scalable=no' name='viewport'>
      <!-- / The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    </meta>
    <meta content='' name='description'>
    <meta content='' name='author'>
    <title>Data Centre Monitoring System</title>
    <!-- %link{:href => "../../favicon.ico", :rel => "icon"}/ -->
    <link href="/css/simple-sidebar.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="/bootstrap/css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="/css/dashboard.css" media="screen" rel="stylesheet" type="text/css" />
    @yield ('header')
  </head>
  <body>
    @include('partials.nav')
    <div id='wrapper'>
      @include('partials.sidebar')
      <div class='page-content-wrapper'>
        <div class='container-fluid'>
          <div class='row'>
            <div class='col-lg-12'>
                @yield('content')
            </div>
          </div>
        </div>
      </div>
          @yield('modal_form')
    </div>
    <!-- /#wrapper -->
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <!-- Sidebar Menu Toggle Script -->
    <script>
      $("#menu-toggle").click(function(e) {
      	e.preventDefault();
      	$("#wrapper").toggleClass("toggled");
      });
    </script>
    @yield('scripts')
  </body>
</html>