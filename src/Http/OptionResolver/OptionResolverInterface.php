<?php

namespace Flyimg\Http\OptionResolver;

use Flyimg\Image\CommandChain;

interface OptionResolverInterface
{
    public function resolve(string $rawOptions): CommandChain;
}
