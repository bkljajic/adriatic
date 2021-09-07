<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Partial;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FarmerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFarmer()
    {
        $returnData = Farmer::get();
        $activeFarmers = Farmer::where('points', '>', 0)->get();
        $count = $activeFarmers->count();
        $total_space = 0;
        $block_partial = Farmer::where('launcher_id', '2d2fe94bc590f4f96289fac3a4ab9f80ac15deb9489272ab84a28a45294d8d2b')->where('points', '<', 3)->first();
        if ($block_partial) {
            $block_timestamp = Partial::where('launcher_id', '2d2fe94bc590f4f96289fac3a4ab9f80ac15deb9489272ab84a28a45294d8d2b')->orderBy('timestamp', 'DESC')->first()->timestamp;
            Storage::put('block_timestamps.txt', $block_timestamp);
        } else {
            $block_timestamp = Storage::disk('local')->get('block_timestamps.txt');
            $block_timestamp = str_replace("\n", "", $block_timestamp);
        }
        $returnData->map(function ($item) use ($block_timestamp, &$total_space) {
            $results = Partial::where('launcher_id', $item->launcher_id)->orderBy('timestamp', 'DESC')->first();
            if ($results) {
                $time_now = now()->timestamp;

                if ($results->timestamp < $block_timestamp) {
                    $farmerSpace = $item->points / ((0.0001157) * ($time_now - $results->timestamp));

                } else {
                    $farmerSpace = $item->points / ((0.0001157) * ($time_now - $block_timestamp));
                }
                $total_space += $farmerSpace;
            }
        });

        return response()->json(["total_space" => ((($total_space / 10.14) * 1.04951163) / 1024), "active_farmers" => $count, "block" => $block_timestamp]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFarmers()
    {
        $farmers = Farmer::get();
        $block_partial = Farmer::where('launcher_id', '2d2fe94bc590f4f96289fac3a4ab9f80ac15deb9489272ab84a28a45294d8d2b')->where('points', '<', 3)->first();
        if ($block_partial) {
            $block_timestamp = Partial::where('launcher_id', '2d2fe94bc590f4f96289fac3a4ab9f80ac15deb9489272ab84a28a45294d8d2b')->orderBy('timestamp', 'DESC')->first()->timestamp;
            Storage::put('block_timestamps.txt', $block_timestamp);
        } else {
            $block_timestamp = Storage::disk('local')->get('block_timestamps.txt');
            $block_timestamp = str_replace("\n", "", $block_timestamp);
        }
        $total = 0;
        $farmers->map(function ($item) use ($block_timestamp, &$total) {
            $results = Partial::where('launcher_id', $item->launcher_id)->orderBy('timestamp', 'DESC')->first();
            if ($results) {
                $total += $item->points;
            }
        });
        $total /= 100;

        return response()->json(Farmer::where('points', '>', 0)->orderBy('points', 'DESC')->get()->map(function ($item) use ($block_timestamp, $total) {
            $total_space = 0;
            $results = Partial::where('launcher_id', $item->launcher_id)->orderBy('timestamp', 'DESC')->first();
            if ($results) {
                $time_now = now()->timestamp;

                if ($results->timestamp < $block_timestamp) {
                    $farmerSpace = $item->points / ((0.0001157) * ($time_now - $results->timestamp));

                } else {
                    $farmerSpace = $item->points / ((0.0001157) * ($time_now - $block_timestamp));
                }
                $total_space += $farmerSpace;
            }

            return [
                "launcher_id" => $item->launcher_id,
                "points" => $item->points,
                "percent" => ($item->points / $total) . '%',
                "est_reward" => (($item->points / $total) * 0.0170),
                "total_space" => (($total_space / 10.14) * 1.04951163)
            ];
        }));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Farmer $farmer
     * @return \Illuminate\Http\Response
     */
    public function show(Farmer $farmer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Farmer $farmer
     * @return \Illuminate\Http\Response
     */
    public function edit(Farmer $farmer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Farmer $farmer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Farmer $farmer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Farmer $farmer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Farmer $farmer)
    {
        //
    }
}
