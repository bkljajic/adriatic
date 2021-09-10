<?php

namespace App\Console\Commands;

use App\Models\Farmer;
use App\Models\Partial;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ControllFarmerSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'famer:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync famer timestamp';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $block_partial = Farmer::where('launcher_id', '2d2fe94bc590f4f96289fac3a4ab9f80ac15deb9489272ab84a28a45294d8d2b')->where('points', '<', 3)->first();
        if ($block_partial) {
            $block_timestamp = Partial::where('launcher_id', '2d2fe94bc590f4f96289fac3a4ab9f80ac15deb9489272ab84a28a45294d8d2b')->orderBy('timestamp', 'DESC')->first()->timestamp;
            Storage::put('block_timestamps.txt', $block_timestamp);
        }
    }
}
