<?php

namespace Leaf;

/**
 * Leaf UI
 * ---------------------
 * A PHP library for building user interfaces.
 * 
 * @version 1.0.0
 * @author Michael Darko <mychi.darko@gmail.com>
 */
class UI
{
    public const SINGLE_TAG = "single-tag";
    public const SELF_CLOSING = "self-closing";

    public static function elements()
    {
        return require __DIR__ . "/UI/Elements.php";
    }

    /**
     * Create an HTML element
     * 
     * Element Type Options:
     * - UI::SELF_CLOSING
     * - UI::SINGLE_TAG
     * - Ignore to create a normal tag
     * 
     * @param string $element The HTML Element to create
     * @param array $props The Element children and attributes eg: `style`
     * @param string $type The type of tag you want to create
     */
    public static function createElement(string $element, $props = [], string $type = "normal")
    {
        $type = strtolower($type);
        $attributes = "";
        $subs = "";
        $id = self::randomId($element);

        $data = [];

        if (is_string($props)) {
            $data["children"] = $props;
        } else {
            $data = $props;
        }

        $children = $data["children"];

        unset($data["children"]);

        if (is_array($children)) {
            foreach ($children as $child) {
                $subs .= $child;
            }
        } else {
            $subs = $children;
        }

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if ($key != "id") {
                    $attributes .= "$key=\"" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "\" ";
                } else {
                    $id = $data["id"];
                }
            }
        }

        if ($type == self::SELF_CLOSING) {
            return self::selfClosing($element, $attributes, $id);
        }

        if ($type == self::SINGLE_TAG) {
            return self::selfClosing($element, $attributes, $id);
        }

        return "<$element id=\"$id\" $attributes>$subs</$element>";
    }

    /**
     * Return a self closing tag
     * 
     * @param string $element The element you want to create
     * @param string $attributes Element attributes eg: `name`, `style`
     * @param string $id Element id (compulsory)
     */
    public static function selfClosing(string $element, string $attributes, string $id)
    {
        return "<$element $attributes id=\"$id\" />";
    }

    /**
     * Return a single tag eg: `meta`, `link`
     * 
     * @param string $element The element you want to create
     * @param string $attributes Element attributes eg: `name`, `style`
     * @param string $id Element id (compulsory)
     */
    public static function singleTag(string $element, string $attributes, string $id)
    {
        return "<$element $attributes id=\"$id\">";
    }

    /**
     * Map styles to style tag
     * 
     * @param array $styles The styles to apply
     * @param array $props Style tag attributes
     */
    public static function createStyles(array $styles, array $props = [])
    {
        $parsed_styles = "";

        foreach ($styles as $key => $value) {
            if (is_array($value)) {
                if (strpos($key, "@") !== 0) {
                    $parsed_styles .= "$key { ";

                    foreach ($value as $selector => $styling) {
                        $parsed_styles .= "$selector: $styling; ";
                    }

                    $parsed_styles .= "} ";
                } else {
                    foreach ($value as $selector => $styling) {
                        if (is_string($styling)) {
                            $parsed_styles .= "$key { $selector { $styling }}";
                        } else {
                            $parsed_styles .= "$key { $selector { ";

                            foreach ($styling as $sKey => $sValue) {
                                $parsed_styles .= "$sKey: $sValue; ";
                            }

                            $parsed_styles .= "}} ";
                        }
                    }
                }
            } else {
                $parsed_styles .= "$key { $value }";
            }
        }

        return self::createElement("style", array_merge(
            $props,
            ["children" => $parsed_styles]
        ));
    }

    /**
     * Join multiple elements
     * 
     * @param mixed $elements Elements to merge
     */
    public static function merge(...$elements)
    {
        $data = "";
        foreach ($elements as $el) {
            $data .= $el;
        }
        return $data;
    }

    /**
     * Generate a random id
     *
     * @param string $element An html element name to append to id
     * @return string The random id
     */
    public static function randomId($element = "")
    {
        $seed = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789_-');
        shuffle($seed);
        $rand = '';
        foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];

        return "$rand-$element";
    }

    /**
     * Render a Leaf UI
     * 
     * @param string $element The UI components to render
     * @param string $inject A string to inject into element
     */
    public static function render($element)
    {
        header("Content-Type: text/html");
        echo $element;
    }

    /**
     * Loop over an array of items
     * 
     * @param array $array The array to loop through
     * @param callable $handler Call back function to run per iteration
     */
    public static function loop(array $array, callable $handler)
    {
        $element = "";
        if (is_callable($handler)) {
            foreach ($array as $key => $value) {
                $element .= call_user_func($handler, $value, $key);
            }
        }
        return $element;
    }

    /**
     * Fragment Component.
     * Literally does nothing. It just serves as an invisible container for your elements. 
     * (It doesn't render unto the DOM.)
     * 
     * @param array $children Children
     */
    public static function Fragment(array $children)
    {
        $els = "";
        $els .= self::loop($children, function ($child) {
            return $child;
        });
        return $els;
    }

    /**
     * Return Leaf UI's vendor path
     * 
     * @param string $file A file/path to append to vendor path
     * @return string Path to leaf ui in vendor folder
     */
    public static function Vendor($file = null)
    {
        return "vendor\\leafs\\ui\\src\\UI\\$file";
    }
}
