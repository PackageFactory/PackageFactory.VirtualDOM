<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

use PackageFactory\VirtualDOM\Rendering\RenderableInterface;

abstract class Node implements RenderableInterface
{
    public function getAsVirtualDOMNode(): Node
    {
        return $this;
    }
}