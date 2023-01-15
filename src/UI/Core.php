<?php

namespace Leaf\UI;

/**
 * Leaf UI Core
 * ---
 * Core functions for Leaf UI
 */
class Core
{
    public const SINGLE_TAG = "single-tag";
    public const SELF_CLOSING = "self-closing";

    /**
     * Create an HTML element
     * 
     * @param string $element The HTML Element to create
     * @param array $props The Element children and attributes eg: `style`
     * @param string|array|null $children The component children
     */
    public static function createElement(string $element, $props = [], $children = null)
    {
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

        if (!$children || $children && empty($children)) {
            return self::selfClosing($element, $attributes, $id);
        }

        return "<$element id=\"$id\" $attributes>$subs</$element>";
    }
}
