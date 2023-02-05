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
        return static::createElement('script', ['src' => '/vendor/leafs/ui/client/dist/ui.cjs.production.min.js'], ['']);
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
                'html' => static::compileTemplate($component->render(), $state),
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
            ']) . '</body>', static::compileTemplate($component->render(), get_class_vars($component::class))));
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

        return file_get_contents($filename);
    }

    /**
     * Compile Leaf UI Template
     * @param string $rawText The template to compile
     */
    public static function compileTemplate(string $rawText, array $state = []): string
    {
        // $compiled = preg_replace_callback('/{{(.*?)}}/', function ($matches) {
            /*/ return "<?php $matches[1]; ?>"; */
        // }, $rawText);

        $compiled = preg_replace_callback('/@if\((.*?)\)/', function ($matches) {
            return "<?php if ($matches[1]): ?>";
        }, $rawText);

        $compiled = preg_replace_callback('/@elseif\((.*?)\)/', function ($matches) {
            return "<?php elseif ($matches[1]): ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@else/', function ($matches) {
            return "<?php else: ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@endif/', function ($matches) {
            return "<?php endif; ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@for\((.*?)\)/', function ($matches) {
            return "<?php for ($matches[1]): ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@endfor/', function ($matches) {
            return "<?php endfor; ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@foreach\((.*?)\)/', function ($matches) {
            return "<?php foreach ($matches[1]): ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@endforeach/', function ($matches) {
            return "<?php endforeach; ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@while\((.*?)\)/', function ($matches) {
            return "<?php while ($matches[1]): ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@endwhile/', function ($matches) {
            return "<?php endwhile; ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@switch\((.*?)\)/', function ($matches) {
            return "<?php switch ($matches[1]): ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@case\((.*?)\)/', function ($matches) {
            return "<?php case $matches[1]: ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@break/', function ($matches) {
            return "<?php break; ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@endswitch/', function ($matches) {
            return "<?php endswitch; ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@continue/', function ($matches) {
            return "<?php continue; ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@php(.*?)@endphp/', function ($matches) {
            return "<?php $matches[1]; ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@include\((.*?)\)/', function ($matches) {
            return "<?php echo Core::view($matches[1]); ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@component\((.*?)\)/', function ($matches) {
            return "<?php echo Core::component($matches[1]); ?>";
        }, $compiled);

        extract($state);
        ob_start();

        echo $compiled;

        return ob_get_clean();
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
