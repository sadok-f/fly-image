<?php

namespace Flyimg\App\OptionResolver;

use Flyimg\Image\CommandChain;

interface OptionResolverInterface
{
    public function resolve(string $rawOptions): CommandChain;
}
