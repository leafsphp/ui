<?php

namespace Leaf\UI;

use Leaf\UI;
use MatthiasMullie\Minify\CSS;

/**
 * This class handles the internal parsing of Leaf UI components
 */
class Parser {
    /**
     * Compile Leaf UI Template
     * @param string $rawText The template to compile
     */
    public static function compileTemplate(string $rawText, array $state = []): string
    {
        $compiled = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($state) {
            return $state[ltrim(trim($matches[1]), '$')] ?? trigger_error($matches[1] . ' is not defined', E_USER_ERROR);
        }, $rawText);

        $compiled = preg_replace_callback('/<style.*?>(.*?)<\/style>/is', function ($matches) {
            $newCSS = (new CSS())->add($matches[1])->minify();
            return str_replace($matches[1], $newCSS, $matches[0]);
        }, $compiled);

        $compiled = preg_replace_callback('/\$eval\((.*?)\)/', function ($matches) use ($state) {
            $compiledWithVars = preg_replace_callback('/\$([a-zA-Z0-9_]+)/', function ($matches) use ($state) {
                return $state[$matches[1]] ?? trigger_error($matches[1] . ' is not defined', E_USER_ERROR);
            }, $matches[1]);

            return eval("return $compiledWithVars;");
        }, $compiled);

        $compiled = preg_replace_callback('/@loop\([\s\S]*?\)\s*[\s\S]*@endloop\s*/', function ($matches) use ($state) {
            $rendered = '';
            $loopMatches = null;

            preg_match('/@loop\((.*?)\)/', $matches[0], $loopMatches);

            $dataToLoop = "return $loopMatches[1];";

            if (strpos($loopMatches[1], '$') !== false) {
                $loopMatches[1] = $state[ltrim(trim($loopMatches[1]), '$')] ?? trigger_error($loopMatches[1] . ' is not defined', E_USER_ERROR);
                $dataToLoop = 'return json_decode(\'' . json_encode($loopMatches[1]) . '\', true);';
            }

            static::loop(eval($dataToLoop), function ($value, $key) use ($matches, &$rendered, $state) {
                $regex = '/@loop\((.*?)\)([\s\S]*?)@endloop/';
                preg_match($regex, $matches[0], $regexLoopMatches);

                preg_match('/@key/', $regexLoopMatches[2], $keyMatches);
                preg_match('/@value/', $regexLoopMatches[2], $valueMatches);

                $renderedString = str_replace(
                    ['@key', '@value', "\""],
                    [$key, '$value', "'"],
                    preg_replace(
                        '/@value\[[\'"][^\'"]*[\'"]\]/',
                        '{$0}',
                        preg_replace_callback(
                            '/@if\((.*?)\)/',
                            function ($ifStatementMatches) {
                                $compiledIf = '';

                                if (strpos($ifStatementMatches[1], '@value[') !== false) {
                                    $compiledIf = preg_replace('/@value\[[\'"]([^\'"]*)[\'"]\]/', '\'@value[\'$1\']\'', $ifStatementMatches[0]);
                                }

                                if (strpos($compiledIf, '@key') !== false) {
                                    $compiledIf = str_replace('@key', '\'@key\'', $compiledIf);
                                }

                                return $compiledIf;
                            },
                            $regexLoopMatches[2]
                        )
                    )
                );

                $rendered .= eval("\$value = json_decode('" . json_encode($value) . "', true); return \"$renderedString\";");
            });

            return $rendered;
        }, $compiled);

        $compiled = preg_replace_callback('/@if\([\s\S]*?\)\s*[\s\S]*?(\s*@endif\s*)/', function ($matches) use ($state) {
            $renderedData = '';
            $compiledWithParsedConditions = preg_replace_callback('/\$([a-zA-Z0-9_]+)/', function ($matches) use ($state) {
                return $state[$matches[1]] ?? trigger_error($matches[1] . ' is not defined', E_USER_ERROR);
            }, $matches[0]);

            preg_match('/@if\((.*?)\)/', $compiledWithParsedConditions, $condition);

            if (eval("return $condition[1];") === true) {
                preg_match(
                    '/@if\([\s\S]*?\)\s*[\s\S]*?(?:\s*@elseif\([\s\S]*?\)\s*[\s\S]*?|\s*@else\s*[\s\S]*?|\s*@endif\s*)/',
                    $compiledWithParsedConditions,
                    $ifConditionMatches
                );

                $renderedData = preg_replace('/@if\([\s\S]*?\)\s*[\s\S]*?/', '', $ifConditionMatches[0]);
                $renderedData = preg_replace('/\s*@elseif\([\s\S]*?\)\s*[\s\S]*?/', '', $renderedData);
                $renderedData = preg_replace('/\s*@else\s*[\s\S]*?/', '', $renderedData);
            } else {
                if (strpos($compiledWithParsedConditions, '@elseif') !== false) {
                    preg_match('/@elseif\((.*?)\)/', $compiledWithParsedConditions, $elseifCondition);

                    if (eval("return $elseifCondition[1];") === true) {
                        preg_match(
                            '/@elseif\([\s\S]*?\)\s*[\s\S]*?(?:\s*@elseif\([\s\S]*?\)\s*[\s\S]*?|\s*@else\s*[\s\S]*?|\s*@endif\s*)/',
                            $compiledWithParsedConditions,
                            $elseifConditionMatches
                        );

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

        $compiled = preg_replace_callback('/@php\s*([\s\S]+?)\s*@endphp/', function ($matches) {
            return eval($matches[1]);
        }, $compiled);

        $compiled = preg_replace_callback('/@include\((.*?)\)/', function ($matches) use ($state) {
            $viewToInclude = trim($matches[1], '"\'\`');

            $compiledWithVars = preg_replace_callback('/\$([a-zA-Z0-9_]+)/', function ($matches) use ($state) {
                return $state[$matches[1]] ?? trigger_error($matches[1] . ' is not defined', E_USER_ERROR);
            }, $viewToInclude);

            return UI::view($compiledWithVars);
        }, $compiled);

        $compiled = preg_replace_callback('/@component\((.*?)\)/', function ($matches) {
            $paramsArray = preg_split('/,\s*/', $matches[1]);
            $props = preg_replace('/\s+/', '', $paramsArray[1] ?? '[]');
            return UI::component($paramsArray[0], eval("return $props;"));
        }, $compiled);

        return $compiled;
    }

    /**
     * Loop over an array of items
     * 
     * @param array|string|int $array The array to loop through
     * @param callable $handler Call back function to run per iteration
     */
    public static function loop($array, callable $handler)
    {
        $element = "";

        if (!is_array($array)) {
            $array = explode(',', str_repeat(',', (int) $array - 1));
        }

        if (is_callable($handler)) {
            foreach ($array as $key => $value) {
                $element .= call_user_func($handler, $value, $key);
            }
        }

        return $element;
    }

    public static function parseStyles(array $styles): string
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
