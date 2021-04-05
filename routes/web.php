<?php

use App\Console\Process;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process as ProcessProcess;

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
    $process = (new Process('dir'))->run();

    // dd($process->getOutput());exit;
    // $teste = shell_exec("node ./../node_modules/lighthouse/lighthouse-cli/index.js https://www.google.com.br/ --output=json --output-path ./../saidas/myfile.json");
    // dd($teste);
    $process = new ProcessProcess(['cd']);
    $process->run();
    dd($process->getOutput());
    // dd($process->getErrorOutput());exit;

    // $lighthouse = new Lighthouse2([]);
    return view('welcome');
});
Route::resource('contacts', ContactController::class);
