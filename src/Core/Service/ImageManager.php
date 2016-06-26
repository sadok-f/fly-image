<?php

namespace Core\Service;

use League\Flysystem\Filesystem;

/**
 * Class ImageManager
 * @package Core\Service
 */
class ImageManager
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var array
     */
    protected $params;

    /**
     * ImageManager constructor.
     *
     * @param array $params
     * @param Filesystem $filesystem
     */
    public function __construct($params, Filesystem $filesystem)
    {
        $this->params = $params;
        $this->filesystem = $filesystem;
    }

    /**
     * Process give source file with given options
     *
     * @param array $options
     * @param $sourceFile
     * @return string
     */
    public function process($options, $sourceFile)
    {
        //check restricted_domains is enabled
        if ($this->params['restricted_domains'] &&
            is_array($this->params['whitelist_domains']) &&
            !in_array(parse_url($sourceFile, PHP_URL_HOST), $this->params['whitelist_domains'])
        ) {
            throw  new \Exception('Restricted domains enabled, the domain your fetching from is not allowed: ' . parse_url($sourceFile, PHP_URL_HOST));

        }
        /*echo '<pre>
        '.print_r($options,true);*/

        $options = $this->parseOptions($options);
        $newFileName = md5(implode('.', $options) . $sourceFile);

        if ($this->filesystem->has($newFileName) && $options['refresh']) {
            $this->filesystem->delete($newFileName);
        }
        if (!$this->filesystem->has($newFileName)) {
            $this->saveNewFile($sourceFile, $newFileName, $options);
        }
        /*echo '<br>
        then:<br>
        '.print_r($options,true);*/

        return $this->filesystem->read($newFileName);
    }

    /**
     * Parse options: match options keys and merge default options with given ones
     *
     * @param $options
     * @return array
     */
    public function parseOptions($options)
    {
        $defaultOptions = $this->params['default_options'];
        $optionsKeys = $this->params['options_keys'];
        $optionsSeparator = !empty($this->params['options_separator']) ? $this->params['options_separator'] : ',';
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
     * Save new FileName based on source file and list of options
     *
     * @param $sourceFile
     * @param $newFileName
     * @param $options
     * @throws \Exception
     */
    public function saveNewFile($sourceFile, $newFileName, $options)
    {
        $newFilePath = TMP_DIR . $newFileName;
        $tmpFile = $this->saveToTemporaryFile($sourceFile);
        $commandStr = $this->generateCmdString($newFilePath, $tmpFile, $options);

        exec($commandStr, $output, $code);
        if (count($output) === 0) {
            $output = $code;
        } else {
            $output = implode(PHP_EOL, $output);
        }

        if ($code !== 0) {
            throw new \Exception($output . ' Command line: ' . $commandStr);
        }
        $this->filesystem->write($newFileName, stream_get_contents(fopen($newFilePath, 'r')));
        unlink($tmpFile);
        unlink($newFilePath);
    }

    /**
     * Generate Command string bases on options
     *
     * @param $options
     * @param $tmpFile
     * @param $newFilePath
     * @return string
     */
    public function generateCmdString($newFilePath, $tmpFile, $options)
    {
        $this->extractByKey($options, 'refresh');
        $quality = $this->extractByKey($options, 'quality');
        $strip = $this->extractByKey($options, 'strip');
        $mozJPEG = $this->extractByKey($options, 'mozjpeg');
        $thread = $this->extractByKey($options, 'thread');
        $size = $this->extractByKey($options, 'width') . 'x' . $this->extractByKey($options, 'height') . '>';

        $command = [];
        //-filter Triangle 
        //-define filter:support=2 
        //-thumbnail OUTPUT_WIDTH -unsharp 0.25x0.25+8+0.065 -dither None -posterize 136 -quality 82 -define jpeg:fancy-upsampling=off -define png:compression-filter=5 -define png:compression-level=9 -define png:compression-strategy=1 -define png:exclude-chunk=all -interlace none -colorspace sRGB -strip 
        //$command[] = "/usr/bin/convert " . $tmpFile . "[" . escapeshellarg($size) . "]";
        //$command[] = "/usr/bin/convert " . $tmpFile . " -thumbnail " . escapeshellarg($size) . " ";
        //$command[] = "/usr/bin/convert " . $tmpFile . " -filter Triangle -define filter:support=2 -thumbnail " . escapeshellarg($size) . " ";
        //$command[] = "/usr/bin/convert " . $tmpFile . " -define filter:support=2 -thumbnail " . escapeshellarg($size) . " -unsharp 0.25x0.25+8+0.065 -dither None ";
        //$command[] = "/usr/bin/convert " . $tmpFile . " -filter Triangle -define filter:support=2 -thumbnail " . escapeshellarg($size) . " -unsharp 0.25x0.25+8+0.065 -dither None  -colorspace sRGB";
        $command[] = "/usr/bin/convert " . $tmpFile . " -filter Triangle -thumbnail " . escapeshellarg($size) . " -colorspace sRGB";
        //$command[] = "-extent " . escapeshellarg($size);

        if (!empty($thread)) {
            $command[] = "-limit thread " . escapeshellarg($thread);
        }

        if (!empty($strip)) {
            $command[] = "-strip ";
        }

        foreach ($options as $key => $value) {
            if (!empty($value)) {
                $command[] = "-{$key} " . escapeshellarg($value);
            }
        }

        if (is_executable($this->params['mozjpeg_path']) && $mozJPEG == 1) {
            $command[] = "TGA:- | " . escapeshellarg($this->params['mozjpeg_path']) . " -quality " . escapeshellarg($quality) . " -outfile " . escapeshellarg($newFilePath) . " -targa";
        } else {
            $command[] = "-quality " . escapeshellarg($quality) . " " . escapeshellarg($newFilePath);
        }

        $commandStr = implode(' ', $command);
        header('command: '.$commandStr);
        return $commandStr;
    }

    /**
     * Extract a value from given array and unset it.
     *
     * @param $array
     * @param $key
     * @return null
     */
    public function extractByKey(&$array, $key)
    {
        $value = null;
        if (isset($array[$key])) {
            $value = $array[$key];
            unset($array[$key]);
        }
        return $value;
    }

    /**
     * Save given image to temporary file and return the path
     *
     * @param $fileUrl
     * @return string
     * @throws \Exception
     */
    public function saveToTemporaryFile($fileUrl)
    {
        if (!$resource = @fopen($fileUrl, "r")) {
            throw  new \Exception('Error occured while trying to read the file Url : ' . $fileUrl);
        }
        $content = "";
        while ($line = fread($resource, 1024)) {
            $content .= $line;
        }
        $tmpFile = TMP_DIR . uniqid("", true);
        file_put_contents($tmpFile, $content);
        return $tmpFile;
    }
}
