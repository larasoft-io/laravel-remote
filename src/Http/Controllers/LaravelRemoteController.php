<?php

namespace Larasoft\LaravelRemote\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Artisan;

class LaravelRemoteController extends Controller
{
//    protected $key_is_valid;
//    
//    public function __construct()
//    {
//        if(config('remote.key')){
//            $key_is_valid = true;
//        }else{
//            $key_is_valid = false;
//        }
//    }

    public function getStatus(){
        if(app()->isDownForMaintenance()){
            return \Response::json(['status' => 'down']);

        }else{
            return \Response::json(['status' => 'up']);
        }
    }

    public function executeCommand($command){
        if($command == 'up'){
            Artisan::call('up');
            return \Response::json(['status' => 'up']);
        }
        else if($command == 'down'){
            Artisan::call('down');
            return \Response::json(['status' => 'down']);
        }
    }
}
