<?php

namespace Leaf\UI;

/**
 * Leaf UI Core
 * ---
 * Core functions for Leaf UI
 */
class Core
{
    /**
     * Create an HTML element
     * 
     * @param string $element The HTML Element to create
     * @param array $props The Element children and attributes eg: `style`
     * @param string|array|null $children The component children
     */
    public static function createElement(string $element, $props = [], $children = [])
    {
        $subs = "";
        $attributes = "";
        $id = self::randomId($element);

        if (!isset($props["id"])) {
            $props["id"] = $id;
        }

        if (isset($props["children"]) && (!$children || ($children && empty($children)))) {
            $children = $props["children"];
            unset($props["children"]);
        }

        if (is_array($children)) {
            foreach ($children as $child) {
                $subs .= $child;
            }
        } else {
            $subs = $children;
        }

        if (!empty($props)) {
            foreach ($props as $key => $value) {
                $attributes .= "$key=\"" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "\" ";
            }
        }

        if (!$children || $children && empty($children)) {
            return "<$element $attributes />";
        }

        return "<$element $attributes>$subs</$element>";
    }

    /**
     * Generate a random id
     *
     * @param string $element An html element name to append to id
     * @return string The random id
     */
    public static function randomId($element = "")
    {
        $rand = '';
        $seed = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789_-');
        shuffle($seed);
        foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];

        return "$rand-$element";
    }
}
