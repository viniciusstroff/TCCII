<?php
namespace App\Helpers;

use App\Exceptions\AuditFailedException;
use Symfony\Component\Process\Process;

class Lighthouse {
    protected Array $sites = [];
    protected String $lighthousePath  = './../../node_modules/lighthouse/lighthouse-cli/index.js';
    protected ?String $nodePath = null;
    protected ?String $configPath = null;
    protected Array $outputFormat = ['--output=json'];
    protected Array $headers = [];
    protected Array $options = [];
    protected $config = null;
    protected Array $categories = [];
    protected $environmentVariables = [];
    protected $timeout = 60;

    protected Array $availableFormats = ['json', 'html'];


    public function __construct(array $sites)
    {
        $this->sites = $sites;

        // $this->setOutput('./saidas/report.json');
        $this->audit('https://www.google.com');
    }


    public function audit(String $url){

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

    public function setOutput($path, $format = null)
    {
        $this->setOption('--output-path', $path);

        if ($format === null) {
            $format = $this->guessOutputFormatFromFile($path);
        }

        if (!is_array($format)) {
            $format = [$format];
        }

        $format = array_intersect($this->availableFormats, $format);

        $this->outputFormat = array_map(function ($format) {
            return "--output=$format";
        }, $format);

        return $this;
    }

    public function setOption($option, $value = null)
    {
        if (($foundIndex = array_search($option, $this->options)) !== false) {
            $this->options[$foundIndex] = $option;

            return $this;
        }

        if ($value === null) {
            $this->options[] = $option;
        } else {
            $this->options[$option] = $value;
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
