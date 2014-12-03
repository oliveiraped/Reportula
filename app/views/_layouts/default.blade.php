<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Reportula - Bacula Backups Web Gui">
        <meta name="author" content="Pedro Oliveira">
        <title>Reportula - Bacula Web Gui </title>
        <script type="text/javascript">
            var myPath = '{{ URL::to("/") }}';
            var language = {{ trans('datatables.language') }};
        </script>
         <?php echo Asset::styles(); ?>
         <?php echo Asset::scripts(); ?>
    </head>
    <body>
        <div class="container-fluid">
            @if ( Sentry::check() )
                <!-- Navbar -->
                <style>
                    @section('styles')
                        body {
                            padding-top: 40px;
                        }
                    @show
                </style>
                 <!-- navbar -->
                <div class="navbar navbar-fixed-top">
                    <div class="navbar-inner">
                        <div class="container">
                            <a class="brand" href="{{ URL::route('dashboard', array('data'=>'day')) }}"><img src={{asset('assets/img/logo.png')}} alt="Logo" > Reportula</a>

                                <ul class="nav">
                                    <li><a href="{{ URL::route('dashboard', array('data'=>'day')) }}"><i class="icon-fam-application-view-list"></i> {{ trans('messages.dashboard') }} </a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::route('clients') }}"><i class="icon-fam-drive-key"></i> {{ trans('messages.clients') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::route('jobs') }}"><i class="icon-fam-drive-web"></i> {{ trans('messages.jobs') }}</a></li>
                                    <li>
                                        <a href="{{ URL::route('volumes') }}"><i class="icon-fam-cd-add"></i> {{ trans('messages.volumes') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::route('pools') }}"><i class="icon-fam-database"></i> {{ trans('messages.pools') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::route('stats') }}"><i class="icon-fam-chart-curve-add"></i> {{ trans('messages.statistics') }}</a>
                                    </li>

                                </ul>
                                <ul class="pull-right nav">
                                    <li><a href="{{ URL::route('logout') }}"><i class="icon-fam-house-link"></i> {{ trans('messages.logout') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ./ navbar -->
            @endif
                 @yield('main')
            <hr>
            <footer>
                <?php echo Asset::container('footer')->scripts(); ?>
                <p>
                    <center>Reportula V2.1.0 <?php echo HTML::link('http://www.reportula.org', 'wwww.reportula.org'); ?> &copy; Pedro Oliveira 2013 - 2015 </center>
                </p>
            </footer>
        </div>
    </body>
</html>
