<?php

namespace spec\Flyimg\Http\OptionResolver;

use Flyimg\Http\OptionResolver\PathResolver;
use Flyimg\Image\Command\CommandFactoryInterface;
use Flyimg\Image\Command\CommandInterface;
use Flyimg\Image\CommandChain;
use PhpSpec\ObjectBehavior;

class PathResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith([]);

        $this->shouldHaveType(PathResolver::class);
    }

    function it_should_call_proper_factory(
        CommandFactoryInterface $factory,
        CommandInterface $command
    ) {
        $this->beConstructedWith(
            [
                'w' => $factory,
            ]
        );

        $factory->build(200)
            ->willReturn($command)
            ->shouldBeCalledTimes(1)
        ;

        $this->resolve('w_200')->shouldReturnAnInstanceOf(CommandChain::class);
    }

    function it_should_build_proper_command_count(
        CommandFactoryInterface $factory,
        CommandInterface $command
    ) {
        $this->beConstructedWith(
            [
                'w' => $factory,
            ]
        );

        $factory->build(200)
            ->willReturn($command)
            ->shouldBeCalledTimes(1)
        ;

        $this->resolve('w_200')->shouldHaveCount(1);
    }
}
