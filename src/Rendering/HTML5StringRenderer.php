<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Rendering;

use PackageFactory\VirtualDOM\Model\Attribute;
use PackageFactory\VirtualDOM\Model\Attributes;
use PackageFactory\VirtualDOM\Model\ComponentInterface;
use PackageFactory\VirtualDOM\Model\Element;
use PackageFactory\VirtualDOM\Model\Fragment;
use PackageFactory\VirtualDOM\Model\Text;
use PackageFactory\VirtualDOM\Model\VisitorInterface;

final class HTML5StringRenderer implements VisitorInterface
{
    /**
     * @var string
     */
    private $result;

    /**
     * 
     */
    private function __construct()
    {
        $this->result = '';
    }

    /**
     * @param Element $element
     * @return void
     */
    public function onElement(Element $element): void
    {
        if ($element->getChildren()->isEmpty()) {
            if ($element->getName()->isVoid()) {
                $this->result .= sprintf(
                    '<%s%s/>',
                    $element->getName(),
                    $this->renderAttributes($element->getAttributes())
                );
            } else {
                $this->result .=  sprintf(
                    '<%1$s%2$s></%1$s>',
                    $element->getName(),
                    $this->renderAttributes($element->getAttributes())
                );
            }
        } else {
            $this->result .= sprintf(
                '<%s%s>',
                $element->getName(),
                $this->renderAttributes($element->getAttributes())
            );

            foreach ($element->getChildren() as $child) {
                $child->render($this);
            }

            $this->result .= sprintf('</%s>', $element->getName());
        }
    }

    /**
     * @param Fragment $fragment
     * @return void
     */
    public function onFragment(Fragment $fragment): void
    {
        foreach ($fragment->getChildren() as $child) {
            $child->render($this);
        }
    }

    /**
     * @param Text $text
     * @return void
     */
    public function onText(Text $text): void
    {
        $this->result .= (string) $text;
    }

    /**
     * @param Attributes $attributes
     * @return string
     */
    protected function renderAttributes(Attributes $attributes): string
    {
        if ($attributes->count() === 0) {
            return '';
        } else {
            $result = '';

            foreach ($attributes as $attribute) {
                $result .= $this->renderAttribute($attribute);
            }
    
            return $result;
        }
    }

    /**
     * @param Attribute $attribute
     * @return string
     */
    protected function renderAttribute(Attribute $attribute): string
    {
        if ($attribute->isBoolean()) {
            if ($attribute->getValue() === true) {
                return ' ' . $attribute->getName();
            }
        } else {
            return sprintf(' %s="%s"', $attribute->getName(), $attribute->getValue());
        }
    }

    /**
     * @param ComponentInterface $component
     * @return string
     */
    public static function render(ComponentInterface $component): string
    {
        $visitor = new self();

        $component->render($visitor);

        return $visitor->result;
    }
}