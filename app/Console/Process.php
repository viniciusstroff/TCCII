<?php

namespace App\Console;

use Illuminate\Support\Facades\Log;

//NPM exe lighthouse https://www.google.com.br

//npm exe -c "lighthouse https://www.google.com.br/ --output=json --output-path ./app/console/outputs/myfile2.json"
class Process
{
    protected String $command;
    protected Int $timeout;
    protected Array $output;
    protected ?String $defaultPath = null;
    protected ?String $defaultOutputPath = "/app/console/outputs/myfile23.json";
    protected ?String $error = null;
    protected Bool $hasFinished = false;
    protected Bool $hasError = false;
    protected Bool $captureStdErr = true;
    protected ?Int $exitCode = null;
    protected String $outputFilename = "myfile23.json";

    public function __construct(Array $commands, ?Array $arguments = [])
    {
        $this->constructCommandByArray($commands);
        $this->setDefaultPath();
        // $this->setDefaultOutputPath('');
        // $this->setArguments($arguments);
    }

    private function constructCommandByArray(Array $commands)
    {
        if(empty($commands)) return;

        $this->command =  implode(" ", $commands);
    }

    public function setTimeout(Int $timeout = 60)
    {
        $this->timeout = $timeout;
    }

    private function setDefaultOutputPath(?String $path = '')
    {
        $this->defaultOutputPath = $this->defaultPath . $this->defaultOutputPath;
    }

    public function getDefaultOutputPath() : String
    {
        return $this->defaultOutputPath;
    }

    public function getCommand() : String
    {
        return $this->command;
    }

    public function getExecCommand() : String
    {

        $command = $this->getCommand();
        if (!$command) {
            $this->setError('Could not locate any executable command');
            return false;
        }

        $args = $this->getArguments();
        return $args ? $command.' '.$args : $command;
    }

    private function setDefaultPath(?String $path = '')
    {
        $basePath = base_path($path);
        $basePath = str_replace(["\\", "//"], "/", $basePath);
        $this->defaultPath = $basePath;
    }

    public function setArguments(Array $arguments)
    {
        $this->arguments = $arguments;
    }

    public function getArguments() : String
    {
        return implode(' ',$this->arguments);
    }

    public function getBasePath(): String
    {
        return $this->defaultPath;
    }

    public function getOutput(bool $removeSpaces = true)
    {
        if($this->hasFinished()){

            $file = $this->defaultPath. $this->defaultOutputPath;
            // dd($file);
            if(file_exists($file)){

                $teste = file_get_contents($file);
                dd($teste);
            }
            if($this->hasError()) return $this->getError();

            if(!is_array($this->output))
                return $removeSpaces ? trim($this->output) : $this->output;

            $output = array_map(function($output, $removeSpaces){
                return $removeSpaces ? trim($output) : $output;
            }, $this->output, [$removeSpaces]);


            if(is_null($output)){

            }
            return $output;
        }
    }

    public function setPath(?String $path)
    {
        $this->defaultPath = base_path($path);
    }

    public function goTo(?String $path)
    {
        $this->output = $this->execute("cd $path");
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

    public function execute(?String $command = null)
    {

       try{
            $output=null;
           if(!$this->command)
                $this->command = $command;

            // dd($this->command);
            exec($this->command, $output, $this->exitCode);
            $this->output = $output;
            $this->setHasFinished(true);
       }catch(\Exception $exception)
       {
           $this->setError($exception->getMessage());
       }

   }

    public function run()
    {

        try{
            $this->execute($this->command);

        } catch(\Exception $exception){
            $this->setError($exception->getMessage());
        }
    }



}
