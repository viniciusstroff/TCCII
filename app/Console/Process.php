<?php

namespace App\Console;

use Illuminate\Support\Facades\Log;
use Spatie\Async\Pool;

//NPM exe lighthouse https://www.google.com.br

//npm exe -c "lighthouse https://www.google.com.br/ --output=json --output-path ./app/console/outputs/myfile2.json"
class Process
{
    protected Int $id;
    protected String $command;
    protected Int $timeout;
    protected Array $output;
    protected ?String $error = null;
    protected Bool $hasFinished = false;
    protected Bool $hasError = false;
    protected Bool $captureStdErr = true;
    protected ?Int $exitCode = null;
    protected Bool $isRunning = false;
    protected String $starttime;

    public function __construct(Array $commands, ?Array $arguments = [])
    {
        $this->constructCommandByArray($commands);
        // $this->setTimeout();
        // $this->setArguments($arguments);
    }

    private function constructCommandByArray(Array $commands)
    {
        if(empty($commands)) return;

        $this->command =  implode(" ", $commands);
    }

    public function setTimeout(Int $timeout = 120)
    {
        ini_set('max_execution_time', $timeout);
        $this->timeout = $timeout;
    }

    public function getCommand() : String
    {
        return $this->command;
    }


    public function getExitCode() : Int 
    {
        return $this->exitCode;
    }
    public function getExecCommand() : String
    {

        $command = $this->getCommand();

        $args = $this->getArguments();
        return $args ? "{$command} {$args}" : $command;
    }

    public function setArguments(Array $arguments)
    {
        $this->arguments = $arguments;
    }

    public function getArguments() : String
    {
        return implode(' ',$this->arguments);
    }

    public function getOutput(bool $removeSpaces = true)
    {
        if($this->hasFinished()){

            if($this->hasError()) return $this->getError();

            if(!is_array($this->output))
                return $removeSpaces ? trim($this->output) : $this->output;

            $output = array_map(function($output, $removeSpaces){
                return $removeSpaces ? trim($output) : $output;
            }, $this->output, [$removeSpaces]);

            return $output;
        }
    }

    public function getError($escapeSpaces = true): String
    {
        if(!$this->hasError())
            return $escapeSpaces ? trim($this->error) : $this->error;
    }

    public function setHasFinished(bool $done = true)
    {
        $this->hasFinished = $done;
    }

    public function hasFinished(): Bool
    {
        return $this->hasFinished;
    }

    public function hasError(): Bool
    {
        return $this->hasError;
    }

    public function setError(String $error)
    {
        $this->error = $error;
        dd($this->error);
    }

    public function setProcessId(Int $pid)
    {
        $this->id = $pid;
    }

    public function execute(?String $command = null)
    {

       try{

            if(!$this->command)
                $this->command = $command;
            

            
            $pool = Pool::create();    
            $pool->add(function(){
                $this->isRunning = true;
                error_reporting(E_ALL);
                if (substr(php_uname(), 0, 7) == "Windows"){
                    $process = popen("start /B ". $this->command , "r");
                    pclose($process); 
                }
                else {
                    $output = exec($this->command . " > /dev/null &", $output, $this->exitCode);  
                    
                    $this->output = $output;
                }
            })->then(function($output){
                
            })->catch(function($exception){
                dd($exception);
            });

            $results = await($pool);
            $this->isRunning = false;
            return $results;
       }catch(\Exception $exception)
       {
           $this->setError($exception->getMessage());
       }

   }

    public function setStartTime()
    {
        $this->startTime = time();
    }

    public function isOverTime()
    {
        return (time() - $this->startTime) > $this->timeout;
    }

    public function run()
    {

        try{
            $this->setStartTime();

            $pool = Pool::create();
            
            $pool->add(function(){

                $result = $this->execute($this->command);
                return $result;
            });
            $results = await($pool);

            return $results;

        } catch(\Exception $exception){
            $this->setError($exception->getMessage());
        }
    }



}
