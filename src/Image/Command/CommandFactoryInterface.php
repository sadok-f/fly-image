<?php

namespace Flyimg\Image\Command;

interface CommandFactoryInterface
{
    public function build(...$options): CommandInterface;
}
