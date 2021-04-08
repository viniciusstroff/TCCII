<?php

use App\Console\Process;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    
    // dd($process->getOutput());exit;
    // $teste = shell_exec("node ./../node_modules/lighthouse/lighthouse-cli/index.js https://www.google.com.br/ --output=json --output-path ./../saidas/myfile.json");
    // dd($teste);
    $process = new Process('ls -la');
    $process->run();
    dd($process->getOutput());
    return view('welcome');
});
Route::resource('contacts', ContactController::class);
