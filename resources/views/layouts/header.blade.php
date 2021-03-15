<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="description" content="Competitive breakin League">
    <meta name="author" content="al-burraq">
    <title>Competitive Breakin League</title>

    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/custom.css')}}">
    <link rel="stylesheet" id="jssDefault" type="text/css" href="{{URL::asset('public/css/theme-purple.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/morris/morris.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/nanoscroller.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/datatables/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/vmap/jqvmap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/daterangepicker/daterangepicker.css')}}">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style media="screen">
      .dataTables_wrapper .dataTables_paginate .current,
      #content-panel .btn-chat{
        background-color: #CB132D !important;
      }
      .dataTables_wrapper .dataTables_paginate .current{
        background: #CB132D;
        border: 1px solid #CB132D;
      }
      .dataTables_wrapper .dataTables_paginate .paginate_button:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover{
        background-color: #CB132D;
        border-color: #CB132D;
      }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
<div id="header-panel">
<nav class="navbar navbar-fixed-top">
<div class="container-fluid">
    <div id="navbar-header">
    <a class="navbar-brand" href="{{route('home')}}">
        <span class="logo-img" style="font-size"><!-- logo img <img src="img" alt=""> -->H</span>
        <span style="    font-size: 14px;font-weight: bold;" class="logo-text hidden-xs hidden-sm"> Competitive Breakin League</span>
    </a>
    <ul class="nav navbar-nav">
    <li class="btn-menu hidden-xs hidden-sm"> <a id="menu-toggle" href="#" class="toggle"></a> </li>
    <li class="btn-menu hidden-md hidden-lg"> <a id="mobile-menu-toggle" href="#" class="toggle"></a> </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
        <li class="dropdown user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="{{URL::asset('public/img/admin.jpg')}}" alt="" class="profile-img img-circle img-resposnive pull-left">
        @if (!Auth::user())
            @php
              Redirect::to(route('login'));
            @endphp
        @endif
        <span class="hidden-xs">{{ Auth::user()->name }}</span> <span class="caret"></span></a>
        <ul class="dropdown-menu pull-right">
            {{-- <li><a href="#"><i class="fa fa-user" aria-hidden="true"></i>Profile</a></li> --}}
            <li style="margin: 0 auto ; text-align:center;"><a  href="{{ url('/logout') }}" style="width: 100%;"   onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();"><i class="fa fa-sign-out" aria-hidden="true"></i>
              Log out
            </a>
            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
          </li>
        </ul>
        </li>
    </ul>
    </div>
    </div>
</nav>
</div>
<div id="navigation-panel" class="">
    <nav class="sidebar nano">
    <div class="clearfix"></div>
    <div id="#sidebar-navbar" class="sidebar-nav nano-content navbar-collapse ">
    <ul class="nav" id="side-menu">
    <li><a href="{{route('home')}}"  {{{ (Request::is('home') ? 'class=active' : '') }}}><i class="fa fa-home" aria-hidden="true"></i> <span class="link-hide"> Dashboard </span></a></li>
    <li><a href="{{route('participant.index')}}" ><i class="fa fa-user" aria-hidden="true"></i> <span class="link-hide">Participant</span></a></li>
    <li><a href="{{route('teams.index')}}" ><i class="fa  fa-trophy " aria-hidden="true"></i> <span class="link-hide"> Teams </span></a></li>
    <li><a href="{{route("judges.index")}}" ><i class="fa fa-user" aria-hidden="true"></i> <span class="link-hide"> Judges </span></a></li>
    <li><a href="{{route("competitionVenue.index")}}" ><i class="fa   fa-building-o" aria-hidden="true"></i> <span class="link-hide"> Competition </span></a></li>
    <li><a href="{{route("api-getCompetition")}}" ><i class="fa fa-building-o" aria-hidden="true"></i> <span class="link-hide">Live Competition </span></a></li>
    <li><a href="{{route("sponsors.index")}}" ><i class="fa  fa-users" aria-hidden="true"></i> <span class="link-hide"> Sponsors </span></a></li>
    <li><a href="{{route("criteria.index")}}" ><i class="fa  fa-users" aria-hidden="true"></i> <span class="link-hide"> Criteria </span></a></li>

    <li><a href="#" ><i class="fa fa-star-half-o" aria-hidden="true"></i> <span class="link-hide"> Participants Ranking </span></a></li>
    <li><a href="#" ><i class="fa fa-star-half-o" aria-hidden="true"></i> <span class="link-hide"> Teams Ranking </span></a></li>
    <li><a href="{{route("options.index")}}" ><i class="fa fa-cog" aria-hidden="true"></i> <span class="link-hide"> Options </span></a></li>
    </ul>
    </div>
    </nav>
</div>
<script type="text/javascript">
$.ajaxSetup({
     headers: {
         'X-CSRF-TOKEN': "{{csrf_token()}}"
     }
   });

</script>
@yield('main-section')
@yield('footer')
