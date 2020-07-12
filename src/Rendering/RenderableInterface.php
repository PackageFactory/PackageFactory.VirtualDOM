<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Rendering;

use PackageFactory\VirtualDOM\Node;

interface RenderableInterface
{
    public function getAsVirtualDOMNode(): Node;
}