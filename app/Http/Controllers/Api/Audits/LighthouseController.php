<?php

namespace App\Http\Controllers\Api\Audits;

use App\Helpers\Lighthouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\TesteRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Spatie\Async\Pool;
use Throwable;

class LighthouseController extends BaseApiController
{
    private $lighthouse;
    public $counter;
    public Array $outputs;

    public function __construct()
    {
        // $this->lighthouse = new Lighthouse();
    }

    public function index()
    {
        try{
            // $this->lighthouse->setSite(['https://www.google.com.br/']);
            // // $this->lightouse->audit();

            // // if($lightouse->hasFinished()){
            //     $data = $this->lighthouse->getOutput();


                return response(['project' => [], 'message' => 'Retrieved successfully'], 200);
            // }
            
        }catch( \Exception $exception){
            $this->sendError($exception->getMessage());
        }
    }

    public function store(TesteRequest $request)
    {   

        $data = $request->all();
        
        $pool = Pool::create();
        $sites[] = $data['site'];
        $sites[] = 'https://www.sinonimos.com.br';
        foreach ($sites as $site) {
            $lighthouse = new Lighthouse($site);
            $pool->add(function () use($lighthouse) {
               
                $lighthouse->setCategories(['accessibility', 'performance']);
                $lighthouse->audit();
            })->then(function () use($lighthouse, $pool){
                // $outputFile = $this->lighthouse->getOutputFile();
                // $open = fopen(base_path('app/console/outputs/') .'teste.log', 'a');
                // fwrite($open,$lighthouse->getOutput());
                // fclose($open);
            })->catch(function( Throwable $exception){
                return $this->sendError("ERRO", $exception->getMessage());
            });
        }
        
        $results = await($pool);
        // dd($results);
        // if(!$this->lighthouse->isRunning()){
        
        return $this->sendResponse("resultado pendente", "A audição está sendo executada...");
        // }
       
        

        
        // dd($this->lighthouse->getOutput());

    }





}
