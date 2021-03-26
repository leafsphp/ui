<?php

use Leaf\UI;

/*
|--------------------------------------------------------------------------
| Structural HTML Tags
|--------------------------------------------------------------------------
*/

/**
 * Import/Use a styleheet
 * 
 * @param string|array $src The styles/stylesheet to apply
 * @param array $props The attributes for style/link tag
 */
function Style($src, array $props = [])
{
    if (!is_array($src)) {
        return UI::createElement("link", ["href" => $src, "rel" => "stylesheet"], UI::SINGLE_TAG);
    }

    return UI::createStyles($src, $props);
}

/**
 * Import/Use a script
 * 
 * @param string|array $src The internal/external scripts to apply
 * @param array $props The attributes for style/link tag
 */
function Script($src, array $props = [])
{
    if (is_string($src)) {
        $props["src"] = $src;
        return UI::createElement("script", $props);
    }

    return UI::createElement("script", array_merge(
        $props,
        ["children" => $src]
    ));
}

/*
|--------------------------------------------------------------------------
| Structural HTML Tags
|--------------------------------------------------------------------------
*/

/**
 * HTML Element
 * 
 * @param array $children HTML Element children
 * @param array $props Attributes for HTML element
 */
function html(array $children, array $props = [])
{
    return UI::createElement("!Doctype html", [], UI::SINGLE_TAG) . UI::createElement("html", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * Head Element
 * 
 * @param array $children Head Element children
 * @param array $props Attributes for Head element
 */
function head(array $children, array $props = [])
{
    return UI::createElement("head", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * body Element
 * 
 * @param array $children body Element children
 * @param array $props Attributes for body element
 */
function body(array $children, array $props = [])
{
    return UI::createElement("body", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * header Element
 * 
 * @param array $children header Element children
 * @param array $props Attributes for header element
 */
function _header(array $children = [], array $props = [])
{
    return UI::createElement("header", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * nav Element
 * 
 * @param array $props Attributes for nav element
 * @param array $children nav Element children
 */
function nav(array $children = [], array $props = [])
{
    return UI::createElement("nav", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * footer Element
 * 
 * @param array $props Attributes for footer element
 * @param array $children footer Element children
 */
function footer(array $children = [], array $props = [])
{
    return UI::createElement("footer", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * aside Element
 * 
 * @param array $props Attributes for aside element
 * @param array $children aside Element children
 */
function aside(array $children = [], array $props = [])
{
    return UI::createElement("aside", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * Line Break
 * 
 * @param array $props Attributes for br element
 */
function br(array $props = [])
{
    return UI::createElement("br", $props, UI::SINGLE_TAG);
}

/**
 * Horizontal Rule
 * 
 * @param array $props Attributes for hr element
 */
function hr(array $props = [])
{
    return UI::createElement("hr", $props, UI::SINGLE_TAG);
}

/**
 * HTML anchor Element 
 * 
 * @param array $props Element props
 * @param string|array $children Children
 */
function a(array $props = [], $children = [])
{
    return UI::createElement("a", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML OL Element 
 * 
 * @param array $props Element props
 * @param string|array $children Children
 */
function ol(array $props = [], $children = [])
{
    return UI::createElement("ol", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML UL Element 
 * 
 * @param array $props Element props
 * @param string|array $children Children
 */
function ul(array $props = [], $children = [])
{
    return UI::createElement("ul", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML LI Element 
 * 
 * @param array $props Element props
 * @param string|array $children Children
 */
function li(array $props = [], $children = [])
{
    return UI::createElement("li", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML DIV Element 
 * 
 * @param array $props Element props
 * @param string|array $children Children
 */
function div(array $props = [], $children = [])
{
    return UI::createElement("div", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML Span Element
 * 
 * @param array $props Element props
 * @param string|array $children Children
 */
function span(array $props = [], $children = [])
{
    return UI::createElement("span", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * Section Element
 * 
 * @param array $props Element props
 * @param string|array $children Children
 */
function section(array $props = [], array $children = [])
{
    return UI::createElement("section", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML hgroup Element
 * 
 * @param string|array $children Children
 * @param array $props Element props
 */
function hgroup($children, array $props = [])
{
    return UI::createElement("hgroup", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML H1 Element
 * 
 * @param string|array $children Children
 * @param array $props Element props
 */
function h1($children, array $props = [])
{
    return UI::createElement("h1", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML H2 Element
 * 
 * @param string|array $children Children
 * @param array $props Element props
 */
function h2($children, array $props = [])
{
    return UI::createElement("h2", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML H3 Element
 * 
 * @param string|array $children Children
 * @param array $props Element props
 */
function h3($children, array $props = [])
{
    return UI::createElement("h3", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML H4 Element
 * 
 * @param string|array $children Children
 * @param array $props Element props
 */
function h4($children, array $props = [])
{
    return UI::createElement("h4", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML H5 Element
 * 
 * @param string|array $children Children
 * @param array $props Element props
 */
function h5($children, array $props = [])
{
    return UI::createElement("h5", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML H6 Element
 * 
 * @param string|array $children Children
 * @param array $props Element props
 */
function h6($children, array $props = [])
{
    return UI::createElement("h6", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML Blockquote
 * 
 * @param string|array $children Children
 * @param array $props Element props
 */
function blockquote($children, array $props = [])
{
    return UI::createElement("blockquote", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML Paragraph Element
 * 
 * @param string|array $children Children
 * @param array $props Element props
 */
function p($children, array $props = [])
{
    return UI::createElement("p", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML Article Element
 * 
 * @param array $props Element props
 * @param array $children Children
 */
function article(array $props = [], array $children = [])
{
    return UI::createElement("article", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML 5 Details Element
 * 
 * @param array $props Element props
 * @param array $children Children
 */
function details(array $props = [], array $children = [])
{
    return UI::createElement("details", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * Html summary
 * 
 * @param array $props Element props
 * @param array $children Children
 */
function summary(array $props = [], array $children = [])
{
    return UI::createElement("summary", array_merge(
        $props,
        ["children" => $children]
    ));
}

/*
|--------------------------------------------------------------------------
| Meta-data HTML Tags
|--------------------------------------------------------------------------
*/

/**
 * Html Link Tag
 * 
 * @param string $href Link to resource
 * @param string $rel Resource's relation to current document
 * @param array $props Link tag attributes
 */
function _link(string $href, string $rel = "stylesheet", array $props = [])
{
    $props["href"] = $href;
    $props["rel"] = $rel;

    return UI::createElement("link", $props, UI::SINGLE_TAG);
}

/**
 * Html Base Tag
 * 
 * @param string $href base url
 * @param array $props Link tag attributes
 */
function base(string $href, array $props = [])
{
    $props["href"] = $href;
    return UI::createElement("base", $props, UI::SINGLE_TAG);
}

/**
 * Title element
 * 
 * @param string $title The document title
 * @param array $props Title Element props
 */
function title(string $title, array $props = [])
{
    return UI::createElement("title", array_merge(
        $props,
        ["children" => $title]
    ));
}

/**
 * Meta Tag
 * 
 * @param string $name meta name property
 * @param string $content meta content property
 * @param array $props Additional props
 */
function meta(string $name, string $content, array $props = [])
{
    $props["name"] = $name;
    $props["content"] = $content;
    return UI::createElement("meta", $props, UI::SINGLE_TAG);
}

/*
|--------------------------------------------------------------------------
| Formatting Tags
|--------------------------------------------------------------------------
*/

/**
 * tt tag
 * 
 * @param array|string $children Children
 * @param array $props Element props
 */
function tt($children, array $props = [])
{
    return UI::createElement("tt", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * abbr element
 * 
 * @param string $title Long Text
 * @param string $children Short Text
 * @param array $props Element props
 */
function abbr(string $title, string $children, array $props = [])
{
    $props["title"] = $title;
    return UI::createElement("abbr", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * HTML address Element 
 * 
 * @param array $children Children
 * @param array $props Element props
 */
function address(array $children, array $props = [])
{
    return UI::createElement("address", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * bdi tag
 * 
 * @param string $children Children
 * @param array $props Element props
 */
function bdi(string $children, array $props = [])
{
    return UI::createElement("bdi", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * bdo tag
 * 
 * @param string $children Children
 * @param array $props Element props
 */
function bdo(string $children, array $props = [])
{
    return UI::createElement("bdo", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * b tag
 * 
 * @param array|string $children Children
 * @param array $props Element props
 */
function b($children, array $props = [])
{
    return UI::createElement("b", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * i tag
 * 
 * @param array|string $children Children
 * @param array $props Element props
 */
function i($children, array $props = [])
{
    return UI::createElement("i", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * u tag
 * 
 * @param array|string $children Children
 * @param array $props Element props
 */
function u($children, array $props = [])
{
    return UI::createElement("u", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * small tag
 * 
 * @param array|string $children Children
 * @param array $props Element props
 */
function small($children, array $props = [])
{
    return UI::createElement("small", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * big tag
 * 
 * @param array|string $children Children
 * @param array $props Element props
 */
function big($children, array $props = [])
{
    return UI::createElement("big", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * sub tag
 * 
 * @param array|string $children Children
 * @param array $props Element props
 */
function sub($children, array $props = [])
{
    return UI::createElement("sub", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * sup tag
 * 
 * @param array|string $children Children
 * @param array $props Element props
 */
function sup($children, array $props = [])
{
    return UI::createElement("sup", array_merge(
        $props,
        ["children" => $children]
    ));
}

/*
|--------------------------------------------------------------------------
| Embedded Content Tags
|--------------------------------------------------------------------------
*/

/**
 * figure Element
 * 
 * @param array $props Attributes for figure element
 * @param array $children figure Element children
 */
function figure(array $props = [], array $children = [])
{
    return UI::createElement("figure", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * img Element
 * 
 * @param array $image image source
 * @param array $props Attributes for img element
 */
function img(string $image, array $props = [])
{
    $props["src"] = $image;
    return UI::createElement("img", $props, UI::SINGLE_TAG);
}

/*
|--------------------------------------------------------------------------
| Form Tags
|--------------------------------------------------------------------------
*/

/**
 * Shorthand method for creating an HTML form element
 * 
 * @param string $method HTTP method
 * @param string $action Form action
 * @param array $fields Form Fields
 * @param array $props Other attributes eg: `style`
 */
function form(string $method, string $action, array $fields, array $props = [])
{
    $props["action"] = $action;
    $props["method"] = $method;

    return UI::createElement("form", $props, $fields);
}

/**
 * Shorthand method for creating an HTML form label
 * 
 * @param string|array $label Children
 * @param string $id Label ID
 * @param array $props Other attributes eg: `style`
 */
function label(string $label, string $id = null, array $props = [])
{
    if (!$id) {
        $id = UI::random_id($label);
    }
    $props["id"] = $id;
    $props["for"] = $id;
    return UI::createElement("label", $props, is_array($label) ? $label : [$label]);
}

/**
 * Shorthand method for creating an HTML form input
 * 
 * @param string $type Input type
 * @param string $name Input name
 * @param array $props Other attributes eg: `style` and `value`
 */
function input(string $type, string $name, array $props = [])
{
    $id = UI::random_id($type);
    $output = "";

    if (!isset($props["id"])) {
        $props["id"] = $id;
    } else {
        $id = $props["id"];
    }

    if (isset($props['label'])) {
        $output .= UI::label($props['label'], $id);
    }

    $props["type"] = $type;
    $props["name"] = $name;

    $output .= UI::createElement("input", $props, []);
    return $output;
}

/**
 * HTML Textarea component
 * 
 * @param string $name textarea name
 * @param array $props Other attributes eg: `style` and `value`
 * @param string $children Textarea text
 */
function textarea(string $name, array $props = [], string $children = "")
{
    $id = UI::random_id($name);
    $output = "";

    if (!isset($props["id"])) {
        $props["id"] = $id;
    } else {
        $id = $props["id"];
    }

    if (isset($props['label'])) {
        $output .= UI::label($props['label'], $id);
        unset($props["label"]);
    }

    $props["name"] = $name;

    $output .= UI::createElement("textarea", array_merge(
        $props,
        ["children" => $children]
    ));
    return $output;
}

/**
 * Datalist element
 * 
 * @param string $id id for datalist element
 * @param array $list A list of datalist values
 * @param array $props Attributes for datalist
 */
function datalist(string $id, array $list, array $props = [])
{
    $props["id"] = $id;
    return UI::createElement("datalist", $props, UI::loop($list, function ($value) {
        return UI::option($value);
    }));
}

/**
 * HTML Select element
 * 
 * @param array $list A list of datalist values
 * @param array $props Attributes for datalist
 */
function select(array $list, array $props = [])
{
    return UI::createElement("select", $props, UI::loop($list, function ($text, $value) {
        return UI::option($value, $text);
    }));
}

/**
 * Option Tag
 * 
 * @param string $value Value property
 * @param string $text Text displayed to the user
 * @param array $props Additional props
 */
function option(string $value, string $text = "", array $props = [])
{
    $props["value"] = $value;
    return UI::createElement("option", array_merge(
        $props,
        ["children" => $text]
    ));
}

/**
 * HTML Button Element
 * 
 * @param string|array $text Text displayed on button
 * @param array $props Button properties
 */
function button($text, array $props = [])
{
    return UI::createElement("button", array_merge(
        $props,
        ["children" => $text]
    ));
}

/*
|--------------------------------------------------------------------------
| Custom Leaf UI Tags
|--------------------------------------------------------------------------
*/

/**
 * Render uppercase text
 * 
 * @param string $children Children
 * @param array $props Element props
 */
function Uppercase($children, array $props = [])
{
    $children = strtoupper($children);
    return UI::createElement("p", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * Render lowercase text
 * 
 * @param string $children Children
 * @param array $props Element props
 */
function Lowercase(string $children, array $props = [])
{
    $children = strtolower($children);
    return UI::createElement("p", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * Custom div Element (padding container)
 * 
 * @param string|array $children Children
 * @param array $props Element props
 */
function Container($children, array $props = [])
{
    if (!isset($props["style"])) {
        $props["style"] = "";
    }
    $props["style"] = "padding: 12px 25px; " . $props["style"];
    return UI::createElement("div", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * Custom div Element (flex row)
 * 
 * @param array $children Children
 * @param array $props Element props
 */
function Row(array $children, array $props = [])
{
    if (!isset($props["style"])) {
        $props["style"] = "";
    }
    $props["style"] = "display: flex; " . $props["style"];
    return UI::createElement("div", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * Custom div Element (flex column)
 * 
 * @param array $children Children
 * @param array $props Element props
 */
function Column(array $children, array $props = [])
{
    if (!isset($props["style"])) {
        $props["style"] = "";
    }
    $props["style"] = "display: flex; flex-direction: column; " . $props["style"];
    return UI::createElement("div", array_merge(
        $props,
        ["children" => $children]
    ));
}

/**
 * Custom Datalist element
 * 
 * @param string $type input type
 * @param string $name input name
 * @param string $id id for datalist element
 * @param array $list A list of datalist values
 * @param array $props Attributes for datalist wrapper
 */
function CustomDatalist(string $type, string $name, string $id, array $list, array $props = [])
{
    return UI::div($props, [
        UI::input($type, $name, ["list" => $id]),
        UI::datalist($id, $list)
    ]);
}

/**
 * Custom preloader component
 * 
 * @param string|array $children Item to display as preloader
 * @param array $props Preloader properties
 */
function Preloader($children, array $props = [])
{
    if (!isset($props["id"])) $props["id"] = "leaf-ui-preloader";
    return UI::_fragment([
        UI::_style([
            "#{$props['id']}" => "
                position: fixed !important;
                z-index: 1000 !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                flex-direction: column;
                background: white;
            ",
            "#{$props['id']} > *" => "
                width: 100px;
            ",
            "#{$props['id']}.hidden" => "
                animation: fadeOut 1.5s !important;
                animation-fill-mode: forwards !important;
            ",
            "@keyframes fadeOut" => [
                "100%" => "
                    opacity: 0;
                    visibility: hidden;
                "
            ]
        ]),
        UI::div(array_merge(
            $props,
            ["children" => $children]
        )),
        UI::_script(["
            window.addEventListener('load', function() {
                const leafUILoader = document.getElementById('{$props['id']}');
                leafUILoader.className += ' hidden';
            });
        "])
    ]);
}
