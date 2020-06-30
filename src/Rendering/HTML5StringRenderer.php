<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Rendering;

use PackageFactory\VirtualDOM\DangerouslyUnescapedText;
use PackageFactory\VirtualDOM\Element;
use PackageFactory\VirtualDOM\Fragment;
use PackageFactory\VirtualDOM\Node;
use PackageFactory\VirtualDOM\NodeList;
use PackageFactory\VirtualDOM\Text;

final class HTML5StringRenderer
{
    /**
     * @param Node $node
     * @return string
     */
    public static function render(Node $node): string
    {
        if ($node instanceof Element) {
            if ($node->getChildren()->getIsEmpty()) {
                if ($node->getElementType()->getIsVoid()) {
                    return sprintf(
                        '<%s%s/>',
                        $node->getElementType()->getTagName(),
                        $node->getAttributes()
                    );
                }
                else {
                    return sprintf(
                        '<%1$s%2$s></%1$s>',
                        $node->getElementType()->getTagName(),
                        $node->getAttributes()
                    );
                }
            }
            else {
                return sprintf(
                    '<%1$s%2$s>%3$s</%1$s>',
                    $node->getElementType()->getTagName(),
                    $node->getAttributes(),
                    self::renderNodeList($node->getChildren())
                );
            }
        } elseif ($node instanceof Fragment) {
            return self::renderNodeList($node->getChildren());
        } elseif ($node instanceof Text) {
            return $node->getValue();
        } elseif ($node instanceof DangerouslyUnescapedText) {
            return $node->getValue();
        } else {
            throw RenderingFailed::becauseOfAnUnknownNodeClass($node);
        }
    }

    /**
     * @param NodeList $nodeList
     * @return string
     */
    public static function renderNodeList(NodeList $nodeList): string 
    {
        $result = '';

        foreach ($nodeList as $node) {
            $result .= self::render($node);
        }

        return $result;
    }
}