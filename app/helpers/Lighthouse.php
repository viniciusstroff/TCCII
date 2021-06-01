<?php
namespace App\Helpers;

use App\Console\Process;
use App\Exceptions\AuditFailedException;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Boolean;
use Spatie\Async\Pool;
use Symfony\Component\Process\Process as ProcessProcess;

class Lighthouse {
    protected ?String $site = null;
    protected String $lighthousePath  = '/node_modules/lighthouse/lighthouse-cli/index.js';
    protected ?String $command = null;
    protected ?String $nodePath = null;
    protected ?String $configPath = null;
    protected Array $outputFormat = ['json'];
    protected String $outputPath = 'storage/reports/';
    protected String $outputFile = "myfile234.report";
    protected Array $headers = [];
    protected Array $options = [];
    protected $config = null;
    protected Array $categories = [];
    protected $environmentVariables = [];
    protected $timeout = 60;
    protected String $defaultFormat = 'json';
    protected String $format = "";

    protected String $relativePath = "";

  

    protected Bool $hasFinished = false;

    protected Array $availableCategories = ["accessibility", "best-practices", "performance", "pwa", "seo" ];
    protected Array $availableFormats = ['json', 'html'];

    public $process;


    public function __construct(String $site = null)
    {
        
    }

    public function build(String $site)
    {
        $this->setSite($site);
        
        $this->setNodePath();
        $this->setOutputPath();
        $this->setOutputFormat('json');
        $this->setTimeOut(60);
        $this->setOutputFile();
        $this->setOutput();
        $this->setLogging();
        $this->setChromeFlags(['--disable-gpu', '--no-sandbox']);
        $this->setOption("--verbose", true);
    }
    public function configure()
    {

    }

    public function setChromeFlags($flags)
    {
        if (is_array($flags)) {
            $flags = implode(' ', $flags);
        }

        $this->setOption('--chrome-flags', "'$flags'");

        return $this;
    }
    public function setNodePath()
    {   
        $path = str_replace(["\\", "//"], "/", base_path($this->lighthousePath));
        $this->nodePath = $path;
    }

    public function setOutputPath()
    {
        $fullPath = FileHelper::createDirBySite($this->site);
        $this->outputPath = $fullPath;
        $this->setRelativePath();
    }

    public function setRelativePath()
    {
        $relativePath = str_replace(storage_path('app\reports'), "", $this->outputPath);
        $this->relativePath = $relativePath;
    }

    public function setOutputFile(?String $filename = null)
    {
        $filename = UrlHelper::getOnlySiteName($this->site);
        $time = Carbon::now()->format('H-i-s');
        $file =  "{$this->relativePath}/{$filename}-{$time}.{$this->format}";
        $file = str_replace(["\\", "//"], "/", $file);
        $this->outputFile = $file;
    }

    public function setOutput(?String $file = null)
    {
        $basePath = storage_path('app\reports');
        $file = is_null($file) ? $this->outputFile : $file;
        $fullPath = "{$basePath}{$file}";
        $this->setOption('--output-path', $fullPath);
    }

    public function setSite(?String $url)
    {
        if(empty($url)) return;
        $this->site = $url;
       
    }
    
    public function getSite()
    {
        return $this->site;
    }
    public function setCommand(String $url)
    {

        // $outputFormat = $this->getOutputFormat();
        $options = $this->getOptions();

        $command = "{$this->command} {$this->nodePath}{$this->lighthousePath} {$url}";
        $this->command = $command;
    }

    public function setOutputFormat(String $format)
    {   
        $this->format = $format;
        if(in_array($format, $this->availableFormats))
            $this->setOption('--output', $format);
     

    }

    public function getOutputFormat() : String 
    {

        return $this->format;
    }

    public function getOutputFile() : String 
    {
        
        return $this->outputFile;
    }

    public function getOptions() : String
    {
        if(!empty($this->options)){
            $options = array_values($this->options);
            return implode(" ", $options);   
        }
    }

    public function hasFinished()
    {
        return $this->hasFinished;
    }

    public function getCommand(String $url)
    {
        if ($this->configPath === null || $this->config !== null) {
            $this->buildConfig();
        }
        $command = array_merge([
            "node",
            $this->nodePath,
            $url,
            // ...$this->outputFormat,
            // ...$this->headers,
            // '--quiet',
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
    protected function setLogging()
    {
        $this->setOption("--save-assets", true);
    }

    protected function processOptions()
    {
        $processOptions = array_map(function ($value, $option) {
            return is_numeric($option) ? $value : "$option=$value";
        }, $this->options, array_keys($this->options));

        return $processOptions;
    }

    public function setOption(String $option, ?String $value = null)
    {
       
        if (($foundIndex = array_search($option, $this->options)) !== false) {
            $this->options[$foundIndex] = $option;
            return $this;
        }

        if (is_null($value)) {
            $this->options[] = "{$option}";
        } else {
            $this->options[$option] = "{$value}";
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

    public function setCategories(Array $categories = [])
    {
        $categories = implode(",", $categories);
        $this->setOption("--only-categories", $categories);
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

    public function getOutput() 
    {
        // if($this->isRunning()){
            if(!file_exists($this->outputFile))
                dd("arquivo {$this->outputFile} nao econtrado");

            $content = Storage::disk('local')->get($this->outputFile);
            $object = json_decode($content);
            $json = $this->mapOnlyNecessaryToJson($object);
        
            return $json;
        // }
        
    }
    public function isRunning()
    {
        $start = microtime(true);   
        while(!file_exists($this->outputFile)){
            if (file_exists($this->outputFile)) {
                $this->hasFinished = true;
                break;
            }
            if((microtime(true) - $start ) >= $this->timeout){
                break;
            }
        }
    }

    private function mapOnlyNecessaryToJson(Object $object)
    {
        $array = [
            'categories' => $object->categories,
            'timing' => $object->timing
        ];
        $json = json_encode($array);
        return $json;
    }

    public function setTimeOut($timemout = 30)
    {
        $this->timeout = $timemout;
        $this->setOption('--max-wait-for-load', $timemout);
    }

    public function audit()
    {

        try{
            $this->hasFinished = false;
            $this->setCommand($this->site);
            $command = $this->getCommand($this->site);
            // . " > /dev/null &" //executar em segundo plano

     
                // $pool = Pool::create();

                // $pool->add(function() use($command){
                  
                        $process = new Process($command);
                        $process->setTimeout($this->timeout);
                        
                        $results = $process->run();
                //     return $results;
                // })->then(function ($results)  {
                //     // dd($results);

                // });
                
                // $results = await($pool);
               

                $this->hasFinished = true;
                // $this->isRunning();
            
            return $results;
        }catch(\Exception $e){
            dd($e->getMessage());
        }   
        return $this;
    }
}
