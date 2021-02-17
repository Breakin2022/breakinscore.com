<?php
// Index Controller is being used for page that will be main page where score or comming event time will be
// showen

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use DB;
use Session;
use App\Helpers\Helpers as Helper;


class indexController extends Controller
{
    public function notificationAjax(Request $request){
      $competitionId = $request->competitionId;
      // $competitionId = '24';
      $now = date('Y-m-d H:i:s');
      $isStarted = DB::select("select * ,timestampdiff(second,isStarted, '$now' ) as timediff from notifications where timestampdiff(second,isStarted, '$now' ) <= 9 and competitionId = $competitionId and isFinished = 'null'");
      $isFinished = DB::select("select * ,timestampdiff(second,isFinished, '$now' ) as timediff from notifications where timestampdiff(second,isFinished, '$now' ) <= 9 and competitionId = $competitionId");
      // dd($obj);
      // dd($isFinished[0]->matchId);
      // $matchId = $isFinished[0]->matchId;

      $status = (Object)['started'=> '0',
                         'finished'=> '0',
                         'competionId' => $competitionId,
                         'matchId'     => '0'
                        ];
      // $status->started = 1;
      if(count($isStarted)){
        $status->started = 1;
      }
      if(count($isFinished)){
        $status->finished = 1;
        $status->matchId  = $isFinished[0]->matchId;
      }
      // dd($status);
      return json_encode($status);
    }
    public function index()
    {
      $dateCurrent = new DateTime();
      $dateOld = new DateTime();
      date_sub($dateOld,date_interval_create_from_date_string("1 days"));
      $dateCurrent = date_format($dateCurrent,"Y-m-d");
      $dateOld = date_format($dateOld,"Y-m-d");

      $teamsCollectionwScore = Array();
      $BigCollection = Array();
      $competitionI = 0;
      $matchesI = 0;

      $competitions = DB::table('competitionVenues')->whereDate('start_date', '=' ,$dateOld)->orWhere('start_date', '=', $dateCurrent)->get();
      // dd('yes');
      // dd($competitions);
      foreach ($competitions as $competition) {
        $competitionCollection = helper::customeResponse($competition, 'matches', null);
        // dd($competition);
        $BigCollection[$competitionI] = $competitionCollection;

        $competitionId = $competition->id;
        $matches = DB::table('matches')->where('competitionId', $competitionId)->get();

        $matchesI = 0;
        $matcesCollection = Array();


        foreach ($matches as $match) {
          // dd($match);

          $matcesCollection[$matchesI] = helper::customeResponse($match,
                  'redTeamTitle', null, 'blueTeamTitle', null, 'redTeamScore', null,'blueTeamScore' , null
          );

          $redTeamId = $match->redtid;
          $blueTeamId = $match->bluetid;
          $redTeamTitle = helper::getTeamTitleById($redTeamId);
          $blueTeamTitle = helper::getTeamTitleById($blueTeamId);

          $matcesCollection[$matchesI]['redTeamTitle'] = $redTeamTitle;
          $matcesCollection[$matchesI]['blueTeamTitle'] = $blueTeamTitle;
          $redTeamScore = helper::getRedTeamScore($redTeamId, $match->id);
          $blueTeamScore = helper::getRedTeamScore($blueTeamId, $match->id);
          $matcesCollection[$matchesI]['redTeamScore'] = $redTeamScore;
          $matcesCollection[$matchesI]['blueTeamScore'] = $blueTeamScore;

          $matchesI++;
        }

        $BigCollection[$competitionI]['matches'] = $matcesCollection;
        $competitionI++;
      }


      $sponsers = DB::table('sponsors')->get();

      // dd($sponsers);
      return view('indexPage.index')->with('BigCollection',$BigCollection)->with('sponsers',$sponsers);
    }


    public function index2()
    {
      $dateCurrent = new DateTime();
      $dateOld = new DateTime();
      date_sub($dateOld,date_interval_create_from_date_string("1 days"));
      $dateCurrent = date_format($dateCurrent,"Y-m-d");
      $dateOld = date_format($dateOld,"Y-m-d");

      $teamsCollectionwScore = Array();
      $BigCollection = Array();
      $competitionI = 0;
      $matchesI = 0;

      $competitions = DB::table('competitionVenues')->whereDate('start_date', '=' ,$dateOld)->orWhere('start_date', '=', $dateCurrent)->get();
      foreach ($competitions as $competition) {
        $competitionCollection = helper::customeResponse($competition, 'matches', null);
        $BigCollection[$competitionI] = $competitionCollection;

        $competitionId = $competition->id;
        $matches = DB::table('matches')->where('competitionId', $competitionId)->get();

        $matchesI = 0;
        $matcesCollection = Array();

        foreach ($matches as $match) {
          $matcesCollection[$matchesI] = helper::customeResponse($match,
                  'redTeamTitle', null, 'blueTeamTitle', null, 'redTeamScore', null,'blueTeamScore' , null
          );
          $redTeamId = $match->redtid;
          $blueTeamId = $match->bluetid;
          $redTeamTitle = helper::getTeamTitleById($redTeamId);
          $blueTeamTitle = helper::getTeamTitleById($blueTeamId);
          $matcesCollection[$matchesI]['redTeamTitle'] = $redTeamTitle;
          $matcesCollection[$matchesI]['blueTeamTitle'] = $blueTeamTitle;


          $redTeamScore = helper::getRedTeamScore($redTeamId, $match->id);
          $blueTeamScore = helper::getRedTeamScore($blueTeamId, $match->id);
          $matcesCollection[$matchesI]['redTeamScore'] = $redTeamScore;
          $matcesCollection[$matchesI]['blueTeamScore'] = $blueTeamScore;
          $matchesI++;
        }
          $BigCollection[$competitionI]['matches'] = $matcesCollection;
        $competitionI++;
      }
      return view('indexPage.index2')->with('BigCollection',$BigCollection);
    }
    public function teamScoreByTeamId(Request $request){
      $matchId = $request->matchId;
      $newCollection = DB::select("Select Sum(score) as Score, judgeId,color,Max(name) as JudgeName,
              Case When Color = 'red' then Max(redtid) Else Max(bluetid) End as TeamId
              From (
              	SELECT S.score,S.judgeId,S.participantId,S.matchId,JD.name,M.redtid, M.bluetid,
              	(Select color From teams
              		INNER JOIN teamsmembers ON teams.id = teamsmembers.tid
              	Where teamsmembers.pid = S.participantId
              	And (teams.id = M.redtid OR teams.id = M.bluetid)
              	Limit 1) as color
              	 FROM scores S
              	INNER JOIN matches M ON S.matchId = M.Id
              	INNER JOIN judges JD on S.judgeId = JD.id
              	where S.matchId = $matchId
              ) as tt
              group by judgeId,color");
              foreach($newCollection as $colection){
                if (strtolower($colection->color) == 'blue') {
                  $blueTeam = DB::table('teams')->where('id', $colection->TeamId)->get();

                }
                if (strtolower($colection->color) == 'red') {
                  $redTeam = DB::table('teams')->where('id', $colection->TeamId)->get();

                }
              }
              // dd($newCollection);
              $blueTeams = Array();
              $redTeams = Array();
              foreach ($newCollection as $Collection) {
                if (strtolower($Collection->color) == 'blue') {
                  $blueTeams[] = $Collection;
                }
                if (strtolower($Collection->color) == 'red') {
                  $redTeams[] = $Collection;
                }
              }

        ?>
        <thead>
          <tr>


            <th id="blueTeamHeading"  style="    width: 50%;border: none;     font-size: 35px;"><?php
            if (isset($blueTeam) == true) {
              echo $blueTeam[0]->name;
            }else{
              echo '--';
            }
            ?></th>
            <th class="text-center cntrcolstyle" >VS</th>

            <th id="redTeamHeading" style="    width: 50%;border: none;    font-size: 35px;" ><?php
            if (isset($redTeam) == true) {
              echo $redTeam[0]->name ;
            }else{
              echo '--';
            }
            ?></th>


          </tr>
        </thead>
        <tbody id="tableBody">
        <tr>
          <td class="blueTeamColor " style="border: none;">
            <div class="table-responsive blueTeamColorNew">

            <table  class="table bgTransparent blueTeamColorNew">

            <tr>
            <?php
            $countforblue = 0;
            $blueJ        = 0;
            $blueTeamScoreSum = 0;
            foreach ($blueTeams as $team): ?>
               <?php
              if ($blueJ == ($countforblue - 1)) {
                // echo "string";
              }else{
                // echo 'class="whiteBorderRight"';
              }
               ?><?php $blueTeamScoreSum += $team->Score ?>
               <?php $blueJ++; ?>
            <?php endforeach; ?>
            <td class="text-center" style="font-size: 220px !important; padding:0px !important;line-height: 1 !important;">
              <?php
              echo $blueTeamScoreSum;
               ?>
            </td>
            </tr>
          </table>
          </div>

          </td>

          <td class=" cntrcolstyle"> </td>

          <td class="redTeamColor"  style="border: none;">
            <div class="table-responsive redTeamColorNew">
            <table  class="table redTeamColorNew">
              <tr class="text-center">
              <?php
              $countforred = 0;
              $redJ        = 0;
              $redTeamScoreSum = 0;
               foreach ($redTeams as $team): ?>
                <?php
                if ($redJ == ($countforred - 1)) {
                  // echo "string";
                }else{
                  // echo 'class="whiteBorderRight"';
                }
             ?>
                <?php $redTeamScoreSum += $team->Score ?>
                <?php $redJ++ ?>
              <?php endforeach; ?>
              <td class="text-center" style="font-size: 220px  !important;  padding:0px !important;line-height: 1 !important;">
              <?php
              echo $redTeamScoreSum;
               ?>
             </td>
              </tr>

            </table>
          </div>
          </td>
        </tr>
        </tbody>
        <?php
    }

    public function ajax(Request $request){
        // dd($request->id);
      $competionId = $request->id;
      $now = date('Y-m-d H:i:s');
      $matches = DB::select("SELECT * FROM matches where timestampdiff(second,updated_at,'$now') <= 15 and `competitionId` = $competionId");
      $count = DB::select("SELECT * FROM matches where timestampdiff(second,updated_at,'$now') <= 15 and `competitionId` = $competionId");
      // dd("SELECT * FROM matches where timestampdiff(second,updated_at,'$now') <= 215 and 'competitionId' = $competionId");
      if (count($count) < 1) {
        return 'false';
      }
      // dd(count($count));
      // dd($matches);
      // dd('$newCollection');
      foreach ($matches as $match) {
          $newCollection = DB::select("Select Sum(score) as Score, judgeId,color,Max(name) as JudgeName,
                  Case When Color = 'red' then Max(redtid) Else Max(bluetid) End as TeamId
                  From (
                  	SELECT S.score,S.judgeId,S.participantId,S.matchId,JD.name,M.redtid, M.bluetid,
                  	(Select color From teams
                  		INNER JOIN teamsmembers ON teams.id = teamsmembers.tid
                  	Where teamsmembers.pid = S.participantId
                  	And (teams.id = M.redtid OR teams.id = M.bluetid)
                  	Limit 1) as color
                  	 FROM scores S
                  	INNER JOIN matches M ON S.matchId = M.Id
                  	INNER JOIN judges JD on S.judgeId = JD.id
                  	where S.matchId = $match->id
                  ) as tt
                  group by judgeId,color");

      }
      foreach($newCollection as $colection){
        if (strtolower($colection->color) == 'blue') {
          $blueTeam = DB::table('teams')->where('id', $colection->TeamId)->get();

        }
        if (strtolower($colection->color) == 'red') {
          $redTeam = DB::table('teams')->where('id', $colection->TeamId)->get();

        }
      }
      // dd($newCollection);
      $blueTeams = Array();
      $redTeams = Array();
      foreach ($newCollection as $Collection) {
        if (strtolower($Collection->color) == 'blue') {
          $blueTeams[] = $Collection;
        }
        if (strtolower($Collection->color) == 'red') {
          $redTeams[] = $Collection;
        }
      }

?>
<thead>
  <tr>
    <th id="redTeamHeading" style="border: none;    font-size: 35px;" ><?php
    if (isset($redTeam) == true) {
      echo $redTeam[0]->name ;
    }else{
      echo '--';
    }
    ?></th>
    <th class="text-center cntrcolstyle" >VS</th>
    <th id="blueTeamHeading"  style="border: none;     font-size: 35px;"><?php
    if (isset($blueTeam) == true) {
      echo $blueTeam[0]->name;
    }else{
      echo '--';
    }
    ?></th>
  </tr>
</thead>
<tbody id="tableBody">
<tr>
  <td class="redTeamColor"  style="border: none;">
    <div class="table-responsive redTeamColorNew">
    <table  class="table redTeamColorNew">
      <tr>
      <?php
      $countforred = count($redTeams);
      $redI = 0;
      $redJ = 0;
       ?>
      <?php foreach ($redTeams as $team): ?>
        <th <?php
            if ($redI == ($countforred - 1)) {
              // echo "string";
            }else{
              echo 'class="whiteBorderRight"';
            }
         ?> ><?php echo $team->JudgeName ?></th>
        <?php $redI++ ?>
      <?php endforeach; ?>
      </tr>

      <tr>
      <?php foreach ($redTeams as $team): ?>
        <td <?php
        if ($redJ == ($countforred - 1)) {
          // echo "string";
        }else{
          echo 'class="whiteBorderRight"';
        }
     ?>
        ><?php echo $team->Score ?></td>
        <?php $redJ++ ?>
      <?php endforeach; ?>
      </tr>

    </table>
  </div>
  </td>
  <td class=" cntrcolstyle"> </td>
  <td class="blueTeamColor " style="border: none;">
    <div class="table-responsive blueTeamColorNew">

    <table  class="table bgTransparent blueTeamColorNew">
    <tr>
      <?php
      $countforblue = count($blueTeams);
      $blueI = 0;
      $blueJ = 0;
       ?>
    <?php foreach ($blueTeams as $team): ?>
      <th
        <?php
        if ($blueI == ($countforblue - 1)) {
          // echo "string";
        }else{
          echo 'class="whiteBorderRight"';
        }
         ?>
      ><?php echo $team->JudgeName ?></th>
      <?php $blueI++; ?>
    <?php endforeach; ?>
    </tr>

    <tr>
    <?php foreach ($blueTeams as $team): ?>
      <td <?php
      if ($blueJ == ($countforblue - 1)) {
        // echo "string";
      }else{
        echo 'class="whiteBorderRight"';
      }
       ?>><?php echo $team->Score ?></td>
       <?php $blueJ++; ?>
    <?php endforeach; ?>
    </tr>
  </table>
  </div>

  </td>
</tr>
</tbody>
<?php
    }






    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ajax2(Request $request){
        // dd($request->id);
      $competionId = $request->id;
      $now = date('Y-m-d H:i:s');
      $matches = DB::select("SELECT * FROM matches where timestampdiff(second,updated_at,'$now') <= 15 and `competitionId` = $competionId");
      $count = DB::select("SELECT * FROM matches where timestampdiff(second,updated_at,'$now') <= 15 and `competitionId` = $competionId");
      // dd("SELECT * FROM matches where timestampdiff(second,updated_at,'$now') <= 215 and 'competitionId' = $competionId");
      if (count($count) < 1) {
        return 'false';
      }
      // dd(count($count));
      // dd($matches);
      // dd('$newCollection');
      foreach ($matches as $match) {
          $newCollection = DB::select("Select Sum(score) as Score, judgeId,color,Max(name) as JudgeName,
                  Case When Color = 'red' then Max(redtid) Else Max(bluetid) End as TeamId
                  From (
                    SELECT S.score,S.judgeId,S.participantId,S.matchId,JD.name,M.redtid, M.bluetid,
                    (Select color From teams
                      INNER JOIN teamsmembers ON teams.id = teamsmembers.tid
                    Where teamsmembers.pid = S.participantId
                    And (teams.id = M.redtid OR teams.id = M.bluetid)
                    Limit 1) as color
                     FROM scores S
                    INNER JOIN matches M ON S.matchId = M.Id
                    INNER JOIN judges JD on S.judgeId = JD.id
                    where S.matchId = $match->id
                  ) as tt
                  group by judgeId,color");

      }
      // $blueTeam = "";
      // $redTeam = "";
      // dd($newCollection);
      foreach($newCollection as $colection){
        // dd($colection);
        // helper::mydump($colection);
        if (strtolower($colection->color) == 'blue') {
          $blueTeam = DB::table('teams')->where('id', $colection->TeamId)->get();

          // dd('going here');
        }
        if (strtolower($colection->color) == 'red') {
          $redTeam = DB::table('teams')->where('id', $colection->TeamId)->get();
        }
      }
      // dd($newCollection);
      $blueTeams = Array();
      $redTeams = Array();
      foreach ($newCollection as $Collection) {
        if (strtolower($Collection->color) == 'blue') {
          $blueTeams[] = $Collection;
        }
        if (strtolower($Collection->color) == 'red') {
          $redTeams[] = $Collection;
        }
      }
      // dd($newCollection);
?>
<thead>
  <tr>
    <th id="redTeamHeading" style="width: 50%; border: none;    font-size: 35px;" ><?php
    if (isset($redTeam) == true) {
      echo $redTeam[0]->name ;
    }else{
      echo '--';
      // dd($redTeam);
    }
    ?></th>
    <th class="text-center cntrcolstyle" >VS</th>
    <th id="blueTeamHeading"  style="width: 50%;border: none;     font-size: 35px;"><?php
    if (isset($blueTeam) == true) {
      echo $blueTeam[0]->name;
    }else{
      echo '--';
    }
    ?></th>
  </tr>
</thead>



<tbody id="tableBody">
<tr>
  <td class="redTeamColor"  style="border: none;">
    <div class="table-responsive redTeamColorNew">

    <table  class="table redTeamColorNew">
      <tr>
      <?php
      $countforred = count($redTeams);
      $redI = 0;
      $redJ = 0;
       ?>
      <?php foreach ($redTeams as $team): ?>

        <th <?php
            if ($redI == ($countforred - 1)) {
              // echo "string";
            }else{
              echo 'class="whiteBorderRight"';
            }
         ?> ><?php echo $team->JudgeName ?></th>
        <?php $redI++ ?>
      <?php endforeach; ?>
      </tr>

      <tr>

      <?php
      // dd($redTeams);

       foreach ($redTeams as $team): ?>
        <td <?php
        if ($redJ == ($countforred - 1)) {
          // echo "string";
        }else{
          echo 'class="whiteBorderRight"';
        }
     ?>
     >
     <?php echo $team->Score ?></td>
        <?php $redJ++ ?>
      <?php endforeach; ?>
      </tr>

    </table>
  </div>

  </td>
  <td class=" cntrcolstyle"> </td>
  <td class="blueTeamColor " style="border: none;">
    <div class="table-responsive blueTeamColorNew">

    <table  class="table bgTransparent blueTeamColorNew">
    <tr>
      <?php

      $countforblue = count($blueTeams);
      $blueI = 0;
      $blueJ = 0;
       ?>
    <?php foreach ($blueTeams as $team): ?>
      <th
        <?php
        if ($blueI == ($countforblue - 1)) {
          // echo "string";
        }else{
          echo 'class="whiteBorderRight"';
        }
         ?>
      ><?php echo $team->JudgeName ?></th>
      <?php $blueI++; ?>
    <?php endforeach; ?>
    </tr>

    <tr>
    <?php foreach ($blueTeams as $team): ?>
      <td <?php
      if ($blueJ == ($countforblue - 1)) {
        // echo "string";
      }else{
        echo 'class="whiteBorderRight"';
      }
       ?>><?php echo $team->Score ?></td>
       <?php $blueJ++; ?>
    <?php endforeach; ?>
    </tr>
  </table>
  </div>

  </td>
</tr>
</tbody>

















<?php





    }
























}
