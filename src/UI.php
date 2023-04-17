<?php

namespace Leaf;

use Leaf\UI\Component;
use Leaf\UI\Core;
use Leaf\UI\Parser;
use Leaf\UI\Utils;
use MatthiasMullie\Minify\CSS;

/**
 * Leaf UI
 * ---------------------
 * A PHP library for building user interfaces.
 * 
 * @author Michael Darko <mychi.darko@gmail.com>
 */
class UI
{
    /**
     * Initialize Leaf UI on a page
     * @return string
     */
    public static function init(): string
    {
        return (implode(Core::$scripts) .
            static::createElement('script', [
                'src' => '/vendor/leafs/ui/client/dist/ui.cjs.production.min.js',
            ], [''])
        );
    }

    /**
     * Render a Leaf UI
     * 
     * @param Component $component The Leaf UI component to render
     */
    public static function render($component)
    {
        $config = json_decode((new \Leaf\Http\Request())->get('_leaf_ui_config', false) ?? '', true) ?? [];

        if (!$component->key) {
            if (isset($config['component'])) {
                $component->key = $config['component'];
            } else {
                $component->key = Utils::randomId($component::class);
            }
        }

        if (isset($config['payload']['data'])) {
            Core::$state = array_merge(Core::$state, $config['payload']['data']);
        }

        Core::$state[$component->key] = array_merge(get_class_vars($component::class), [
            'key' => $component->key,
        ]);

        Core::$componentMethods = array_merge(Core::$componentMethods, get_class_methods($component));
        Core::$mappedComponentMethods = array_merge(Core::$mappedComponentMethods, [$component->key => get_class_methods($component)]);
        Core::$components = array_merge(Core::$components, [$component->key => $component::class]);

        $componentData = Core::buildComponent($component, $config);

        if ($componentData['responseType'] === 'json') {
            unset($componentData['responseType']);
            (new \Leaf\Http\Response())->json($componentData);
        } else {
            (new \Leaf\Http\Response())->markup($componentData['html']);
        }
    }

    /**
     * Render a Leaf UI from a file
     * 
     * @throws \JsonException
     */
    public static function view(string $filename): string
    {
        if (!file_exists($filename)) {
            throw new \JsonException("$filename not found!");
        }

        return Parser::compileTemplate(file_get_contents($filename), Core::mergeState());
    }

    /**
     * Embed a component into a view
     * 
     * @param string $component The component to embed
     * @return string
     */
    public static function component(string $component, array $props = []): string
    {
        $component = trim($component, '"\'\`');

        if (!class_exists($component)) {
            trigger_error($component . ' does not exist', E_USER_ERROR);
        }

        $component = new $component;

        if (!$component->key) {
            $component->key = Utils::randomId($component::class);
        }

        if ($props['key'] ?? false) {
            $component->key = $props['key'];
        }

        Core::$state[$component->key] = array_merge(get_class_vars($component::class), Core::$state[$component->key] ?? [], ['key' => $component->key], $props);

        foreach (Core::$state[$component->key] as $key => $value) {
            $component->{$key} = $value;
        }

        Core::$componentMethods = array_merge(Core::$componentMethods, get_class_methods($component));
        Core::$mappedComponentMethods = array_merge(Core::$mappedComponentMethods, [$component->key => get_class_methods($component)]);
        Core::$components = array_merge(Core::$components, [$component->key => $component::class]);

        $config = [
            'type' => 'component',
            'payload' => [
                'params' => [],
                'method' => null,
                'methodArgs' => [],
                'data' => Core::$state,
            ],
        ];

        $componentData = Core::buildComponent($component, $config, true);

        return $componentData['html'];
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
            (new CSS())->add(Parser::parseStyles($styles))->minify()
        );
    }
}
