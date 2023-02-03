<?php

namespace Leaf\UI;

use MatthiasMullie\Minify\CSS;

/**
 * Leaf UI Core
 * ---
 * Core functions for Leaf UI
 */
class Core
{
    /**
     * Render a Leaf UI
     * 
     * @param Component|callable $component The Leaf UI component to render
     */
    public static function render($component)
    {
        $data = json_decode(request()->get('_leaf_ui_config', false) ?? '', true);

        if (is_string($data['type'] ?? null === 'callMethod')) {
            foreach ($data['payload']['data'] as $key => $value) {
                $component->{$key} = $value;
            }

            $component->{$data['payload']['method']}();

            $state = [];

            foreach ($data['payload']['data'] as $key => $value) {
                $state[$key] = $component->{$key};
            }

            return response()->json([
                'html' => $component->render(),
                'state' => $state,
            ]);
        }

        if (is_callable($component)) {
            echo call_user_func($component);
        } else if ($component instanceof Component) {
            echo str_replace('</body>', Core::createElement('script', [], ['
                    window.onload = function() {
                        window._leafUIConfig = {
                            el: document.querySelector("body"),
                            component: "' . $component::class . '",
                            data: ' . json_encode(get_class_vars($component::class)) . ',
                            methods: ' . json_encode(get_class_methods($component::class)) . ',
                            path: "' . $_SERVER['REQUEST_URI'] . '",
                            requestMethod: "' . $_SERVER['REQUEST_METHOD'] . '",
                        };
                    }
                ']) . '</body>', $component->render());
        }
    }
    /**
     * Create an HTML element
     * 
     * @param string $element The HTML Element to create
     * @param array $props The Element children and attributes eg: `style`
     * @param string|array|null $children The component children
     */
    public static function createElement(string $element, array $props = [], $children = [])
    {
        $subs = '';
        $attributes = '';

        if (isset($props['children']) && (!$children || ($children && empty($children)))) {
            $children = $props['children'];
            unset($props['children']);
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

        return (!$children || $children && empty($children))
            ? "<$element $attributes />"
            : "<$element $attributes>$subs</$element>";
    }

    /**
     * Map styles to style tag
     * 
     * @param array $styles The styles to apply
     * @param array $props Style tag attributes
     */
    public static function createStyles(array $styles, array $props = [])
    {
        return self::createElement(
            'style',
            $props,
            (new CSS())->add(self::parseStyles($styles))->minify()
        );
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
     * Generate a random id
     *
     * @param string $element An html element name to append to id
     * @return string The random id
     */
    public static function randomId($element = '')
    {
        $rand = '';
        $seed = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789_-');
        shuffle($seed);
        foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];

        return "$rand-$element";
    }

    protected static function parseStyles(array $styles): string
    {
        $parsed_styles = '';

        foreach ($styles as $key => $value) {
            if (is_numeric($key)) {
                $parsed_styles .= $value;
            } else if (is_string($value)) {
                $parsed_styles .= "$key { $value }";
            } else {
                $parsed_styles .= "$key {";

                foreach ($value as $selector => $styling) {
                    if (is_array($styling)) {
                        if (is_string($selector)) {
                            $parsed_styles .= self::parseStyles([$selector => $styling]);
                        } else {
                            $parsed_styles .= self::parseStyles($styling);
                        }
                    } else {
                        $styling = str_replace(';', "", $styling);

                        if (is_numeric($selector)) {
                            $parsed_styles .= self::parseStyles(["$styling;"]);
                        } else {
                            $parsed_styles .= "$selector { $styling; }";
                        }
                    }
                }

                $parsed_styles .= "}";
            }
        }

        return $parsed_styles;
    }
}
