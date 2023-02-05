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
     * Initialize Leaf UI on a page
     * @return string
     */
    public static function init(): string
    {
        return static::createElement('script', ['src' => dirname(__DIR__, 2) . '/client/dist/ui.cjs.development.js'], ['']);
    }

    /**
     * Render a Leaf UI
     * 
     * @param Component|callable $component The Leaf UI component to render
     */
    public static function render($component)
    {
        $data = json_decode((new \Leaf\Http\Request())->get('_leaf_ui_config', false) ?? '', true);

        if (is_string($data['type'] ?? null)) {
            foreach ($data['payload']['data'] as $key => $value) {
                $component->{$key} = $value;
            }

            $data['payload']['methodArgs'] = explode(',', $data['payload']['methodArgs']);

            call_user_func(
                [$component, $data['payload']['method']],
                ...$data['payload']['methodArgs']
            );

            $state = [];

            foreach ($data['payload']['data'] as $key => $value) {
                $state[$key] = $component->{$key};
            }

            return (new \Leaf\Http\Response)->json([
                'html' => $component->render(),
                'state' => $state,
            ]);
        }

        (new \Leaf\Http\Response)
            ->markup(str_replace('</body>', Core::createElement('script', [], ['
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
            ']) . '</body>', $component->render()));
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
        $parsedStyles = '';

        foreach ($styles as $key => $value) {
            if (is_numeric($key)) {
                $value = rtrim($value, ';');
                $parsedStyles .= "$value;";
            } else if (is_string($value)) {
                $value = rtrim($value, ';');

                if (strpos($value, ':') !== false) {
                    $parsedStyles .= "$key { $value; }";
                } else {
                    $parsedStyles .= "$key: $value;";
                }
            } else {
                $parsedStyles .= "$key {";

                foreach ($value as $selector => $styling) {
                    if (is_array($styling)) {
                        if (is_string($selector)) {
                            $parsedStyles .= self::parseStyles([$selector => $styling]);
                        } else {
                            $parsedStyles .= self::parseStyles($styling);
                        }
                    } else {
                        $styling = rtrim($styling, ';');

                        if (is_numeric($selector)) {
                            $parsedStyles .= self::parseStyles(["$styling;"]);
                        } else {
                            if (strpos($styling, ':') !== false) {
                                $parsedStyles .= "$selector { $styling; }";
                            } else {
                                $parsedStyles .= "$selector: $styling;";
                            }
                        }
                    }
                }

                $parsedStyles .= '}';
            }
        }

        return $parsedStyles;
    }
}
