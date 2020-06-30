<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Rendering;

use PackageFactory\VirtualDOM\DangerouslyUnescapedText;
use PackageFactory\VirtualDOM\Element;
use PackageFactory\VirtualDOM\Fragment;
use PackageFactory\VirtualDOM\Node;
use PackageFactory\VirtualDOM\Text;

final class RenderingFailed extends \RuntimeException
{
    public static function becauseOfAnUnknownNodeClass(Node $node): self
    {
        return new self(
            sprintf(
                'Node of unknown class "%s" could not be rendered. ' .
                'Expected one of the following classes: ' . PHP_EOL . '    %s',
                get_class($node),
                join(PHP_EOL . '    ', [
                    Element::class,
                    Fragment::class,
                    Text::class,
                    DangerouslyUnescapedText::class
                ])
            )
        );
    }
}