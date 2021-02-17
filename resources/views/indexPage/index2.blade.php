<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Competitive Breakin League</title>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/app.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/main.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/jquery.bxslider.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

</head>
<body>
      <div id="app">
      <img src="http://tony.al-burraq.com/mainscreenbg.jpg" id="bg" alt="">
      <div id="bg" style="width:100%;height:100%; background: rgba(0,0,0,0.7); ">

          <div class="row">
            <div class="col-md-12">
              <div id="login-div" class="col-md-3 login-main">
                <img src="http://tony.al-burraq.com/adminPanel/public/img/logo.jpg" style="width:100%;" alt="">
              </div>
            </div>
          </div>
        <div id="main" class="container">
        <div class="row">
          <div class="col-md-12">
            <div id="uniqeboxay" class="col-md-4 col-md-offset-4 ">
              <select  id="selectboxMain" class="selectBoxStyle form-control  m-t-10 ">
                          @foreach ($BigCollection as $competition)
                            <option value="{{$competition['id']}}">{{$competition['title']}}</option>
                          @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
                <div id="mycustomecountdown" class="col-md-3 col-md-offset-4 hidden text-center mycustomecountdown">
                            10
                </div>
                <table id="mytable" class="table">
                  <thead>
                    <tr>
                      <th class="teamsLabelColor"> <strong> Teams</strong></th>
                      <th id="blueTeamHeading">Blue Team</th>
                      <th id="redTeamHeading" >Red Team</th>
                    </tr>
                  </thead>
                  <tbody id="tableBody">
                    @php
                      $competitionI = 0;
                    @endphp
                    @foreach ($BigCollection as $competition)
                      @php
                        $competitionId = $competition['id'];
                      @endphp

                      @foreach ($competition['matches'] as $match)
                      <tr class="allCommonClass competitionBody{{$competitionId}}" >
                        <td class="teamsLabelColor">{{ $match['redTeamTitle'] }} VS {{$match['blueTeamTitle']}}</td>
                        <td class="blueTeamColor">{{$match['blueTeamScore']}}</td>
                        <td class="redTeamColor">{{$match['redTeamScore']}}</td>
                      </tr>
                      @endforeach

                @endforeach
                  </tbody>
                 </table>
             </div>
           </div>
         </div>

         <div class="row">
            <div class="col-md-12 text-center">
              <div class="col-md-offset-3 sliderContainer">

              <ul class="bxslider" id="mySliderSponsers">
                {{-- <li><img src="/images/pic1.jpg" /></li>
                <li><img src="/images/pic2.jpg" /></li>
                <li><img src="/images/pic3.jpg" /></li>
                <li><img src="/images/pic4.jpg" /></li> --}}
                @foreach ($sponsers as $sponser)
                  <li>
                    <div class="SliderTitle bg-primary">
                      {{$sponser->title}}
                    </div>
                    <img class="img-responsive" src="{{$sponser->image}}" alt="">
                  </li>
                @endforeach
              </ul>
            </div>

            </div>
         </div>

       </div>
        {{-- 1 or div end here --}}

      <div class="col-md-3 footer"  style="     width: 97%;text-decoration:none;">
            <a target="_blank" href="https://web.facebook.com/Competitive-Breakin-League-726213087473439" class="fb">
             <span style="color: white;" class="fa fa-facebook"></span>
           </a>
           <a  target="_blank"  href="#" class="insta">
            <span style="color: white;" class="fa fa-instagram"></span>
          </a>
           <a  target="_blank"  href="https://twitter.com/RTBcompetition" class="twiter">
            <span style="color: white;" class="fa fa-twitter"></span>
          </a>
        </div>
    {{-- </div> --}}
  </div>
</div>
<script src="{{URL::asset('public/js/custome.js')}}" charset="utf-8"></script>
<script src="{{URL::asset('public/js/jquery.bxslider.min.js')}}" charset="utf-8"></script>


<script type="text/javascript">
$(document).ready(function(){
  $('#mySliderSponsers').bxSlider({
    slideWidth: 200,
      minSlides: 1,
      maxSlides: 3,
      slideMargin: 10,
          controls: false,
    auto: true
  });
});
</script>
<script type="text/javascript">
$(document).ready(function() {
  target = $("#selectboxMain");
  $(target).ready(function(){
    id = $(target).val();
    $('.allCommonClass').addClass('hidden');
    currentIndexWanted = getCookie("currentIndex");
    if (currentIndexWanted == "") {
      currentIndexWanted = id;
    }
    $(target).val(currentIndexWanted);
    $('.competitionBody' + currentIndexWanted).removeClass('hidden');
  });
  $(target).change(function(event) {
    id = $(target).val();
    $('.allCommonClass').addClass('hidden');
    $('.competitionBody' + id).removeClass('hidden');
    setCookie("currentIndex", id, 1);
  });
});


function displayCurrentFinishedMatch(competionId,matchId){
  $.ajax({
    url: '{{URL::to(route('teamScoreByTeamId'))}}',
    type: 'POST',
    data: {id: selectedId,
        _token: '{{ csrf_token()}}',
        matchId: matchId
        }
  })
  .done(function(data) {
    $("#mytable").removeClass('hidden');
    if (data == "false") {
      console.log("no event");
      return;
    }
    if (firstFlage == false) {
      // existingCSS = $("#mytable").css();
      existingHtml = $("#mytable").html();
    }
    firstFlage = true;
    if (isDisplayed == false) {

      setDemoTime();
      isDisplayed = true;
      // alert('displayed');
    }
    // newONe = getCookie('a');
    awaisReq = data;
    // setCookie('a',awaisReq,1);
    $("#mytable").html(data);
    $("#mytable").css({
      width: '95%',
      'margin-left': "5%"
    });
    $("#redTeamHeading").addClass('text-center');
    $("#blueTeamHeading").addClass('text-center');

  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    // console.log("complete");
  });
}



</script>
<script type="text/javascript">
  var isDisplayedHain = false;
  var isScoreBeingDispled = false;
  var newInterval1 = setInterval(function(){

  if (isDisplayedHain == true || isScoreBeingDispled == true) {
    return 0;
  }
  selectedtarget = $("#selectboxMain");
  selectedId = $(selectedtarget).val();
  $.ajax({
    url: '{{URL::to(route('notificationAjax'))}}',
    type: 'POST',
    data: {competitionId: selectedId,
      _token: '{{ csrf_token() }}'
    }
  })
  .done(function(data) {
    decodeData = JSON.parse(data);
    if (decodeData.started == '1'){
      isDisplayedHain = true;
      console.log('inside Start');
      mycounternew = $("#mycustomecountdown");
      mytable = $("#mytable");
      $(mytable).addClass('hidden');
      var newCounterInside = 10;
      $(mycounternew).removeClass('hidden');
      var intervalfor10second = setInterval(function(){
        $(mycounternew).html(newCounterInside);
        if (newCounterInside == 0) {
          // alert('yes');
          $(mytable).removeClass('hidden');
          $(mycounternew).addClass('hidden');
          $(mycounternew).html('10');
          isDisplayedHain = false;
          clearInterval(intervalfor10second);
        }
        newCounterInside--;
      }, 1000)
    }
    if (decodeData.finished == '1') {
      mycounternew = $("#mycustomecountdown");
      $(mycounternew).html('3')
      competionId = decodeData.competionId;
      matchId     = decodeData.matchId;
      console.log('inside Finish');
      // alert('finisehd');
      isScoreBeingDispled = true;
      $("#mytable").addClass('hidden');
      var newCounterInside = 3;
      $(mycounternew).removeClass('hidden');
      var intervalfor10second = setInterval(function(){
        $(mycounternew).html(newCounterInside);
        if (newCounterInside == 0) {
          $(mycounternew).html('10');
          isDisplayedHain = false;
          $(mycounternew).addClass('hidden');
          displayCurrentFinishedMatch(competionId,matchId);

          clearInterval(intervalfor10second);
        }
        newCounterInside--;
      }, 1000)

    }
  })

}, 1000);
</script>


<script type="text/javascript">
function setDemoTime(){

  var counter = 0;
  var interval = setInterval(function() {
    newIntervalclear = interval;
    counter++;
    // Display 'counter' wherever you want to display it.
    if (counter == 10) {
        clearInterval(interval);
        $("#mytable").html(existingHtml);
        console.log('yes');
        $("#mytable").css({
          width: '36% !important',
          'margin-left': "31% !important"
        });
        // alert('setDemoTime');
        isDisplayed = false;
        // location.reload
        location.reload();

    }
}, 1000);

}
isDisplayed = false;
firstFlage = false;
existingHtml = '';
existingCSS  = '';










var myMaininterval = window.setInterval(function(){
  return 0;
  selectedtarget = $("#selectboxMain");
  selectedId = $(selectedtarget).val();

  // clearInterval()
  // console.log("yes");
}, 9000);

</script>



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

</body>
</html>
