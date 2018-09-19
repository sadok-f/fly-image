<?php

namespace Flyimg\Image;

use Flyimg\Image\Command\CommandInterface;

final class CommandChain implements CommandInterface
{
    /**
     * @var CommandInterface[]
     */
    private $commands;

    public function __construct(CommandInterface ...$commands)
    {
        $this->commands = $commands;
    }

    public function add(CommandInterface $command)
    {
        $this->commands[] = $command;
    }

    public function execute(ImageInterface $image): ImageInterface
    {
        foreach ($this->commands as $command) {
            $image = $command->execute($image);
        }

        return $image;
    }
}
