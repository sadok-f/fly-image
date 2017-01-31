<?php

namespace Core\Entity;

use Core\Exception\AppException;
use Core\Exception\ReadFileException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Image
 * @package Core\Entity
 */
class Image
{
    /** Bin path */
    const IM_IDENTITY_COMMAND = '/usr/bin/identify';

    /** Content TYPE */
    const WEBP_MIME_TYPE = 'image/webp';
    const JPEG_MIME_TYPE = 'image/jpeg';
    const PNG_MIME_TYPE = 'image/png';

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

    /** @var array Associative array with data about the source file */
    protected $temporaryFileInfo;

    /** @var string */
    protected $commandString;

    /** @var array */
    protected $defaultParams;

    /** @var  Request */
    protected $request;

    /** @var  Information Map to get and store file info */
    protected $defaultInfoMap = [
        'mime'               => '%m',
        'colorDepth'         => '%[type]',
        'width'              => '%[width]',
        'height'             => '%[height]',
        'compressionAmmount' => '%Q',
        'compressionType'    => '%C',
        'alphaChannel'       => '%A'
        ];

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
     * @return string
     */
    public function getTemporaryFileInfo()
    {
        return $this->temporaryFileInfo;
    }

    /**
     * @return string
     */
    public function getSourceMimeType()
    {
        if (empty($this->temporaryFileInfo) || empty($this->temporaryFileInfo['mime'])) {
            return '';
        }
        return strtolower('image/'.$this->temporaryFileInfo['mime']);
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
     * Get the image Identity information
     * For formating details see https://www.imagemagick.org/script/escape.php
     * @param array    $properties associative array of the properties to check.
     * @return string
     */
    public function getInfo($properties = [])
    {
        // @todo: check if the temporatyFile path has changed, if not, return the latest result.
        $formating = ' ';
        if(count($properties)) {
            $formating = ' -format "' . implode(' ', $properties) . '" ';
        }
        $output = $this->execute(self::IM_IDENTITY_COMMAND . $formating . $this->temporaryFile);
        return $this->parseImageInfo($output, $properties);
    }

    /**
     * Parse the info output for an IM idetify 
     * it will be parsed according to the $propMap using it's keys
     * @param  array    $stdOut  Theoutput from exec afrer calling the image magic identify
     * @param  array    $propMap associative array that was used in the getInfo
     * @return array             Same keys as propMap but with the falues populated.
     */
    protected function parseImageInfo($stdOut, $propMap) {
        if(empty($stdOut[0])) {
            return [];
        }

        // if no property Map was given return raw output
        if(empty($propMap)) {
            !empty($output[0]) ? $output[0] : "";
        }
        $outputProps = explode(' ', $stdOut[0]);
        $count = 0;
        foreach ($propMap as $key => $value) {
            $propMap[$key] = $outputProps[$count];
            $count++;
        }
        return $propMap;
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
     * Save given image to temporary file and store the path
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
        $this->temporaryFileInfo = $this->getInfo($this->defaultInfoMap);
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

            // does this make a new path instead of ovewriting the the file with the same hash?
            // does this prevent the cache from being updated?
            $this->newFilePath .= uniqid("-", true);
        }

        // setting file extension, this should be moved to it's own method.
        $fileExtension = '.jpg';
        if($this->getSourceMimeType() === PNG_MIME_TYPE) {
            $fileExtension = '.png';
        }
        if ($this->isWebPSupport() || $this->getSourceMimeType() === WEBP_MIME_TYPE) {
            $fileExtension = '.webp';
        }
        $this->newFilePath .= $fileExtension;
    }

    /**
     * @param $commandStr
     * @return string
     * @throws \Exception
     * @todo This si a copy of the execute command found in the ImageProcessor class. Maybe have it in some sort of helper?
     */
    protected function execute($commandStr)
    {
        exec($commandStr, $output, $code);
        if (count($output) === 0) {
            $outputError = $code;
        } else {
            $outputError = implode(PHP_EOL, $output);
        }

        if ($code !== 0) {
            throw new AppException(
                "Command failed. The exit code: " .
                $outputError . "
                The last line of output:
                " . $commandStr
            );
        }
        return $output;
    }

    /**
     * @return bool
     */
    public function isWebPSupport()
    {
        return in_array(self::WEBP_MIME_TYPE, $this->request->getAcceptableContentTypes())
            && $this->extractByKey('webp-support', false);
    }

    /**
     * @return string
     */
    public function getResponseContentType()
    {
        return $this->isWebPSupport() ? self::WEBP_MIME_TYPE : self::JPEG_MIME_TYPE;
    }
}
