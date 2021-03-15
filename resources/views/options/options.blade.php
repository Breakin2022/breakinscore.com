@extends('layouts.header')
@section('title','Competitive breakin League')
@section('current-page','Options')
@section('main-section')

<div id="content-panel">
  <div class="row">
    @if(Session::has('status'))
    <div class="panel
        @if (Session::has('alert'))
          {{ Session('alert') }}
        @endif
    ">
      <div class="panel-body" style="padding-top:10px;">
        <div id="alertbox" class="col-md-4 col-xs-12">
          <p>{{Session::get('status')}}</p>
        </div>
      </div>
    </div>
    @endif
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
          <div class="panel-heading">
              <h3 style="margin-bottom: 26px;"> <span class="pull-left">Options</span></h3>
          </div>
          <div class="panel-body m-t-0">
          <form method="POST" action="{{ route('options.store') }}" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="website_title">Website Title</label>
                  <input type="text" class="form-control" id="website_title" name="website_title" value="{{ $website_title }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="website_logo">Website Logo</label>
                    @if ($website_logo)
                    <br>
                    <img 
                      src="storage/app/{{ $website_logo }}"
                      alt="Logo"
                      style="max-width:120px; max-height:80px; background: #eee;"
                      id="logo"
                    />
                    <a class="btn btn-xs bg-purple" id="add_logo"><span>New Logo</span></button></a>
                    @else
                    <input type="file" class="form-control-file" id="website_logo" name="website_logo">
                    @endif
                    
                </div>
                <div class="form-group col-md-4">
                  <label for="website_identity">Website Identity</label>
                  <select class="form-control" id="website_identity" name="website_identity">
                    <option value="both" @if ($website_identity == 'both') selected @endif>Both</option>
                    <option value="logo" @if ($website_identity == 'logo') selected @endif>Logo Only</option>
                    <option value="title" @if ($website_identity == 'title') selected @endif>Title Only</option>
                  </select>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="scoreboard_layout">Scoreboard Layout</label>
                  <select class="form-control" id="scoreboard_layout" name="scoreboard_layout">
                    <option value="style_1" @if ($scoreboard_layout == 'style_1') selected @endif>Style 1</option>
                    <option value="style_2" @if ($scoreboard_layout == 'style_2') selected @endif>Style 2</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="website_background">Background Image</label>
                  @if ($website_logo)
                    <br>
                    <img 
                      src="storage/app/{{ $website_background }}"
                      alt="Background Image"
                      style="max-width:120px; max-height:80px; background: #eee;"
                      id="website_background_image"
                    />
                    <a class="btn btn-xs bg-purple" id="add_website_background"><span>New Background</span></button></a>
                    @else
                    <input type="file" class="form-control-file" id="website_background" name="website_background">
                    @endif
                </div>
                <div class="form-group col-md-4">
                  <label for="website_background_status">Background Status</label>
                  <select class="form-control" id="website_background_status" name="website_background_status">
                    <option value="show" @if ($website_background_status == 'show') selected @endif>Enable</option>
                    <option value="hide" @if ($website_background_status == 'hide') selected @endif>Disable</option>
                  </select>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="sponsors_slider">Sponsors Slider</label>
                  <select class="form-control" id="sponsors_slider" name="sponsors_slider">
                    <option value="show" @if ($sponsors_slider == 'show') selected @endif>Show</option>
                    <option value="hide" @if ($sponsors_slider == 'hide') selected @endif>Hide</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="sponsors_slider_width">Sponsors Slider Width <small>[0% - 100%]</small></label>
                  <input type="number" class="form-control" id="sponsors_slider_width" name="sponsors_slider_width" value="{{ $sponsors_slider_width }}">
                </div>
                <div class="form-group col-md-4">
                  <label for="vs_scoreboard">vs Scoreboard</label>
                  <select class="form-control" id="vs_scoreboard" name="vs_scoreboard">
                    <option value="style_1" @if ($vs_scoreboard == 'style_1') selected @endif>Style 1</option>
                    <option value="style_2" @if ($vs_scoreboard == 'style_2') selected @endif>Style 2</option>
                  </select>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="privacy_policy">Privacy Policy</label>
                  <textarea id="editor" name="privacy_policy">{{ $privacy_policy }}</textarea>
                </div>
              </div>
              <button type="submit" class="btn btn-md bg-purple"><span>Submit</span></button>
            </form> <!-- /form -->
          </div>  
        </div>
      </div>
    </div>

  </div><!-- container-fluid end -->
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
<script type="text/javascript" src="{{URL::asset('public/js/jQuery.style.switcher.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('public/js/jquery-functions.js')}}"></script>
<script src="https://cdn.tiny.cloud/1/0ognb3a1sccygu8852cmqeaxh6oud8cs6t4pnh3brxmgnd5a/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<script>
  tinymce.init({
    selector: 'textarea#editor',
    menubar: false
  });
</script>
<script>
$(document).ready(function() {
  $(document).on('click','#add_logo', function(){
    if(confirm("You are about to upload new logo!")){
      $('img#logo').after('<hr><input type="file" class="form-control-file" id="website_logo" name="website_logo">');
      $(this).remove();
    }
  });
  $(document).on('click','#add_website_background', function(){
    if(confirm("You are about to upload new Background!")){
      $('img#website_background_image').after('<hr><input type="file" class="form-control-file" id="website_background" name="website_background">');
      $(this).remove();
    }
  });
  function delteam(id){
    if (confirm(" By Removing This Team will also delete all matches in which this team has been added !\nAre you sure you want to remove this team ?\n")) {
      $.ajax({
        url: "teams/"+ id,
        type: 'delete',
        // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
        data: {
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
});
</script>
</body>
</html>

@endsection