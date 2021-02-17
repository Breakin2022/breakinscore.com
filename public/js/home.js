
function mw(s){
    $(s).css('background','white');
    $(s).css('color','#2552a6');
}

function mw1(){
  $(".box-inner-1-border-start").css('background','white');
  $(".box-inner-1-border").css('border-color','white');
  $(".box-inner-1-border-end").css('background','white');
}
function mw2(){
  $(".box-inner-2-border-start").css('background','white');
  $(".box-inner-2-border").css('border-color','white');
  $(".box-inner-2-border-end").css('background','white');
}
function mw3(){
  $(".box-inner-3-border-start").css('background','white');
  $(".box-inner-3-border").css('border-color','white');
  $(".box-inner-3-border-end").css('background','white');
}
function mw1r(){
  $(".box-inner-1-border-start-r").css('background','white');
  $(".box-inner-1-border-r").css('border-color','white');
  $(".box-inner-1-border-end-r").css('background','white');
}
function mw2r(){
  $(".box-inner-2-border-start-r").css('background','white');
  $(".box-inner-2-border-r").css('border-color','white');
  $(".box-inner-2-border-end-r").css('background','white');
}
function mw3r(){
  $(".box-inner-3-border-start-r").css('background','white');
  $(".box-inner-3-border-r").css('border-color','white');
  $(".box-inner-3-border-end-r").css('background','white');
}


var rightBoxes = Array();
var leftBoxes = Array();
for(var i = 0;i <= 7;i++ ){
  rightBoxes.push(
    'box-inner-'+i
  );
  leftBoxes.push(
    'box-inner-'+i+'-r'
  );
}
$(".overlay").width(document.height);
$(".overlay").width(window.width);
