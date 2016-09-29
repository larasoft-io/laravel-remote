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

    public function getEnvVariables(){
        $vars = $this->envToArray(base_path('.env'));

        $data = [];

        foreach ($vars as $key => $var){
            array_push($data, ['name' => $key, 'value' => $var]);
        }

        return $data;
    }

    protected function envToArray($file){
        $string = file_get_contents($file);
        $string = preg_split('/\n+/', $string);
        $returnArray = array();
        foreach($string as $one){
            if (preg_match('/^(#\s)/', $one) === 1) {
                continue;
            }
            $entry = explode("=", $one, 2);
            $returnArray[$entry[0]] = isset($entry[1]) ? $entry[1] : null;
        }
        return $returnArray;
    }
}
