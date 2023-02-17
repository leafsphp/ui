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
    /** Scripts to embed into view */
    protected static $scripts = [];

    /** State to embed into view */
    protected static $state = [];

    /** List of components in a view */
    protected static $components = [];

    /** List of component methods */
    protected static $componentMethods = [];

    /**
     * Initialize Leaf UI on a page
     * @return string
     */
    public static function init(): string
    {
        return implode(static::$scripts) . static::createElement('script', ['src' => '/vendor/leafs/ui/client/dist/ui.cjs.production.min.js'], ['']);
    }

    /**
     * Render a Leaf UI
     * 
     * @param Component $component The Leaf UI component to render
     */
    public static function render($component)
    {
        $config = json_decode((new \Leaf\Http\Request())->get('_leaf_ui_config', false) ?? '', true) ?? [];

        $component->key = static::randomId($component::class);
        static::$state[$component->key] = get_class_vars($component::class);
        static::$componentMethods = array_merge(static::$componentMethods, get_class_methods($component));
        static::$components = array_merge(static::$components, [$component->key => $component::class]);

        $componentData = static::buildComponent($component, $config);

        if ($componentData['responseType'] === 'json') {
            unset($componentData['responseType']);
            (new \Leaf\Http\Response)->json($componentData);
        } else {
            (new \Leaf\Http\Response)->markup($componentData['html']);
        }
    }

    /**
     * Process a Leaf UI component and return the HTML
     * 
     * @param Component $name The name of the component
     * @param array $config Configuration for the component
     * @param bool $withoutScripts Whether to return the HTML without scripts
     * @param callable $callback The callback to run when the component is rendered
     */
    protected static function buildComponent(Component $component, array $config, bool $withoutScripts = false)
    {
        if (is_string($config['type'] ?? null)) {
            foreach ($config['payload']['data'] as $key => $value) {
                $component->{$key} = $value;
            }

            if ($config['type'] === 'callMethod') {
                $config['payload']['methodArgs'] = explode(',', $config['payload']['methodArgs']);

                call_user_func(
                    [$component, $config['payload']['method']],
                    ...$config['payload']['methodArgs']
                );
            }

            foreach ($config['payload']['data'] as $key => $value) {
                static::$state[$component->key][$key] = $component->{$key};
            }

            $pageState = [];
            foreach (array_values(static::$state) as $key => $value) {
                $pageState = array_merge($pageState, $value);
            }

            return [
                'responseType' => 'json',
                'html' => $withoutScripts ?
                    static::compileTemplate($component->render(), static::$state[$component->key]) :
                    str_replace(
                        '</body>',
                        Core::createElement('script', [], ['
                            window._leafUIConfig.methods = ' . json_encode(array_unique(static::$componentMethods)) . ';
                            window._leafUIConfig.components = ' . json_encode(static::$components) . ';
                        ']) . Core::init() . '</body>',
                        static::compileTemplate($component->render(), static::$state[$component->key])
                    ),
                'state' => $pageState,
            ];
        }

        $pageState = [];
        static::$state[$component->key] = array_merge(static::$state[$component->key], get_class_vars($component::class));
        $parsedComponent = static::compileTemplate($component->render(), static::$state[$component->key]);

        foreach (array_values(static::$state) as $key => $value) {
            $pageState = array_merge($pageState, $value);
        }

        return [
            'responseType' => 'html',
            'html' => $withoutScripts ?
                $parsedComponent :
                str_replace('</body>', Core::createElement('script', [], ['
                    window._leafUIConfig = {
                        el: document.querySelector("body"),
                        component: "' . $component::class . '",
                        components: ' . json_encode(static::$components) . ',
                        data: ' . json_encode(array_unique($pageState)) . ',
                        methods: ' . json_encode(array_unique(static::$componentMethods)) . ',
                        path: "' . $_SERVER['REQUEST_URI'] . '",
                        requestMethod: "' . $_SERVER['REQUEST_METHOD'] . '",
                    };
                ']) . Core::init() . '</body>', $parsedComponent),
            'state' => json_encode($pageState),
        ];
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

        return static::compileTemplate(file_get_contents($filename));
    }

    /**
     * Compile Leaf UI Template
     * @param string $rawText The template to compile
     */
    public static function compileTemplate(string $rawText, array $state = []): string
    {
        if (!$state) {
            foreach (array_values(static::$state) as $key => $value) {
                $state = array_merge($state, $value);
            }
        }

        $compiled = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($state) {
            return $state[ltrim(trim($matches[1]), '$')] ?? trigger_error($matches[1] . ' is not defined', E_USER_ERROR);
        }, $rawText);

        $compiled = preg_replace_callback('/\$eval\((.*?)\)/', function ($matches) use ($state) {
            $compiledWithVars = preg_replace_callback('/\$([a-zA-Z0-9_]+)/', function ($matches) use ($state) {
                return $state[$matches[1]] ?? trigger_error($matches[1] . ' is not defined', E_USER_ERROR);
            }, $matches[1]);

            return eval("return $compiledWithVars;");
        }, $compiled);

        $compiled = preg_replace_callback('/@if\([\s\S]*?\)\s*[\s\S]*?(\s*@endif\s*)/', function ($matches) use ($state) {
            $renderedData = '';
            $compiledWithParsedConditions = preg_replace_callback('/\$([a-zA-Z0-9_]+)/', function ($matches) use ($state) {
                return $state[$matches[1]] ?? trigger_error($matches[1] . ' is not defined', E_USER_ERROR);
            }, $matches[0]);

            preg_match('/@if\((.*?)\)/', $compiledWithParsedConditions, $condition);

            if (eval("return $condition[1];") === true) {
                preg_match('/@if\([\s\S]*?\)\s*[\s\S]*?(?:\s*@elseif\([\s\S]*?\)\s*[\s\S]*?|\s*@else\s*[\s\S]*?|\s*@endif\s*)/', $compiledWithParsedConditions, $ifConditionMatches);
                $renderedData = preg_replace('/@if\([\s\S]*?\)\s*[\s\S]*?/', '', $ifConditionMatches[0]);
                $renderedData = preg_replace('/\s*@elseif\([\s\S]*?\)\s*[\s\S]*?/', '', $renderedData);
            } else {
                if (strpos($compiledWithParsedConditions, '@elseif') !== false) {
                    preg_match('/@elseif\((.*?)\)/', $compiledWithParsedConditions, $elseifCondition);

                    if (eval("return $elseifCondition[1];") === true) {
                        preg_match('/@elseif\([\s\S]*?\)\s*[\s\S]*?(?:\s*@elseif\([\s\S]*?\)\s*[\s\S]*?|\s*@else\s*[\s\S]*?|\s*@endif\s*)/', $compiledWithParsedConditions, $elseifConditionMatches);
                        $renderedData = preg_replace('/@elseif\([\s\S]*?\)\s*[\s\S]*?/', '', $elseifConditionMatches[0]);
                        $renderedData = preg_replace('/\s*@else\s*[\s\S]*?/', '', $renderedData);
                    } else if (strpos($compiledWithParsedConditions, '@else') !== false) {
                        preg_match('/@else\s*(.*?)\s*@endif/', $compiledWithParsedConditions, $elseConditionMatches);
                        $renderedData = $elseConditionMatches[1];
                    }
                } else {
                    if (strpos($compiledWithParsedConditions, '@else') !== false) {
                        preg_match('/@else\s*(.*?)\s*@endif/', $compiledWithParsedConditions, $elseConditionMatches);
                        $renderedData = $elseConditionMatches[1];
                    }
                }
            }

            return $renderedData;
        }, $compiled);

        $compiled = preg_replace_callback('/@for\([\s\S]*?\)\s*[\s\S]*?(\s*@endfor\s*)/', function ($matches) {
            return "<?php for ($matches[1]): ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@foreach\([\s\S]*?\)\s*[\s\S]*?(\s*@endforeach\s*)/', function ($matches) {
            return "<?php foreach ($matches[1]): ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@switch\([\s\S]*?\)\s*[\s\S]*?(\s*@endswitch\s*)/', function ($matches) {
            return "<?php switch ($matches[1]): ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@loop\([\s\S]*?\)\s*[\s\S]*@endloop\s*/', function ($matches) {
            return $matches[0];
        }, $compiled);
        
        $compiled = preg_replace_callback('/@case\((.*?)\)/', function ($matches) {
            return "<?php case $matches[1]: ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@break/', function ($matches) {
            return "<?php break; ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@continue/', function ($matches) {
            return "<?php continue; ?>";
        }, $compiled);

        $compiled = preg_replace_callback('/@php\s*([\s\S]+?)\s*@endphp/', function ($matches) {
            return eval($matches[1]);
        }, $compiled);

        $compiled = preg_replace_callback('/@include\((.*?)\)/', function ($matches) use ($state) {
            $viewToInclude = trim($matches[1], '"\'\`');

            $compiledWithVars = preg_replace_callback('/\$([a-zA-Z0-9_]+)/', function ($matches) use ($state) {
                return $state[$matches[1]] ?? trigger_error($matches[1] . ' is not defined', E_USER_ERROR);
            }, $viewToInclude);

            return Core::view($compiledWithVars);
        }, $compiled);

        $compiled = preg_replace_callback('/@component\((.*?)\)/', function ($matches) {
            return Core::component($matches[1]);
        }, $compiled);

        return $compiled;
    }

    /**
     * Embed a component into a view
     * 
     * @param string $component The component to embed
     * @return string
     */
    public static function component(string $component, $state = []): string
    {
        $component = trim($component, '"\'\`');

        if (!class_exists($component)) {
            trigger_error($component . ' does not exist', E_USER_ERROR);
        }

        $component = new $component;
        $component->key = static::randomId($component::class);
        static::$state[$component->key] = get_class_vars($component::class);
        static::$componentMethods = array_merge(static::$componentMethods, get_class_methods($component));
        static::$components = array_merge(static::$components, [$component->key => $component::class]);

        $config = [
            'type' => 'component',
            'payload' => [
                'params' => [],
                'method' => null,
                'methodArgs' => [],
                'component' => $component,
                'data' => static::$state[$component->key],
            ],
        ];

        $componentData = static::buildComponent(new $component, $config, true);

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
