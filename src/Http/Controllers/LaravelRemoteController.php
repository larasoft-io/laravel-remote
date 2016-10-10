<?php

namespace Larasoft\LaravelRemote\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class LaravelRemoteController extends Controller
{
    protected $laravel_remote_key;
    protected $backup_dir_name;

    public function __construct(Request $request)
    {
        $this->laravel_remote_key = config('remote.key');
        $this->backup_dir_name = preg_replace("/[^A-Za-z0-9 ]/", '-', config('laravel-backup.backup.name'));
    }

    public function getStatus(Request $request)
    {
        if ( $this->checkLaravelRemoteKey($request) )
        {
            if ( app()->isDownForMaintenance() )
            {
                return \Response::json(['status' => 'down', 'success' => 1]);
            }
            else
            {
                return \Response::json(['status' => 'up', 'success' => 1]);
            }
        }
        else
        {
            return \Response::json(['success' => 0, 'message' => 'Invalid Laravel Remote Key!']);
        }

    }

    public function executeCommand($command, Request $request)
    {
        if ( $this->checkLaravelRemoteKey($request) )
        {
            if ( $command == 'up' )
            {
                Artisan::call('up');

                return \Response::json(['status' => 'up', 'success' => 1]);
            }
            else if( $command == 'down' )
            {
                Artisan::call('down');

                return \Response::json(['status' => 'down', 'success' => 1]);
            }
            else
            {
                Artisan::call($command);
            }

            return response(200);
        }
        else{
            return response()->json(['success' => 0, 'message' => 'Invalid Laravel Remote Key!']);
        }
    }

    /*
     * Get all Backups
     */
    public function backups(Request $request){
        if ( $this->checkLaravelRemoteKey($request) )
        {
            $links = [];

            $files = File::allFiles(storage_path("app/{$this->backup_dir_name}"));

            foreach ($files as $file){
                array_push($links, [
                    'name' => basename($file),
                    'download_path' => url('laravel-remote/backups/'.basename($file).'/download'),
                    'delete_path' => url('laravel-remote/backups/'.basename($file).'/delete')
                ]);
            }

            return response()->json(['success' => 1, 'backups' => $links]);
        }
        else{
            return response()->json(['success' => 0, 'message' => 'Invalid Laravel Remote Key!']);
        }
    }

    /*
     * Download a specific Backup
     */
    public function downloadBackup($name){
        if ( $this->checkLaravelRemoteKey(request()) )
        {
            $file = storage_path("app/{$this->backup_dir_name}/{$name}");

            return response()->download($file);
        }
        else{
            return 'Unauthorised Access! Please contact administrator.';
        }
    }

    /*
     * Delete a specific Backup
     */
    public function deleteBackup($name){
        if ( $this->checkLaravelRemoteKey(request()) )
        {
            $file = storage_path("app/{$this->backup_dir_name}/{$name}");

            if(File::exists($file)){
                File::delete($file);
            }

            return response()->json(['success' => 1]);
        }
        else{
            return response()->json(['success' => 0, 'message' => 'Invalid Laravel Remote Key!']);
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

    public function getRawEnvFile(){
        return file_get_contents(base_path('.env'));
    }

    public function saveRawEnvFile(Request $request){
        if ( $this->checkLaravelRemoteKey(request()) )
        {
            file_put_contents(base_path('.env'), $request->raw);

            return response()->json(['success' => 1]);
        }
        else{
            return response()->json(['success' => 0, 'message' => 'Invalid Laravel Remote Key!']);
        }
    }

    public function updateEnvVariable(Request $request){
        if($request->name == 'LARAVEL_REMOTE_KEY')
        {
            $this->changeEnv([$request->name => $request->value]);
            return response()->json(['success' => 1]);
        }
        else{
            if ( $this->checkLaravelRemoteKey($request) )
            {
                $this->changeEnv([$request->name => $request->value]);
                return response()->json(['success' => 1]);
            }
            else
            {
                return response()->json(['success' => 0, 'message' => 'Invalid Laravel Remote Key!']);
            }
        }
    }

    public function storeEnvVariable(Request $request){
        if($request->name == 'LARAVEL_REMOTE_KEY')
        {
            $line = PHP_EOL . "{$request->name}={$request->value}";

            $bytesWritten = File::append(base_path('.env'), $line);
            if ( $bytesWritten === false )
            {
                return response()->json(['success' => 0, 'message' => 'Some error occurred while updating .env']);
            }
            else
            {
                return response()->json(['success' => 1]);
            }
        }else{
            if ( $this->checkLaravelRemoteKey($request) )
            {
                $line = PHP_EOL . "{$request->name}={$request->value}";

                $bytesWritten = File::append(base_path('.env'), $line);
                if ( $bytesWritten === false )
                {
                    return response()->json(['success' => 0, 'message' => 'Some error occurred while updating .env']);
                }
                else
                {
                    return response()->json(['success' => 1]);
                }
            }
            else
            {
                return response()->json(['success' => 0, 'message' => 'Invalid Laravel Remote Key!']);
            }
        }
    }

    public function deleteEnvVariable(Request $request)
    {
        if ( $this->checkLaravelRemoteKey($request) )
        {
            $this->removeFromEnv($request->name);

            return response()->json(['success' => 1]);
        }else{
            return response()->json(['success' => 0, 'message' => 'Invalid Laravel Remote Key!']);
        }
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

    /**
     * @param Request $request
     * @return bool
     */
    public function checkLaravelRemoteKey(Request $request)
    {
        if($request->header('token'))
        {
            return $this->laravel_remote_key == $request->header('token');
        }
        else{
            return $this->laravel_remote_key == $request->token;
        }
    }
}
