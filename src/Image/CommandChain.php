<?php

namespace Flyimg\Image;

use Flyimg\Image\Command\CommandInterface;
use Imagine\Image\ImageInterface;
use Traversable;

final class CommandChain implements CommandInterface, \Countable, \IteratorAggregate
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

    public function count()
    {
        return count($this->commands);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->commands);
    }
}
