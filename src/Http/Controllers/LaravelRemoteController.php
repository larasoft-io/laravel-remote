<?php

namespace Larasoft\LaravelRemote\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

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

    public function updateEnvVariable(Request $request){
        $this->changeEnv([$request->name => $request->value]);
    }
    
    public function storeEnvVariable(Request $request){
        $line = PHP_EOL."{$request->name}={$request->value}";

        $bytesWritten = File::append(base_path('.env'), $line);
        if ($bytesWritten === false)
        {
            return response()->json(['success' => 0]);
        }
        else{
            return response()->json(['success' => 1]);
        }
    }
    
    public function deleteEnvVariable(Request $request)
    {
        $this->removeFromEnv($request->name);

        return response()->json(['success' => 1]);
    }

    protected function changeEnv($data = array()){
        if(count($data) > 0){

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;

            // Loop through given data
            foreach((array)$data as $key => $value){

                // Loop through .env-data
                foreach($env as $env_key => $env_value){

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if($entry[0] == $key){
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);

            return true;
        } else {
            return false;
        }
    }

    protected function removeFromEnv($name){
        if(isset($name)){

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;

            // Loop through .env-data
            foreach($env as $env_key => $env_value){

                // Turn the value into an array and stop after the first split
                // So it's not possible to split e.g. the App-Key by accident
                $entry = explode("=", $env_value, 2);

                // Check, if key/name to remove fits the actual .env-key
                if($entry[0] == $name){
                    // If yes, remove it
                    unset($env[$env_key]);
                } else {
                    // If not, keep the old one
                    $env[$env_key] = $env_value;
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);

            return true;
        } else {
            return false;
        }
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
