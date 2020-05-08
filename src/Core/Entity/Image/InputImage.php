<?php

namespace Core\Entity\Image;

use Core\Entity\OptionsBag;
use Core\Exception\ReadFileException;
use Core\Entity\ImageMetaInfo;
use Core\Processor\VideoProcessor;

class InputImage
{
    /** Content TYPE */
    const WEBP_MIME_TYPE = 'image/webp';
    const JPEG_MIME_TYPE = 'image/jpeg';
    const PNG_MIME_TYPE = 'image/png';
    const GIF_MIME_TYPE = 'image/gif';
    const PDF_MIME_TYPE = 'application/pdf';

    /** @var OptionsBag */
    protected $optionsBag;

    /** @var string */
    protected $sourceImageUrl;

    /** @var string */
    protected $sourceImagePath;

    /** @var string */
    protected $sourceImageMimeType;

    /** @var ImageMetaInfo */
    protected $sourceImageInfo;

    /**  @var string */
    protected $sourceFileMimeType;

    /**
     * InputImage constructor.
     *
     * @param OptionsBag $optionsBag
     * @param string     $sourceImageUrl
     *
     * @throws \Exception
     */
    public function __construct(OptionsBag $optionsBag, string $sourceImageUrl)
    {
        $this->optionsBag = $optionsBag;
        $this->sourceImageUrl = $sourceImageUrl;

        $this->sourceImagePath = $optionsBag->hashOriginalImageUrl($this->sourceImageUrl);
        $this->saveToTemporaryFile();

        $this->sourceImageInfo = new ImageMetaInfo($this->sourceImagePath);

        // Store the source file mime type.
        $this->sourceFileMimeType = $this->sourceImageInfo->mimeType();

        // If the source file has a video mime type, generate a full resolution
        // image at the requested (or default) time duration and use that as
        // the source image.
        if (strpos($this->sourceFileMimeType, 'video/') !== false) {
            $videoProcessor = new VideoProcessor();
            $this->sourceImagePath = $videoProcessor->generateVideoSourceImage(
                $this->optionsBag,
                $this->sourceImagePath
            );
            $this->sourceImageInfo = new ImageMetaInfo($this->sourceImagePath);
        }
    }

    /**
     * Save given image to temporary file and return the path
     *
     * @throws \Exception
     */
    protected function saveToTemporaryFile()
    {
        if (file_exists($this->sourceImagePath) && !$this->optionsBag->get('refresh')) {
            return;
        }

        $opts = [
            'http' =>
                [
                    'header' => $this->optionsBag->appParameters()->parameterByKey('header_extra_options'),
                    'method' => 'GET',
                    'max_redirects' => '0',
                ],
        ];
        $context = stream_context_create($opts);
        
        $parts = pathinfo($this->sourceImageUrl);
        $sourceUrl = $parts['dirname'] . '/' . rawurlencode($parts['basename]);
        
        if (!$stream = @fopen($sourceUrl, 'r', false, $context)
        ) {
            throw  new ReadFileException(
                'Error occurred while trying to read the file Url : '
                .$this->sourceImageUrl
            );
        }
        $content = stream_get_contents($stream);
        fclose($stream);
        file_put_contents($this->sourceImagePath, $content);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function extractKey(string $key): string
    {
        $value = '';
        if ($this->optionsBag->has($key)) {
            $value = $this->optionsBag->get($key);
            $this->optionsBag->remove($key);
        }

        return is_null($value) ? '' : $value;
    }

    /**
     * @return OptionsBag
     */
    public function optionsBag(): OptionsBag
    {
        return $this->optionsBag;
    }

    /**
     * @return string
     */
    public function sourceImageUrl(): string
    {
        return $this->sourceImageUrl;
    }

    /**
     * @return string
     */
    public function sourceImagePath(): string
    {
        return $this->sourceImagePath;
    }

    /**
     * @return string
     */
    public function sourceImageMimeType(): string
    {
        if (isset($this->sourceImageMimeType)) {
            return $this->sourceImageMimeType;
        }

        $this->sourceImageMimeType = $this->sourceImageInfo->mimeType();

        return $this->sourceImageMimeType;
    }

    public function sourceImageInfo()
    {
        return $this->sourceImageInfo;
    }

    /**
     * Source file mime type
     *
     * @return string
     */
    public function sourceFileMimeType(): string
    {
        return isset($this->sourceFileMimeType) ? $this->sourceFileMimeType : '';
    }

    /**
     * Is input file a pdf
     *
     * @return bool
     */
    public function isInputPdf(): bool
    {
        return $this->sourceImageMimeType() == self::PDF_MIME_TYPE;
    }

     /**
     * @return bool
     */
    public function isInputGif(): bool
    {
        return $this->sourceImageMimeType() == self::GIF_MIME_TYPE;
    }
}
