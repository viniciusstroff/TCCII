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
    // $teste = shell_exec("node ./../node_modules/lighthouse/lighthouse-cli/index.js https://www.google.com.br/ --output=json --output-path ./../saidas/myfile.json");
    $process = new Process('npm exe -c "lighthouse https://www.google.com.br/ --output=json --output-path ./../app/console/outputs/myfile23.json');
    $process->run();
    $process->getOutput();
    return view('welcome');
});
Route::resource('contacts', ContactController::class);
