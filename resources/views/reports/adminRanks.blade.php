@extends('layouts.header')
@section('title','Competitive breakin League')
@section('current-page','Ranking')
@section('main-section')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchpanes/1.2.1/css/searchPanes.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.8/css/fixedHeader.dataTables.min.css">
<div id="content-panel">
  <!-- Not in use status -->
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
  <!-- Not in use status End-->

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
          <div class="panel-heading">
            <h3><span class="pull-left">Ranking</span>
            </h3>
          </div>
          <div class="panel-body m-t-0">
            <div class="row panel-body">
              <div class="table-responsive">
                <table id="raknings" class="table table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th for="competitionName">Competition</th>
                      <th for="competitionType">Type</th>
                      <th for="matchName">Match</th>
                      <th for="teamName">Team</th>
                      <th for="teamRank">Team Rank</th>
                      <th for="teamAgeGroup">Team Age Group</th>
                      <th for="participantName">Participant</th>
                      <th for="participantRank">Participant Rank</th>
                      <th for="participantAgeGroup">Participant Age Group</th>
                      <th for="updated_at">Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($Rankings as $Ranking)
                    <tr id="{{ $Ranking->id }}">
                      <td>{{ $loop->iteration }}</td>
                      <td competitionId="competitionId_{{ $Ranking->competitionId }}">{{ $Ranking->competitionName }}</td>
                      <td competitionType="competitionType_{{ $Ranking->competitionType }}">
                        {{ $Ranking->competitionType }} vs {{ $Ranking->competitionType }}
                      </td>
                      <td matchId="matchId_{{ $Ranking->matchId }}">{{ $Ranking->matchName }}</td>
                      
                      <td teamId="teamId_{{ $Ranking->teamId }}">{{ $Ranking->teamName }}</td>
                      <td teamId="teamId_{{ $Ranking->teamId }}">
                        <a class="btn btn-primary btn-xs edit-score bg-purple">
                          {{ $Ranking->teamRank }}
                        </a>
                      </td>
                      <td teamId="teamId_{{ $Ranking->teamId }}" teamAgeGroup="teamAgeGroup_{{ $Ranking->teamAgeGroup }}">
                        @if ( $Ranking->teamAgeGroup == 1 )
                          5 - 12
                        @elseif ( $Ranking->teamAgeGroup == 2 )
                          13 - 18
                        @else
                          18+
                        @endif      
                      </td>

                      <td participantId="participantId_{{ $Ranking->participantId }}">{{ trim($Ranking->participantName) }}</td>
                      <td participantId="participantId_{{ $Ranking->participantId }}">
                        <a class="btn btn-primary btn-xs edit-score bg-purple">
                          {{ $Ranking->participantRank }}
                        </a>
                      </td>
                      <td participantId="participantId_{{ $Ranking->participantId }}" participantAgeGroup="participantAgeGroup_{{ $Ranking->participantAgeGroup }}">
                        @if( $Ranking->participantAgeGroup == 1 )
                          5 - 12
                        @elseif( $Ranking->participantAgeGroup == 2 )
                          13 - 18
                        @else
                          18+
                        @endif      
                      </td>
                      <td>{!! htmlspecialchars_decode(date('jS F Y', strtotime($Ranking->updated_at))) !!}</td>
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
</div>

<script  type="text/javascript" src="{{URL::asset('public/js/bootstrap.min.js')}}"></script>
<!-- Data Tables Script -->
<script type="text/javascript" src="{{URL::asset('public/js/datatables/datatables.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/searchpanes/1.2.1/js/dataTables.searchPanes.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js"></script>

<script  type="text/javascript" src="{{URL::asset('public/js/menu/metisMenu.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('public/js/menu/nanoscroller.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('public/js/jquery-functions.js')}}"></script>
<script>
$(document).ready(function() {
  $('#raknings thead tr').clone(true).appendTo( '#raknings thead' );
  $('#raknings thead tr:eq(1) th:nth-child(2), #raknings thead tr:eq(1) th:nth-child(4), #raknings thead tr:eq(1) th:nth-child(5), #raknings thead tr:eq(1) th:nth-child(8), #raknings thead tr:eq(1) th:nth-child(11)').each( function (i) {
      var title = $(this).text();
      $(this).html( '<input type="text" class="form-control" placeholder="'+title+'" />' );

      $( 'input', this ).on( 'keyup change', function () {
          if ( dt.column(i).search() !== this.value ) {
              dt
                  .column(i)
                  .search( this.value )
                  .draw();
          }
      } );
  } );
  jQuery('#raknings thead tr:last-child th:nth-child(1), #raknings thead tr:last-child th:nth-child(3), #raknings thead tr:last-child th:nth-child(6), #raknings thead tr:last-child th:nth-child(7), #raknings thead tr:last-child th:nth-child(9), #raknings thead tr:last-child th:nth-child(10)').html('');
  var dt = $('#raknings').DataTable({
    orderCellsTop: true,
    fixedHeader: true,
    searchPanes: {
      viewTotal: true,
      columns: [1, 4, 7, 9]
    },
    dom: 'Plfrtip',
    columnDefs: [{
      searchPanes: {
        options: [
          @foreach($Competitions as $Competition)
          {
            label: '{{ $Competition->title }}',
            value: function (rowData, rowIdx) {
              return rowData[1] == '{{ $Competition->title }}';
            }
          },
          @endforeach
        ]
      },
      targets: [1]
    },
    {
      searchPanes: {
        options: [
          @foreach($teams as $team)
          {
            label: '{{ $team->name }}',
            value: function (rowData, rowIdx) {
              return rowData[4] == '{{ $team->name }}';
            }
          },
          @endforeach
        ]
      },
      targets: [4]
    },
    {
      searchPanes: {
        options: [
          @foreach($participants as $participant)
          {
            label: '{{ trim($participant->name) }}',
            value: function (rowData, rowIdx) {
              return rowData[7] == '{{ trim($participant->name) }}';
            }
          },
          @endforeach
        ]
      },
      targets: [7]
    },
    {
      searchPanes: {
        options: [{
            label: '18+',
            value: function (rowData, rowIdx) {
              return rowData[9] == '18+';
            }
          },
          {
            label: '13 - 18',
            value: function (rowData, rowIdx) {
              return rowData[9] == '13 - 18';
            }
          },
          {
            label: '5 - 12',
            value: function (rowData, rowIdx) {
              return rowData[9] == '5 - 12';
            }
          }
        ]
      },
      targets: [9]
    }],
    select: {
      style: 'os',
      selector: 'td:first-child'
    }
  });

  dt.on('select.dt', () => {
    dt.searchPanes.rebuildPane(0, true);
  });

  dt.on('deselect.dt', () => {
    dt.searchPanes.rebuildPane(0, true);
  });
});
</script>

</body>
</html>
@endsection