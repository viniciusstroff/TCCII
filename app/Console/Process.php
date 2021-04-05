<?php

namespace App\Console;

class Process
{
    protected String $command;
    protected Int $timeout;
    protected $output;
    protected ?String $defaultPath = null;
    protected ?String $defaultOutputPath = "./app/console/outputs/";
    protected ?String $error = null;
    protected Bool $hasFinished = false;
    protected Bool $captureStdErr = true;
    protected ?Int $exitCode = null;

    public function __construct(String $command, ?Array $arguments = [])
    {
        $this->command = $command;

        $this->setDefaultPath();
        $this->setDefaultOutputPath('');
        $this->setArguments($arguments);
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
            $this->error = 'Could not locate any executable command';
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
        return $removeSpaces ? trim($this->output) : $this->output;
    }

    public function setPath(?String $path)
    {
        $this->defaultPath = base_path($path);
    }

    public function goTo(?String $path) {
        $this->output = $this->execute("cd $path");
    }

    public function getError($escapeSpaces = true)
    {
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

    public function execute()
    {
       try{
        //    $command = $this->getCommand();
        exec("dir", $output, $this->exitCode);

           dd($output);
           return $this->output;
       }catch(\Exception $e)
       {
           dd($e->getMessage());
       }

   }
    public function execute2(String $command)
     {
        try{
            $this->output = shell_exec("$command > ". $this->getDefaultOutputPath() . "teste.txt");
            return $this->output;
        }catch(\Exception $e)
        {
            dd($e->getMessage());
        }

    }

    public function run()
    {
        try{
            // $teste = $this->goTo('cd '. $this->getBasePath());
            $teste2 = $this->execute('');
            dd($teste2);
            $this->output = shell_exec($this->command);
        }catch(\Exception $e)
        {
            dd($e->getMessage());
        }
    }



}
