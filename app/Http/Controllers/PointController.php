<?php

namespace App\Http\Controllers;

use App\Model\Player;
use App\Model\Point;
use App\Model\Round;
use Illuminate\Http\Request;

class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $game = session() -> get('game');
        $players = Player ::with('game') -> where('game_id', $game -> id) -> get();
        return view('rounds.add_round_result', compact('game', 'players'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $game = session() -> get('game');
        $round = Round ::create(['game_id' => $game -> id]);
        $winner = $request -> winner;
        $sumAllPoints = array_sum($request -> all()['points']);
        $winnerPoint = 0;
        foreach($request -> all()['points'] as $key => $value) {
            if($key == $winner) {
                continue;
            }

            if(isset($request -> all()['seen'][$key]) && $request -> all()['seen'][$key] == 'on') {
                if(isset($request -> all()['dubli'][$winner]) && $request -> all()['dubli'][$winner] == 'on') {
                    if(isset($request -> all()['dubli'][$key]) && $request -> all()['dubli'][$key] == 'on') {
                        $pointSecured = ($value * $game -> number_of_players) - ($sumAllPoints);
                    } else {
                        $pointSecured = ($value * $game -> number_of_players) - ($sumAllPoints + $game -> dubli_winner_points_per_seen);
                    }
                } else {
                    if(isset($request -> all()['dubli'][$key]) && $request -> all()['dubli'][$key] == 'on') {
                        $pointSecured = ($value * $game -> number_of_players) - ($sumAllPoints);
                    } else {
                        $pointSecured = ($value * $game -> number_of_players) - ($sumAllPoints + $game -> winner_points_per_seen);
                    }
                }
            } else {
                if(isset($request -> all()['dubli'][$winner]) && $request -> all()['dubli'][$winner] == 'on') {
                    $pointSecured = -1 * ($sumAllPoints + $game -> dubli_winner_points_per_unseen);
                } else {
                    $pointSecured = -1 * ($sumAllPoints + $game -> winner_points_per_unseen);
                }

            }
            $pointArray = ['player_id' => $key, 'round_id' => $round -> id, 'point_scored' => $pointSecured];
            $point = Point ::create($pointArray);
            $winnerPoint = $winnerPoint + $pointSecured;
        }
        $winnerArray = ['player_id' => $winner, 'round_id' => $round -> id, 'point_scored' => $winnerPoint];
        $winnerObject = Point ::create($winnerArray);
        return redirect() -> route('points.show', $round -> id);
    }



    /**
     * Display the specified resource.
     *
     * @param $roundId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($roundId)
    {
        $game=session()->get('game');
        $players=Player::where('game_id',$game->id)->get();
        $points=Point::where('round_id',$roundId)->get();
        return view('points.points_table',compact('roundId','points','players'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function edit(Point $point)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Point $point)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function destroy(Point $point)
    {
        //
    }

    public function total()
    {
        $game = session() -> get('game');
        $roundIdArray = Round ::where('game_id', $game -> id) -> pluck('id');
        $players = Player ::where('game_id', $game -> id) -> get();
        $points = Point ::whereIn('round_id', $roundIdArray) -> get();
        return view('points.total-points', compact('players', 'points','game'));

    }

    public function showTotal($gameId)
    {
        $roundIdArray = Round ::where('game_id', $gameId) -> pluck('id');
        $players = Player ::where('game_id', $gameId) -> get();
        $points = Point ::whereIn('round_id', $roundIdArray) -> get();
        return view('points.total-points', compact('players', 'points','game'));

    }
}
