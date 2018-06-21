<?php

namespace PleskExt\Welcome;

class Config
{
    const CONFIG_FILE = '/usr/local/psa/var/modules/welcome/config.json';
    const PRESET_DIR = '/usr/local/psa/var/modules/welcome/presets';
    const DEFAULT_LOCALE = 'en-US';
    const DEFAULT_LOCALE_KEY = 'default';

    /**
     * @var \pm_ServerFileManager
     */
    private $serverFileManager;

    /**
     * @var string
     */
    private $currentLocale;

    public function __construct()
    {
        $this->serverFileManager = new \pm_ServerFileManager;
        $this->currentLocale = \pm_Locale::getCode();
    }

    /**
     * @param array  $arr
     * @param string $error
     *
     * @return bool
     */
    private function validateLanguage(array $arr, &$error = '')
    {
        if (!isset($arr['title'])) {
            $error = 'Missing required parameter: title';

            return false;
        }

        if (!isset($arr['description'])) {
            $error = 'Missing required parameter: description';

            return false;
        }

        if (!isset($arr['groups'])) {
            $error = 'Missing required parameter: groups';

            return false;
        }

        if (!is_array($arr['groups']) || empty($arr['groups'])) {
            $error = 'At least one group must be defined';

            return false;
        }

        foreach ($arr['groups'] as $groupIdx => $group) {
            if (!isset($group['title'])) {
                $error = "Group #{$groupIdx} is missing required attribute: title";

                return false;
            }

            if (!isset($group['steps'])) {
                $error = "Group '{$group['title']}' is missing required attribute: steps";

                return false;
            }

            if (!is_array($group['steps']) || empty($group['steps'])) {
                $error = "Group '{$group['title']}' must define at least one step";

                return false;
            }

            foreach ($group['steps'] as $stepIdx => $step) {
                if (!isset($step['title'])) {
                    $error = "Step #{$stepIdx} in group '{$group['title']}' is missing required attribute: title";

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param string $json
     * @param string $error
     *
     * @return bool
     */
    private function validate($json, &$error = '')
    {
        $arr = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = 'Invalid or malformed JSON';

            return false;
        }

        if (!isset($arr[self::DEFAULT_LOCALE_KEY])) {
            $error = 'Missing "default" language';

            return false;
        }

        foreach ($arr as $langCode => $langData) {
            $isValidLangData = $this->validateLanguage($langData, $langError);

            if (!$isValidLangData) {
                $error = $langError . " (language: {$langCode})";

                return false;
            }
        }

        return true;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function replace($text)
    {
        $text = htmlspecialchars(strip_tags($text));

        preg_match_all('/%%+(.*?)%%/', $text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $placeholder = $match[0];
            $contents = $match[1];

            if ($placeholder === '%%name%%') {
                $userName = '[Current user name]'; // TODO: Get current user name
                $text = str_replace($placeholder, $userName, $text);
            } else {
                $segments = explode('|', $contents);
                $action = $segments[0];

                if ($action === 'install') {
                    if (count($segments) !== 4) {
                        throw new \Exception('Invalid number of parameters for action: ' . $action);
                    }

                    $extId = $segments[1];
                    $installText = $segments[2];
                    $openText = $segments[3];
                    $isInstalled = false; // TODO: Determine if extension is installed
                    $replacement = $isInstalled ? $openText : $installText;

                    preg_match_all('/{{+(.*?)}}/', $replacement, $matches2, PREG_SET_ORDER);

                    foreach ($matches2 as $match2) {
                        $extLink = $isInstalled ? '/open' : '/install'; // TODO: Get extension install / open link

                        if ($match2[1] === 'name') {
                            $extName = '[extname]'; // TODO: Get extension name by ID
                            $replacement = str_replace($match2[0], '<a href="' . $extLink . '">' . $extName . '</a>', $replacement);
                        } else {
                            $replacement = str_replace($match2[0], '<a href="' . $extLink . '">' . $match2[1] . '</a>', $replacement);
                        }
                    }

                    $text = str_replace($placeholder, $replacement, $text);
                } elseif ($action === 'extlink') {
                    if (count($segments) !== 2) {
                        throw new \Exception('Invalid number of parameters for action: ' . $action);
                    }

                    $extId = $segments[1];
                    $extName = '[extname]'; // TODO: Get extension name by ID
                    $extLink = '/open-local'; // TODO: Get extension open link

                    $text = str_replace($placeholder, '<a href="' . $extLink . '">' . $extName . '</a>', $text);
                } elseif ($action === 'extname') {
                    if (count($segments) !== 2) {
                        throw new \Exception('Invalid number of parameters for action: ' . $action);
                    }

                    $extId = $segments[1];
                    $extName = '[extname]'; // TODO: Get extension name by ID

                    $text = str_replace($placeholder, $extName, $text);
                } elseif ($action === 'link') {
                    if (count($segments) !== 4) {
                        throw new \Exception('Invalid number of parameters for action: ' . $action);
                    }

                    $linkTitle = $segments[1];
                    $linkUrl = $segments[2];
                    $openInNewWindow = ($segments[3] == 'true') ? true : false;
                    $linkParams = $openInNewWindow ? ' target="_blank"' : '';

                    $text = str_replace($placeholder, '<a href="' . $linkUrl . '"' . $linkParams . '>' . $linkTitle . '</a>', $text);
                } elseif ($action === 'image') {
                    if (count($segments) < 2) {
                        throw new \Exception('Invalid number of parameters for action: ' . $action);
                    }

                    $url = $segments[1];
                    $style = '';

                    if (isset($segments[2]) && in_array($segments[2], ['left', 'right'])) {
                        $style = 'style="float: ' . $segments[2] . ';" ';
                    }

                    $text = str_replace($placeholder, '<img src="' . $url . '" ' . $style . '/>', $text);
                } elseif ($action === 'format') {
                    if (count($segments) !== 3) {
                        throw new \Exception('Invalid number of parameters for action: ' . $action);
                    }

                    $type = $segments[1];
                    $str = $segments[2];

                    $formats = [
                        'bold'      => [
                            'before' => '<strong>',
                            'after'  => '</strong>',
                        ],
                        'italic'    => [
                            'before' => '<em>',
                            'after'  => '</em>',
                        ],
                        'underline' => [
                            'before' => '<u>',
                            'after'  => '</u>',
                        ],
                    ];

                    if (!isset($formats[$type])) {
                        throw new \Exception('Unknown format type: ' . $type);
                    }

                    $text = str_replace($placeholder, $formats[$type]['before'] . $str . $formats[$type]['after'], $text);
                } else {
                    throw new \Exception('Invalid action: ' . $action);
                }
            }
        }

        $text = str_replace(["\r\n", "\r", "\n"], '<br />', $text);

        return $text;
    }

    /**
     * @return string
     */
    public function load()
    {
        $configPresetFilePath = \pm_Settings::get('configPresetFilePath');

        if (!empty($configPresetFilePath) && $this->serverFileManager->fileExists($configPresetFilePath)) {
            return $this->serverFileManager->fileGetContents($configPresetFilePath);
        }

        return $this->serverFileManager->fileGetContents(self::CONFIG_FILE);
    }

    /**
     * @return string
     */
    public function preprocess()
    {
        $json = $this->load();
        $arr = json_decode($json, true);
        $locale = isset($arr[$this->currentLocale]) ? $this->currentLocale : self::DEFAULT_LOCALE_KEY;

        $arr[$locale]['title'] = $this->replace($arr[$locale]['title']);
        $arr[$locale]['description'] = $this->replace($arr[$locale]['description']);

        foreach ($arr[$locale]['groups'] as $groupIdx => $group) {
            $arr[$locale]['groups'][$groupIdx]['title'] = $this->replace($group['title']);

            foreach ($group['steps'] as $stepIdx => $step) {
                $arr[$locale]['groups'][$groupIdx]['steps'][$stepIdx]['title'] = $this->replace($step['title']);
                $arr[$locale]['groups'][$groupIdx]['steps'][$stepIdx]['description'] = $this->replace($step['description']);
            }
        }

        return json_encode($arr[$locale]);
    }

    /**
     * @param string $json
     *
     * @throws \InvalidArgumentException if the JSON is not valid
     */
    public function save($json)
    {
        if (!$this->validate($json, $error)) {
            throw new \InvalidArgumentException('JSON validation failed: ' . $error);
        }

        $this->serverFileManager->filePutContents(self::CONFIG_FILE, $json);
        \pm_Settings::set('configPresetFilePath', self::CONFIG_FILE);
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException if preset does not exist
     */
    public function updateDefaultConfigFromPreset($name)
    {
        $presetFile = self::PRESET_DIR . '/' . $name . '.json';

        if (!$this->serverFileManager->fileExists($presetFile)) {
            throw new \InvalidArgumentException('Unknown configuration preset: ' . $name);
        }

        if ($this->serverFileManager->fileExists(self::CONFIG_FILE)) {
            $this->serverFileManager->removeFile(self::CONFIG_FILE);
        }

        $this->serverFileManager->copyFile($presetFile, self::CONFIG_FILE);
        \pm_Settings::set('configPresetFilePath', $presetFile);
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException if preset does not exist
     */
    public function createDefaultConfigFromPreset($name)
    {
        if ($this->serverFileManager->fileExists(self::CONFIG_FILE)) {
            return;
        }

        $this->updateDefaultConfigFromPreset($name);
    }

    /**
     * @return array
     */
    public function getPresets()
    {
        $paths = glob(self::PRESET_DIR . '/*.json');
        $presets = [];

        foreach ($paths as $path) {
            if (!$this->serverFileManager->fileExists($path)) {
                continue;
            }

            $presets[] = pathinfo($path, PATHINFO_FILENAME);
        }

        return $presets;
    }
}
