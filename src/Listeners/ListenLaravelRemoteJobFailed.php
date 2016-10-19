<?php

namespace Larasoft\LaravelRemote;

use Illuminate\Support\Facades\Log;
use Larasoft\LaravelRemote\LaravelRemoteJobFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Vinelab\Http\Client as HttpClient;

class ListenLaravelRemoteJobFailed
{
    protected $client;

    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        $this->client = new HttpClient();
    }

    /**
     * Handle the event.
     *
     * @param  LaravelRemoteJobFailed  $event
     * @return void
     */
    public function handle(LaravelRemoteJobFailed $event)
    {
        $token = config('remote.key');

        $data = [
            'url' => config('remote.url').'/project/failed/job',
            'headers' => ["token: $token"],
            'params' => [
                'data'     => $event->data
            ],
        ];
        
        $this->client->get($data);
        
    }
}
