<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

final class Augmenter
{
    public static function augment(
        Node $node,
        Attributes $attributes,
        ?ElementType $fallbackElementType = null
    ): Node {
        if ($fallbackElementType === null) {
            $fallbackElementType = ElementType::createFromTagName('div');
        }
        
        if ($node instanceof Element) {
            /** @var Element $node  */
            $nextNode = $node;
            foreach ($attributes as $attribute) {
                $nextNode = $nextNode->withAttributes(
                    $nextNode->getAttributes()
                        ->withAppendedAttribute($attribute)
                );
            }

            return $nextNode;
        }
        else if ($node instanceof Fragment) {
            /** @var Element $node  */
            return Element::create(
                $fallbackElementType,
                $attributes,
                $node->getChildren()
            );
        }
        else {
            return Element::create(
                $fallbackElementType,
                $attributes,
                NodeList::create($node)
            );
        }
    }
}