<?php

namespace Flyimg\Process;

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

    public function build(): Process
    {
        var_dump(implode(' ', array_map(function($item){return escapeshellarg($item);}, $this->pack())));die;
        return new Process($this->pack());
    }
}
