<?php

namespace Flyimg\App\OptionResolver;

use Flyimg\Exception\InvalidArgumentException;
use Flyimg\FilterExpression\AST\Node\FilterChainNode;
use Flyimg\FilterExpression\AST\Node\ScalarNodeInterface;
use Flyimg\FilterExpression\AST\Tree;
use Flyimg\FilterExpression\Lexer\Lexer;
use Flyimg\Image\Command\CommandFactoryInterface;
use Flyimg\Image\CommandChain;
use Flyimg\Image\Command\Factory as CommandFactory;
use Flyimg\Image\FaceDetection\FaceDetectionInterface;
use Imagine\Image\ImagineInterface;

class FilterResolver implements OptionResolverInterface
{
    /**
     * @var CommandFactoryInterface[]
     */
    private $commandFactories;

    /**
     * @var Lexer
     */
    private $lexer;

    /**
     * @param CommandFactoryInterface[] $commandFactories
     * @param Lexer                     $lexer
     */
    public function __construct(
        array $commandFactories,
        Lexer $lexer
    ) {
        $this->commandFactories = $commandFactories;
        $this->lexer = $lexer;
    }

    public static function buildStandard(
        ImagineInterface $imagine,
        FaceDetectionInterface $faceDetection
    ): self {
        return new static(
            [
                'resize' => new CommandFactory\ResizeFactory($imagine),
                'width' => new CommandFactory\ResizeByWidthFactory($imagine),
                'height' => new CommandFactory\ResizeByHeightFactory($imagine),
                'crop' => new CommandFactory\CropFactory($imagine),
                'blur' => new CommandFactory\BlurFactory($imagine),
                'square' => new CommandFactory\DrawSquareFactory($imagine),
                'face.blur' => new CommandFactory\FaceBlurBatchFactory($imagine, $faceDetection),
                'face.detect' => new CommandFactory\FaceDetectBatchFactory($imagine, $faceDetection),
                'face.pixelate' => new CommandFactory\FacePixelateBatchFactory($imagine, $faceDetection),
                'pixelate' => new CommandFactory\PixelateFactory($imagine),
            ],
            new Lexer()
        );
    }

    public function resolve(string $expression): CommandChain
    {
        $tree = new Tree();
        $ast = $tree->compile($this->lexer->tokenize($expression));

        return new CommandChain(...$this->consume($ast));
    }

    /**
     * @param iterable|FilterChainNode[] $ast
     *
     * @return \Generator
     */
    private function consume(iterable $ast): \Generator
    {
        /** @var FilterChainNode $node */
        foreach ($ast as $node) {
            foreach ($node->filters as $filter) {
                if (!isset($this->commandFactories[(string) $filter->name])) {
                    throw new InvalidArgumentException(strtr(
                        'Unknown filter "%filterName%", please check the documentation regarding filter management.',
                        [
                            '%filterName%' => $filter->name,
                        ]
                    ));
                }

                yield $this->commandFactories[(string) $filter->name]->build(...$this->toArguments(...$filter->arguments));
            }
        }
    }

    private function toArguments(ScalarNodeInterface ...$nodes): \Generator
    {
        foreach ($nodes as $node) {
            yield $node->value;
        }
    }
}
