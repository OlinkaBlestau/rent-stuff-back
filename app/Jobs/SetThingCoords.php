<?php

namespace App\Jobs;

use App\Models\Thing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetThingCoords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly float $latitude,
        private readonly float $longitude,
        private readonly int $thingId,
    )
    {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $thing = Thing::find($this->thingId);

        $thing->latitude = $this->latitude;
        $thing->longitude = $this->longitude;
        $thing->save();
    }
}
