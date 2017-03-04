<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('aimeos_header')
    <title>Manenblussers Fashion</title>
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    @yield('aimeos_styles')
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Manenblussers Fashion</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="/">Home</a></li>
            </ul>
            <div class="navbar-right">
                @yield('aimeos_head')
            </div>
        </div>
    </div>
</nav>

<div class="container">
    <div class="col-xs-12">

        @yield('aimeos_nav')
        @yield('aimeos_stage')
        @yield('aimeos_body')
        @yield('aimeos_aside')
        @yield('content')
        @yield('aimeos_footer')
    </div>
</div>

<div class="clearfix"></div>
<div style="background-color: #e7e7e7">
    <hr style="border: 1px solid black">
    <div class="container">
        <div class="row">
            <br>

            <!-- Site navigation -->
            <div class="col-sm-6 col-md-4">
                <h4 class="h4">Site navigatie</h4>
                <ul style="list-style: none">
                    <li><a href="/list">Home</a></li>
                    <li><a href="/terms">Algemene voorwaarden</a></li>
                    <li><a href="/privacy">Uw privacy gegarandeerd</a></li>
                </ul>
            </div>

            <!-- Social media links -->
            <div class="col-sm-6 col-md-4">
                <h4 class="h4">Social media</h4>
                <ul style="list-style: none">
                    <li>Instagram</li>
                    <li>Facebook</li>
                    <li>Twitter</li>
                    <li>SnapShat</li>
                    <li>YouTube</li>
                </ul>
            </div>

            <!-- Social media links -->
            <div class="col-sm-6 col-md-4">
                <h4 class="h4">Newsletter</h4>
                <form class="form">
                    <label>Uw e-mail adres:
                        <input type="email" class="form-control" name="email">
                    </label>
                    <input type="submit" class="btn btn-primary" value="Inschrijven">
                </form>
            </div>

        </div>

        <div style="min-height: 75px">&nbsp;</div>
    </div>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
@yield('aimeos_scripts')
</body>
</html>