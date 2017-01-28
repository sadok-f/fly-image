<?php

namespace Core\Entity;

use Core\Exception\ReadFileException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Image
 * @package Core\Entity
 */
class Image
{

    /** Content TYPE */
    const WEBP_CONTENT_TYPE = 'image/webp';
    const JPEG_CONTENT_TYPE = 'image/jpeg';

    /** @var array */
    protected $options = [];

    /** @var string */
    protected $sourceFile;

    /** @var string */
    protected $newFileName;

    /** @var string */
    protected $newFilePath;

    /** @var string */
    protected $temporaryFile;

    /** @var string */
    protected $commandString;

    /** @var array */
    protected $defaultParams;

    /** @var  Request */
    protected $request;

    /**
     * Image constructor.
     * @param string $options
     * @param $sourceFile
     * @param $defaultParams
     */
    public function __construct($options, $sourceFile, $defaultParams)
    {
        $this->defaultParams = $defaultParams;
        $this->options = $this->parseOptions($options);
        $this->sourceFile = $sourceFile;

        $this->request = Request::createFromGlobals();
        $this->saveToTemporaryFile();
        $this->generateFilesName();
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getSourceFile()
    {
        return $this->sourceFile;
    }

    /**
     * @param string $sourceFile
     */
    public function setSourceFile($sourceFile)
    {
        $this->sourceFile = $sourceFile;
    }

    /**
     * @return string
     */
    public function getNewFileName()
    {
        return $this->newFileName;
    }

    /**
     * @param string $newFileName
     */
    public function setNewFileName($newFileName)
    {
        $this->newFileName = $newFileName;
    }

    /**
     * @return string
     */
    public function getNewFilePath()
    {
        return $this->newFilePath;
    }

    /**
     * @param string $newFilePath
     */
    public function setNewFilePath($newFilePath)
    {
        $this->newFilePath = $newFilePath;
    }

    /**
     * @return string
     */
    public function getTemporaryFile()
    {
        return $this->temporaryFile;
    }

    /**
     * @param $commandStr
     */
    public function setCommandString($commandStr)
    {
        $this->commandString = $commandStr;
    }

    public function getCommandString()
    {
        return $this->commandString;
    }

    /**
     * Parse options: match options keys and merge default options with given ones
     *
     * @param $options
     * @return array
     */
    protected function parseOptions($options)
    {
        $defaultOptions = $this->defaultParams['default_options'];
        $optionsKeys = $this->defaultParams['options_keys'];
        $optionsSeparator = !empty($this->defaultParams['options_separator']) ?
            $this->defaultParams['options_separator'] : ',';
        $optionsUrl = explode($optionsSeparator, $options);
        $options = [];
        foreach ($optionsUrl as $option) {
            $optArray = explode('_', $option);
            if (key_exists($optArray[0], $optionsKeys) && !empty($optionsKeys[$optArray[0]])) {
                $options[$optionsKeys[$optArray[0]]] = $optArray[1];
            }
        }
        return array_merge($defaultOptions, $options);
    }


    /**
     * Save given image to temporary file and return the path
     *
     * @throws \Exception
     */
    protected function saveToTemporaryFile()
    {
        if (!$resource = @fopen($this->getSourceFile(), "r")) {
            throw  new ReadFileException('Error occurred while trying to read the file Url : '
                . $this->getSourceFile());
        }
        $content = "";
        while ($line = fread($resource, 1024)) {
            $content .= $line;
        }
        $this->temporaryFile = TMP_DIR . uniqid("", true);
        file_put_contents($this->temporaryFile, $content);
    }

    /**
     * Extract a value from given array and unset it.
     *
     * @param $key
     * @param $remove
     * @return null
     */
    public function extractByKey($key, $remove = true)
    {
        $value = null;
        if (isset($this->options[$key])) {
            $value = $this->options[$key];
            if ($remove) {
                unset($this->options[$key]);
            }
        }
        return $value;
    }

    /**
     * Remove the generated files
     */
    public function unlinkUsedFiles()
    {
        if (file_exists($this->getTemporaryFile())) {
            unlink($this->getTemporaryFile());
        }
        if (file_exists($this->getNewFilePath())) {
            unlink($this->getNewFilePath());
        }
    }

    /**
     * Generate files name + files path
     */
    protected function generateFilesName()
    {
        $hashedOptions = $this->options;
        unset($hashedOptions['refresh']);
        $this->newFileName = md5(implode('.', $hashedOptions) . $this->sourceFile);
        $this->newFilePath = TMP_DIR . $this->newFileName;

        if ($this->options['refresh']) {
            $this->newFilePath .= uniqid("-", true);
        }
        if ($this->isWebPSupport()) {
            $this->newFilePath .= '.webp';
        }
    }

    /**
     * @return bool
     */
    public function isWebPSupport()
    {
        return in_array(self::WEBP_CONTENT_TYPE, $this->request->getAcceptableContentTypes())
            && $this->extractByKey('webp-support', false);
    }

    /**
     * @return string
     */
    public function getResponseContentType()
    {
        return $this->isWebPSupport() ? self::WEBP_CONTENT_TYPE : self::JPEG_CONTENT_TYPE;
    }
}
