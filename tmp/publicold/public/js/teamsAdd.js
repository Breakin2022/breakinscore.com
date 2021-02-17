function showSelect2(idCombined){
  $(".editBtn"+idCombined).hide();
  $(".teamDiv"+idCombined).hide();
  $(".select2Div"+idCombined).removeClass('hidden');
}
function submitForm(idCombined,oldTeamId,matchId){
  var newTeamId = $("#select2"+idCombined).val();
  $.ajax({
      method: "POST",
      url: "/api/replaceMatchTeam",
      data: { oldTeamId: oldTeamId, newTeamId: newTeamId ,matchId: matchId }
    })
  .done(function( msg ) {
    console.log(msg);
    alert(msg.message );
    location.reload();
  });
}
