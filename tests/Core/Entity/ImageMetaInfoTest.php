<?php

namespace Tests\Core\Entity;

use Core\Entity\Image\InputImage;
use Core\Entity\ImageMetaInfo;
use Core\Exception\ExecFailedException;
use Tests\Core\BaseTest;
use PHPUnit\Framework\Attributes\DataProvider;

#[BackupGlobals('disabled')]
class ImageMetaInfoTest extends BaseTest
{
    #[DataProvider('mimeTypeDataProvider')]
    public function testGetMimeType(string $testImagePath, string $expectedMimeType)
    {
        $image = new ImageMetaInfo($testImagePath);
        $this->assertEquals($expectedMimeType, $image->mimeType());
    }

    /**
     * @return array
     */
    public static function mimeTypeDataProvider(): array
    {
        return [
            [self::JPG_TEST_IMAGE, InputImage::JPEG_MIME_TYPE],
            [self::GIF_TEST_IMAGE, InputImage::GIF_MIME_TYPE],
            [self::PNG_TEST_IMAGE, InputImage::PNG_MIME_TYPE],
            [self::WEBP_TEST_IMAGE, InputImage::WEBP_MIME_TYPE],
            [self::AVIF_TEST_IMAGE, InputImage::AVIF_MIME_TYPE],
        ];
    }

    /**
     *
     */
    public function testGetMimeTypeCached()
    {
        $testImagePath = self::JPG_TEST_IMAGE;
        $image = new ImageMetaInfo($testImagePath);
        $this->assertEquals(InputImage::JPEG_MIME_TYPE, $image->mimeType());
        $this->assertEquals(InputImage::JPEG_MIME_TYPE, $image->mimeType());
    }

   
    #[DataProvider('fileInfoProvider')]
    public function testGetInfo(string $testImagePath)
    {
        $image = new ImageMetaInfo($testImagePath);
        $imageInfo = $image->info();
        $this->assertTrue(is_array($imageInfo));
        $this->assertEquals(6, count($imageInfo));
    }

    #[DataProvider('fileInfoProvider')]
    public function testGetInfoAttributes(
        string $testImagePath,
        string $expectedFormat,
        string $expectedCanvas,
        string $expectedBitDepth,
        string $expectedColorProfile,
        string $expectedFileWeight,
        array $expectedDimensions
    ) {
        $image = new ImageMetaInfo($testImagePath);
        $this->assertEquals($expectedFormat, $image->format());
        $this->assertEquals($expectedCanvas, $image->canvas());
        $this->assertEquals($expectedBitDepth, $image->colorBitDepth());
        $this->assertEquals($expectedColorProfile, $image->colorProfile());
        $this->assertEquals($expectedFileWeight, $image->fileWeight());
        $imageDimensions = $image->dimensions();
        $this->assertTrue(is_array($imageDimensions));
        $this->assertEquals(2, count($imageDimensions));
        $this->assertEquals($expectedDimensions['width'], $imageDimensions['width']);
        $this->assertEquals($expectedDimensions['height'], $imageDimensions['height']);
    }

    /**
     *
     */
    public function testFileReadException()
    {
        $this->expectException(ExecFailedException::class);
        $image = new ImageMetaInfo(self::PNG_TEST_IMAGE . '--fail');
        $image->colorBitDepth();
    }

    /**
     * @return array
     */
    public static function fileInfoProvider(): array
    {
        return [
            [
                self::JPG_TEST_IMAGE,
                'JPEG',
                '300x300+0+0',
                '8-bit',
                'sRGB',
                '5301B',
                [
                    'width' => '300',
                    'height' => '300',
                ],
            ],
            [
                self::AVIF_TEST_IMAGE,
                'AVIF',
                '1204x800+0+0',
                '12-bit',
                'sRGB',
                '98800B',
                [
                    'width' => '1204',
                    'height' => '800',
                ],
            ],
            [
                self::GIF_TEST_IMAGE,
                'GIF',
                '800x600+0+0',
                '8-bit',
                'sRGB',
                '128c',
                [
                    'width' => '800',
                    'height' => '600',
                ],
            ],
            [
                self::PNG_TEST_IMAGE,
                'PNG',
                '256x256+0+0',
                '8-bit',
                'sRGB',
                '2438B',
                [
                    'width' => '256',
                    'height' => '256',
                ],
            ],
            [
                self::WEBP_TEST_IMAGE,
                'WEBP',
                '100x100+0+0',
                '8-bit',
                'sRGB',
                '706B',
                [
                    'width' => '100',
                    'height' => '100',
                ],
            ],
        ];
    }
}
