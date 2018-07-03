<?php

use \PleskExt\Welcome\Config;

class Modules_Welcome_ApiCli extends \pm_Hook_ApiCli
{
    /**
     * @var \PleskExt\Welcome\Config
     */
    private $config;

    public function __construct()
    {
        $this->config = new Config;
    }

    /**
     * @param string $line
     */
    private function writeLine($line)
    {
        $this->stdout($line . PHP_EOL);
    }

    /**
     * @param string $message
     */
    private function error($message)
    {
        $this->stderr($message . PHP_EOL);

        exit(1);
    }

    public function helpCommand()
    {
        $this->writeLine('Available commands:');
        $this->writeLine('    --help      Display this help page');
        $this->writeLine('    --list      Show a list of available preset names');
        $this->writeLine('    --show      Display current or preset configuration');
        $this->writeLine('    --select    Overwrite current configuration with preset configuration');
        $this->writeLine('    --input     Overwrite current configuration with JSON from an external URL');
        $this->writeLine('');
        $this->writeLine('Examples:');
        $this->writeLine('    Get a list of available presets             plesk ext welcome --list');
        $this->writeLine('    Show current configuration                  plesk ext welcome --show');
        $this->writeLine('    Show preset configuration                   plesk ext welcome --show -preset wordpress');
        $this->writeLine('    Update current configuration from preset    plesk ext welcome --select -preset business');
        $this->writeLine('    Update current configuration from URL       plesk ext welcome --input -url http://example.com/config.json');
    }

    public function listCommand()
    {
        foreach ($this->config->getPresets() as $preset) {
            $this->writeLine('  ' . $preset);
        }
    }

    /**
     * @param string $preset
     */
    public function showCommand($preset)
    {
        $preset = trim($preset);

        if ($preset === '') {
            $this->writeLine(file_get_contents(\pm_Context::getVarDir() . $this->config::CONFIG_FILE));
        } else {
            $file = \pm_Context::getVarDir() . $this->config::PRESET_DIR . '/' . $preset . '.json';

            if (!file_exists($file)) {
                $this->error('Unknown configuration preset: ' . $preset);
            }

            $this->writeLine(file_get_contents($file));
        }
    }

    /**
     * @param string $preset
     */
    public function selectCommand($preset)
    {
        try {
            $this->config->updateDefaultConfigFromPreset($preset);
        }
        catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * @param string $url
     */
    public function inputCommand($url)
    {
        $client = new \Zend_Http_Client($url);

        try {
            $response = $client->request();

            if ($response->isError()) {
                throw new \Exception($response->getStatus() . ': ' . $response->getMessage());
            }

            $json = $response->getBody();

            $this->config->save($json);
        }
        catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
