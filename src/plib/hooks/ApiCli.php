<?php

class Modules_Welcome_ApiCli extends \pm_Hook_ApiCli
{
    /**
     * @var \PleskExt\Welcome\Config
     */
    private $config;

    /**
     * @var \pm_ServerFileManager
     */
    private $serverFileManager;

    public function __construct()
    {
        $this->config = new \PleskExt\Welcome\Config;
        $this->serverFileManager = new \pm_ServerFileManager;
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

    public function listCommand()
    {
        $this->writeLine('Available presets:');

        foreach ($this->config->getPresets() as $preset)
        {
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
            $this->writeLine($this->serverFileManager->fileGetContents($this->config::CONFIG_FILE));
        } else {
            $file = $this->config::PRESET_DIR . '/' . $preset . '.json';

            if (!$this->serverFileManager->fileExists($file)) {
                $this->error('Unknown configuration preset: ' . $preset);
            }

            $this->writeLine($this->serverFileManager->fileGetContents($file));
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
