<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Competitive breakin League - Login</title>

    <!-- Styles -->
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('public/js/jquery.min.js')}}" type="text/javascript">    </script>
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<script type="text/javascript">
$(window).load(function() {

var theWindow        = $(window),
    $bg              = $("#bg"),
    aspectRatio      = $bg.width() / $bg.height();

function resizeBg() {

  if ( (theWindow.width() / theWindow.height()) < aspectRatio ) {
      $bg
        .removeClass()
        .addClass('bgheight');
  } else {
      $bg
        .removeClass()
        .addClass('bgwidth');
  }

}
theWindow.resize(resizeBg).trigger("resize");

});
</script>
<style media="screen">
.login-main{
  padding-top:10px;
  padding-left: 10px;
  padding-right: 10px;
   position: absolute;left: 36%;top: 20%; background-color:rgba(234, 234, 234, 0.85);
}

#bg { position: fixed; top: 0; left: 0; }
.bgwidth { width: 100%; }
.bgheight { height: 100%; }
@media (max-width: 991px) {
  #login-div{
    width: 40%;
    left: 32%;
  }
}
@media (max-width: 768px) {
  #login-div{
    width: 45%;
    left: 30%;
    }
}
@media (max-width: 600px){
#login-div {
    width: 50%;
    left: 23%;
}
}
@media (max-width: 490px)
{
  #login-div {
    width: 63%;
    left: 18%;
  }
}
@media (max-width: 490px)
{
  #login-div {
    width: 63%;
    left: 18%;
  }
  .login-main {
    top: 9%;
}
</style>
<body>
    <div id="app">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
