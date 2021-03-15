@extends('layouts.header')
@section('title','Competitive breakin League')
@section('current-page','Dashboard')
@section('main-section')


  <div id="content-panel">
  <div class="container-fluid">
  <div class="row">

          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
            <div class="panel-heading">
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">Sponsors List</span> <span> <a href="{{route('sponsors.create')}}" class="btn btn-primary btn-xs pull-right">add Sponsor</a> </span></h3>
            </div> <!-- /panel-heading -->
            <div class="panel-body m-t-0">
            <div class="table-responsive">
            <table id="customer-list" class="table table-striped" >
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Link</th>
                    <th>Action</th>
                </tr>
            </thead>
                <tbody>
                     @php
                     $sponsors = DB::table('sponsors')->get();
                     $i = 1;
                     @endphp
                     @foreach($sponsors as $sponsor)
                    <tr id="del{{$sponsor->id}}">
                        <td>{{$i++}}</td>
                        <td>
                          <a target="_blank" href="storage/app/{{$sponsor->image}}">
                            <img src="storage/app/{{$sponsor->image}}" alt="{{$sponsor->title}}" width="80" height="60" style="background: #eee" />
                          </a>
                        </td>
                        <td>{{$sponsor->title}}</td>
                        <td><a target="_blank" href="{{$sponsor->link}}" >{{$sponsor->link}}</a></td>
                        <td>
                        <div class="btn-group">
                            <a class="btn btn-xs btn-edit bg-purple hidden-xs hidden-sm" href="{{route('sponsors.edit', $sponsor->id)}}" ><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <a class="btn btn-delete btn-xs bg-red hidden-xs hidden-sm" href="#" onclick="delsponsor({{$sponsor->id}})"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </div>
                            <div class="btn-group table-editor visible-xs visible-sm">
                            <!-- /btn dropdown -->
                            <button type="button" class="btn bg-purple circle-xs dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-caret-down"></i></button>
                                <!-- /dropdown menu slidedown -->
                                <ul class="dropdown-menu slidedown">
                                    <li><a href="{{route('sponsors.edit', $sponsor->id)}}" > Edit</a></li>
                                    <li><a href="#" onclick="delsponsor({{$sponsor->id}})"> Delete</a></li>
                                </ul> <!-- /slidedown -->
                            </div> <!-- /btn-group -->
                        </td>
                    </tr>
                  @endforeach


                    </tbody>
                    </table>
                </div> <!-- /table-responsive -->
                </div> <!-- /panel-body -->
            </div> <!-- /panel-->

          </div> <!-- /col -->

          <!-- //////////////////////////////////////////////////// Statistics -->


  </div> <!-- /row -->




  </div> <!-- /container-fluid -->
  </div> <!-- /content-panel -->

  <!-- /////////////////////////////// Scripts /////////////////////////////// -->
  <!-- Offline jQuery script -->
  <!-- <script  type="text/javascript" src="jquery.min"></script>  -->
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script  type="text/javascript" src="{{URL::asset('public/js/bootstrap.min.js')}}"></script>

  <!-- Menu Script -->
  <script  type="text/javascript" src="{{URL::asset('public/js/menu/metisMenu.min.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/menu/nanoscroller.js')}}"></script>

  <!-- Data Range Picker Script -->
  <script type="text/javascript" src="{{URL::asset('public/js/moment.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/daterangepicker/daterangepicker.js')}}"></script>

  <!-- CountTo Script -->
  <script type="text/javascript" src="{{URL::asset('public/js/countTo/jquery.countTo.js')}}"></script>

  <!-- Morris Chart Script -->
  <script  type="text/javascript" src="{{URL::asset('public/js/morris-js/raphael.min.js')}}"></script>
  <script  type="text/javascript" src="{{URL::asset('public/js/morris-js/morris.min.js')}}"></script>

  <!-- Chart.js Script -->
  <script type="text/javascript" src="{{URL::asset('public/js/chart-js/Chart.js')}}"></script>

  <!-- Flot.js Script -->
  <script type="text/javascript" src="{{URL::asset('public/js/flot-js/excanvas.min.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/flot-js/jquery.flot.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/flot-js/jquery.flot.resize.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/flot-js/jquery.flot.time.js')}}"></script>

  <!-- Data Tables Script -->
  <script type="text/javascript" src="{{URL::asset('public/js/datatables/datatables.min.js')}}"></script>

  <!-- VMap Script -->
  <script type="text/javascript" src="{{URL::asset('public/js/vmap/jquery.vmap.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/vmap/maps/jquery.vmap.usa.js')}}"></script>

  <!-- Dashboard script -->
  {{-- <script type="text/javascript" src="{{URL::asset('public/js/dashboard.js')}}"></script> --}}

  <!-- Theme Configurator script -->
  <script type="text/javascript" src="{{URL::asset('public/js/jQuery.style.switcher.min.js')}}"></script>

  <script type="text/javascript" src="{{URL::asset('public/js/jquery-functions.js')}}"></script>
  <script>

  function delsponsor(id){
    if (confirm("Are you sure you want to remove this sponsor ?")) {
      $.ajax({

        url: "sponsors/"+ id,
        type: 'POST',
        // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
        data: {
          _method: 'delete',
          _token:     '{{ csrf_token() }}'
        }
      })
      .done(function(data) {
        $("#del"+data).remove();
        // console.log(data);
      })
      .fail(function() {
        alert("error");
      })
      .always(function() {
        console.log("complete");
      });

    }

  }
$(document).ready(function() {

    $('#customer-list').DataTable({
        "columnDefs": [{
        "targets": 7,
        "orderable": false
        }]
    });
});

</script>
  </body>

  </html>
@endsection
