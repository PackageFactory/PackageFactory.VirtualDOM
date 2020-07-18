<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Rendering;

use PackageFactory\VirtualDOM\Component\Document;
use PackageFactory\VirtualDOM\Model\ComponentInterface;

final class HTML5DocumentStringRenderer
{
    /**
     * @param ComponentInterface $component
     * @return string
     */
    public static function render(Document $document): string
    {
        return sprintf('<!doctype %s>', $document->getDoctype()) . HTML5StringRenderer::render($document);
    }
}