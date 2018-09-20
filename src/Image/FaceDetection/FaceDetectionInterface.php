<?php

namespace Flyimg\Image\FaceDetection;

use Imagine\Image\ImageInterface;

interface FaceDetectionInterface
{
    /**
     * @param ImageInterface $input
     *
     * @return \Generator|array<PointInterface => BoxInterface>
     */
    public function detect(ImageInterface $input): \Generator;
}
