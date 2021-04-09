<?php
namespace App\Helpers;

use App\Console\Process;
use App\Exceptions\AuditFailedException;

class Lighthouse2 {
    protected Array $sites = [];
    protected String $lighthousePath  = './../../node_modules/lighthouse/lighthouse-cli/index.js';
    protected String $command = "node";
    protected ?String $nodePath = null;
    protected ?String $configPath = null;
    protected Array $outputFormat = ['--output=json'];
    protected Array $outputPath = ['--output-path /app/console/outputs/'];
    protected String $outputFile = "myfile23.json";
    protected Array $headers = [];
    protected Array $options = [];
    protected $config = null;
    protected Array $categories = [];
    protected $environmentVariables = [];
    protected $timeout = 60;
    protected $defaultFormat;


    protected Array $availableFormats = ['json', 'html'];

    public $process;


    public function __construct(Array $sites)
    {
        // dd(getcwd());
        $this->sites = $sites;
        $this->setOutput(base_path().'/app/console/outputs/');
        $this->setNodePath();
        // $this->audit();
    }

    public function setNodePath(){
        $this->nodePath = base_path().'/node_modules/lighthouse/lighthouse-cli/index.js';
    }

    public function setCommand(String $url)
    {

        $outputFormat = implode(' ',$this->outputFormat);
        $options = implode(' ', $this->getOptions());

        $command = "{$this->command} {$this->nodePath} {$url} {$outputFormat} {$options}";

        $this->command = $command;
    }

    public function getOptions() : Array
    {
        if(!empty($this->options))
            return array_values($this->options);
    }



    public function audit()
    {
        // foreach($this->sites as $url){

            $this->setCommand('https://www.google.com.br/');
            // $path = base_path(). '/app/console/outputs/myfile23.json';
            // dd($this->command, "npm exec -c 'lighthouse https://www.google.com.br/ --output=json --output-path {$path}' ");

            // $process = new Process(['npm exe -c "lighthouse https://www.google.com.br/ --output=json --output-path '. $path]);

            // $process->run();
            $this->process = new Process([$this->command]);
            $this->process->run();
            if($this->process->hasError()){
                // throw new AuditFailedException($url, $process->getErrorOutput());
                dd('erro');
            }
            // $process->getOutput();


            // dd();
            // dd($process->getErrorOutput());exit;
            // $process->setTimeout($this->timeout)->run(null, $this->environmentVariables);
            // if (!$process->isSuccessful()) {
            //     throw new AuditFailedException($url, $process->getErrorOutput());
            // }

            // return $process->getOutput();


        // }
        return $this;
    }

    public function getCommand(String $url)
    {
        if ($this->configPath === null || $this->config !== null) {
            $this->buildConfig();
        }

        $command = array_merge([
            $this->nodePath,
            $this->lighthousePath,
            ...$this->outputFormat,
            ...$this->headers,
            '--quiet',
            "--config-path={$this->configPath}",
            $url,
        ], $this->processOptions());

        return array_filter($command);
    }

    protected function buildConfig()
    {
        $config = tmpfile();
        $this->withConfig(stream_get_meta_data($config)['uri']);
        $this->config = $config;

        $r = 'module.exports = ' . json_encode([
                'extends' => 'lighthouse:default',
                'settings' => [
                    'onlyCategories' => $this->categories,
                ],
            ]);
        fwrite($config, $r);

        return $this;
    }

    public function withConfig($path)
    {
        if ($this->config) {
            fclose($this->config);
        }

        $this->configPath = $path;
        $this->config = null;

        return $this;
    }

    protected function processOptions()
    {
        return array_map(function ($value, $option) {
            return is_numeric($option) ? $value : "$option=$value";
        }, $this->options, array_keys($this->options));
    }

    public function setOutput(String $path)
    {
        $path = str_replace(["\\", "//"], "/", $path);
        $this->setOption('--output-path', $path.$this->outputFile);
    }

    public function setOption(String $option, String $value){

        if (($foundIndex = array_search($option, $this->options)) !== false) {
            $this->options[$foundIndex] = $option;
            return $this;
        }

        if ($value === null) {
            $this->options[] = "{$option}";
        } else {
            $this->options[$option] = "{$option} {$value}";
        }

        return $this;
    }

    private function guessOutputFormatFromFile($path)
    {
        $format = pathinfo($path, PATHINFO_EXTENSION);

        if (!in_array($format, $this->availableFormats)) {
            $format = $this->defaultFormat;
        }

        return $format;
    }

    protected function setCategory($category, $enable)
    {
        $index = array_search($category, $this->categories);

        if ($index !== false) {
            if ($enable == false) {
                unset($this->categories[$index]);
            }
        } elseif ($enable) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function accessibility($enable = true)
    {
        $this->setCategory('accessibility', $enable);

        return $this;
    }
}
