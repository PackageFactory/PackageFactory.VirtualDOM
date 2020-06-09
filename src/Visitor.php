<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

final class Visitor
{
    /**
     * @param Node $node
     * @param callable(Node $node): Node $callback
     * @return Node
     */
    public static function visit(Node $node, callable $callback): Node
    {
        if ($node instanceof Element) {
            $nextNode = $callback($node);

            if ($nextNode instanceof Element) {
                /** @var Element $nextNode */
                $nextChildren = [];
                foreach ($nextNode->getChildren() as $childNode) {
                    $nextChildren[] = self::visit($childNode, $callback);
                }
    
                return $nextNode->withChildren(...$nextChildren);
            }
            else {
                return $nextNode;
            }
        }
        else if ($node instanceof Fragment) {
            $nextChildren = [];
            foreach ($node->getChildren() as $childNode) {
                $nextChildren[] = self::visit($childNode, $callback);
            }

            return $node->withChildren(...$nextChildren);
        }
        else {
            return $callback($node);
        }
    }
}