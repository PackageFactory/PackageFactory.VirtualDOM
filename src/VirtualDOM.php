<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM;

use PackageFactory\VirtualDOM\Model\ComponentInterface;
use PackageFactory\VirtualDOM\Model\Element;
use PackageFactory\VirtualDOM\Model\Fragment;
use PackageFactory\VirtualDOM\Model\Text;

final class VirtualDOM
{
    /**
     * @param string $name
     * @param array $attributes
     * @param array $children
     * @return Element
     */
    public static function element(string $name, array $attributes = [], array $children = []): Element
    {
        return Element::fromArray([
            'name' => $name,
            'attributes' => $attributes,
            'children' => $children
        ]);
    }

    /**
     * @param array $children
     * @return Fragment
     */
    public static function fragment(array $children): Fragment
    {
        return Fragment::fromArray($children);
    }

    /**
     * @param string $text
     * @return Text
     */
    public static function text(string $text): Text
    {
        return Text::fromString($text);
    }

    /**
     * @param string $text
     * @return Text
     */
    public static function dangerouslyUnescapedText(string $text): Text
    {
        return Text::fromDangerouslyUnescapedString($text);
    }

    /**
     * @param string $htmlString
     * @return ComponentInterface
     */
    public static function fromHtmlString(string $htmlString): ComponentInterface
    {
        $reader = new \XMLReader;
        $reader->xml('<html>' . $htmlString . '</html>');

        while ($reader->read() !== false) {
            if ($reader->name === 'html') {
                continue;
            }

            switch ($reader->nodeType) {
                case \XMLReader::ELEMENT:
                    return Element::fromXMLReader($reader);
                case \XMLReader::TEXT:
                    return Fragment::fromXMLReader($reader);
                default: continue 2;
            }
        }

        throw new \InvalidArgumentException('Given html string was not valid.');
    }
}
