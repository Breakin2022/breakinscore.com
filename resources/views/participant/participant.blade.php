@extends('layouts.header')
@section('title','Competitive Breakin League')
@section('current-page','Dashboard')
@section('main-section')

  <div id="content-panel">
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

  <div class="container-fluid">
    <div class="row" id="mbox" style="display:none;">
      <div class="col-sm-12" >
        <div class="panel">
          <div class="panel-body" id="message" style="color:black;padding:15px;">

          </div>
        </div>


      </div>
    </div>
  <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
            <div class="panel-heading">
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">Participants</span> <span> <a href="{{route('participant.create')}}" class="btn btn-primary btn-xs pull-right">add participant</a> </span></h3>
            </div>
            <div class="panel-body m-t-0">
            <div class="table-responsive">
            <table id="customer-list" class="table table-striped" >
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Nick Names</th>
                    <th>Address</th>
                    <th>joining date</th>
                    <th>
                      Rank
                    </th>
                    <th>
                      DOB
                    </th>
                    <th>Action</th>
                </tr>
            </thead>
                <tbody>
                     @php
                     $participants = App\Participant::all();
                     $i = 1;

                     @endphp
                     @foreach($participants as $participant)
                    <tr id="del{{$participant->id}}">
                        <td>{{$i++}}</td>
                        <td>{{$participant->name}}</td>
                        <td>{{$participant->email}}</td>
                        <td>{{$participant->phone}}</td>
                        <td>{{$participant->nick}}</td>
                        <td>{{$participant->address}}</td>
                        <td>{{$participant->join_date}}</td>
                        <td >
                          @if ($participant->rank != null)
                            {{$participant->rank->rank}}
                          @else
                            -
                          @endif
                        </td>
                        <td>{{$participant->dob}}</td>
                        <td>
                        <div class="btn-group">
                          <a class="btn btn-xs btn-edit bg-purple hidden-xs hidden-sm" href="{{route('participant.edit', $participant->id)}}" ><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <a class="btn btn-delete btn-xs bg-red hidden-xs hidden-sm" href="#" onclick="delparticipant({{$participant->id}})"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </div>
                            <div class="btn-group table-editor visible-xs visible-sm">
                            <button type="button" class="btn bg-purple circle-xs dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-caret-down"></i></button>
                                <ul class="dropdown-menu slidedown">
                                    <li><a href="{{route('participant.edit', $participant->id)}}"> Edit</a></li>
                                    <li><a href="#" onclick="delparticipant({{$participant->id}})"> Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                  @endforeach
                    </tbody>
                    </table>
                </div>
                </div>
            </div>

          </div>

  </div>
  </div>
  </div>
  <script  type="text/javascript" src="{{URL::asset('public/js/bootstrap.min.js')}}"></script>

  <script  type="text/javascript" src="{{URL::asset('public/js/menu/metisMenu.min.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/menu/nanoscroller.js')}}"></script>
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
  $("#mbox").hide();
  function delparticipant(id){

    if (confirm("Are you sure you want to remove this participant ?")) {
      $.ajax({
        url: "participant/"+ id,
        type: 'delete',
        data: {_method: 'delete', _token :$('#token').val()}
      })
      .done(function(data) {
        // message
        if (typeof data == Object || data.constructor === Array) {
          $("#mbox").show();
          $("#message").html('Please remove these teams before removing this participant.<br>');
          data.forEach(function(d){
            $("#message").html( $("#message").html() + d.name + "<br>");
          });
        }else {
          $("#mbox").hide();
          $("#del"+data).remove();
        }

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
