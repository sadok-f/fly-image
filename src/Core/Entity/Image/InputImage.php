<?php

namespace Core\Entity\Image;

use Core\Entity\ImageMetaInfo;
use Core\Entity\OptionsBag;
use Core\Exception\ReadFileException;
use Core\Processor\VideoProcessor;
use Symfony\Component\HttpFoundation\Request;

class InputImage
{
    /** Content TYPE */
    public const WEBP_MIME_TYPE = 'image/webp';
    public const JPEG_MIME_TYPE = 'image/jpeg';
    public const PNG_MIME_TYPE = 'image/png';
    public const GIF_MIME_TYPE = 'image/gif';
    public const AVIF_MIME_TYPE = 'image/avif';
    public const PDF_MIME_TYPE = 'application/pdf';

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
     * @param string $sourceImageUrl
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
     * Properly encode URL
     *
     * @param string $url
     * @return string
     */
    private function encodeUrl($url)
    {
        // URL scheme was not always set as the input scheme
        if (preg_match("!https?:/[a-zA-Z]!", $url)) {
            $url = preg_replace("!http(s?):/!", 'http$1://', $url);
        }
        $parsedUrl = parse_url($url);

        if (empty($parsedUrl['scheme']) || empty($parsedUrl['host'])) {
            return $url; // invalid URL
        }

        $user = isset($parsedUrl['user']) ? rawurlencode($parsedUrl['user']) : '';
        $pass = isset($parsedUrl['pass']) ? ':' . rawurlencode($parsedUrl['pass'])  : '';
        $auth = $user || $pass ? "$user$pass@" : '';

        $host = $parsedUrl['host'];
        $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';

        $path = '';
        if (isset($parsedUrl['path'])) {
            $path = implode('/', array_map('rawurlencode', explode('/', $parsedUrl['path'])));
        }
        $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
        $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

        return  $parsedUrl['scheme'] . '://' . $auth . $host . $port . $path . $query . $fragment;
    }

    /**
     * Save given image to temporary file and return the path
     *
     * @throws \Exception
     */
    protected function saveToTemporaryFile()
    {
        $this->sourceImageUrl = $this->encodeUrl($this->sourceImageUrl);

        $headers = $this->optionsBag->appParameters()->parameterByKey('header_extra_options');
        if (is_string($headers)) {
            $headers = [$headers];
        }
        $refresh = $this->optionsBag->get('refresh');
        $forwardRequestHeaders = (array)$this->optionsBag
            ->appParameters()
            ->parameterByKey('forward_request_headers', []);
        if (!empty($forwardRequestHeaders)) {
            $requestHeaders = Request::createFromGlobals()->headers;
            foreach ($forwardRequestHeaders as $name) {
                if ($requestHeaders->has($name)) {
                    $value = $requestHeaders->get($name);
                    $headers[] = "$name: $value";
                    if ('Authorization' === $name) {
                        $this->sourceImagePath .= md5($value);
                    }
                }
            }
        }

        if (file_exists($this->sourceImagePath) && !$refresh) {
            return;
        }

        if (filter_var($this->sourceImageUrl, FILTER_VALIDATE_URL)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->sourceImageUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Allow redirects
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  // Add custom headers
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->optionsBag->appParameters()
                        ->parameterByKey('source_image_request_timeout'));

            $imageData = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new ReadFileException('Curl error: ' . curl_error($ch));
            } else {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($httpCode == 200) {
                    file_put_contents($this->sourceImagePath, $imageData);
                } else {
                    throw new ReadFileException("Failed to download the image. HTTP Status Code: $httpCode");
                }
            }
            curl_close($ch);
        } else {
            // Handle local file
            if (file_exists($this->sourceImageUrl)) {
                $imageData = file_get_contents($this->sourceImageUrl);
                file_put_contents($this->sourceImagePath, $imageData);
            } else {
                throw  new ReadFileException('Local file does not exist.');
            }
        }
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
