@extends('layouts.header')
@section('title','Competitive breakin League')
@section('current-page','Dashboard')
@section('main-section')

  <!-- //////////////////////////////////////////////////// Content-Panel div -->
  <div id="content-panel">
  <div class="container-fluid">





  <div class="row">
          @if(Session::has('status'))
          <div class="panel">
            <div class="panel-body" style="padding-top:10px;">
          <div id="alertbox" class="col-md-4 col-xs-12">
              <p>{{Session::get('status')}}</p>
          </div>
        </div>
      </div>
          @endif
          @php
            // dd($participant);
           @endphp
          <!-- //////////////////////////////////////////////////// Bar Chart -->
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
            <div class="panel-heading">
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">Edit Participant</span></h3>
            </div> <!-- /panel-heading -->
            <div class="panel-body m-t-0">

            <form method="POST" action="{{route('participant.update', $participant[0]->id)}}" enctype="multipart/form-data">
            {{method_field('PUT')}}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="name">Name</label>
                          <input type="text" class="form-control" id="name" value="{{$participant[0]->name}}" name="name" required="">
                      </div>
                </div>
            <div class="row">
                    <div class="form-group col-md-4">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="{{$participant[0]->email}}" name="email" >
                    </div>
              </div>
            <div class="row">
                    <div class="form-group col-md-4">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" value="{{$participant[0]->phone}}" name="phone" >
                    </div>
              </div>
              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="nick">Nick Name</label>
                          <input type="text" class="form-control" id="nick" value="{{$participant[0]->nick}}" name="nick" >
                      </div>
              </div>
              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="address">Address</label>
                          <input type="text" class="form-control" id="address" name="address" value="{{$participant[0]->address}}">
                      </div>
              </div>
              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="join_date">Joining Date</label>
                          <input type="date"  class="form-control" class="datepicker" id="join_date" value="{{
                            date("Y-m-d", strtotime($participant[0]->join_date))
                          }}" name="join_date">
                      </div>
              </div>
              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="dob">Date of Birth</label>
                          <input type="text"  class="form-control" class="datepicker" id="dob" value="{{
                            date("Y-m-d", strtotime($participant[0]->dob))
                          }}" name="dob">
                      </div>
              </div>
              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="country">Country</label>
                          <select class="form-control" name="country">
                            @foreach ($countries as $country)
                              <option
                              @if ($participant[0]->country == $country)
                                selected
                              @endif
                               value="{{$country}}">{{$country}}</option>
                            @endforeach
                          </select>
                          {{-- <input type="text" class="form-control" id="country" name="country" required=""> --}}
                      </div>
              </div>
              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="image">Image</label>
                          <input type="file" class="form-control" id="image" name="aimage"  >
                      </div>

              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <img src="/{{$participant[0]->image}}" alt="" class="img-responsive">
                </div>
              </div>
              <button type="submit" class="btn btn-md bg-primary"><span>Submit</span></button>

              </form> <!-- /form -->

             </div> <!-- /panel-body -->
            </div> <!-- /panel-->

          </div> <!-- /col -->



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
    $("#dob").flatpickr();
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
