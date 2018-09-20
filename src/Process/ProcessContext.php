<?php

namespace Flyimg\Process;

use Flyimg\Image\StreamedImageInterface;
use Symfony\Component\Process\Process;

class ProcessContext
{
    /**
     * @var ProcessContext
     */
    private $parent;

    /**
     * @var string[]
     */
    private $command;

    /**
     * @param string[] $command
     */
    public function __construct(string ...$command)
    {
        $this->parent = null;
        $this->command = $command;
    }

    /**
     * @param string[] $command
     *
     * @return ProcessContext
     */
    public function pipe(string ...$command): ProcessContext
    {
        $instance = new self(...$command);
        $instance->parent = $this;

        return $instance;
    }

    public function command(): string
    {
        return implode(' ', $this->pack());
    }

    /**
     * @return string[]
     */
    private function pack(): array
    {
        if ($this->parent === null) {
            return $this->command;
        }

        return array_merge(
            $this->parent->pack(),
            $this->command
        );
    }

    public function build(?StreamedImageInterface $input = null): Process
    {
        $process = new Process($this->pack());

        if ($input !== null) {
            $process->setInput($input->asStream());
        }

        return $process;
    }
}
