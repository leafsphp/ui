<?php
namespace Leaf\UI;

/**
 * Leaf UI (Wynter Components) [BETA]
 * ---------------------
 * Leaf UI with a touch of Wynter CSS
 * 
 * @version 1.0.0
 * @author Michael Darko <mychi.darko@gmail.com>
 */
class WynterCSS extends \Leaf\UI {
	/**
	 * Render a wynter UI
	 */
	public static function render($element, $inject = null) {
		parent::render(
			$inject
			.self::_style("src/UI/WynterCSS/wynter.css")
			.self::_style("src/UI/WynterCSS/wynter-exp.css")
			.self::_style("src/UI/WynterCSS/wynter-icons.css")
			.$element
		);
	}

	/**
	 * Wynter Avatar Component
	 */
	public static function _avatar(string $image, string $text = "WYN", array $props = [], array $children = [])
	{
		$f_props = ["wyn:avatar" => "", "data-initial" => $text];
		if (isset($props["size"])) {
			$f_props["wyn:avatar-{$props["size"]}"] = "";
			unset($props["size"]);
		}
		if (isset($props["badge"])) {
			$f_props["wyn:badge data-badge"] = $props["badge"];
			unset($props["badge"]);
		}
		if (isset($props["presence"])) {
			$children[] = self::i([], ["wyn:avatar-presence-{$props['presence']}" => ""]);
			unset($props["presence"]);
		}
		if (strlen($image) < 1) {
			return self::figure($f_props, $children);
		}
		$children[] = self::img($image, ["alt" => " "]);
		return self::figure($f_props, $children);
	}

	public static function _badge(string $badge, array $props = [], $children = [])
	{
		$props["wyn:badge data-badge"] = $badge;
		return self::span($props, $children);
	}

	public static function _breadcrumb(array $breadcrumbs)
	{
		$links = "";
		$links .= self::create_element("ul", ["wyn:breadcrumb-arrow" => ""], [
			self::loop($breadcrumbs, function($link, $text) {
				return self::create_element("li", ["wyn:breadcrumb-item" => ""], [
					self::a(["href" => $link], $text)
				]);
			})
		]);
		return $links;
	}

	public static function _button(string $text, array $props = [])
	{
		$props["wyn:btn"] = "";
		if (isset($props["variant"])) {
			$props["wyn:btn-{$props['variant']}"] = "";
		}
		return self::button($text, $props);
	}

	public static function _btnGroup(array $children, array $props = [])
	{
		$props["wyn:btn-group"] = "";
		return self::div($props, $children);
	}

	public static function _card(array $props = [], array $children = [])
	{
		$props["wyn:card"] = "";
		return self::div($props, $children);
	}

	public static function _carousel(array $children, array $props = []) 
	{
		$props["class"] = isset($props["class"])  ? $props["class"] . " carousel" : "carousel";

		return self::div($props, [
			self::loop($children, function() {
				return self::input("radio", "carousel-radio", [
					"class" => "carousel-locator",
					"id" => "slide-1",
					"hidden" => "",
					"checked" => "",
					"name" => "carousel-radio"
				]);
			}),
			self::div(["class" => "carousel-container"], [
				self::loop($children, function($child) {
					return $child;
				}),
			]),
			self::div(["class" => "carousel-nav"], [
				self::loop($children, function($child, $key) {
					return self::label((int) $key + 1, "slide-1", ["class" => "nav-item text-hide c-hand"]);
				}),
			])
		]);
	}

	public static function _carouselItem(string $back, string $next, $children, array $props = [])
	{
		$props["class"] = isset($props["class"])  ? $props["class"] . " carousel-item" : "carousel-item";
		return self::figure($props, [
			self::create_element("label", ["class" => "item-prev btn btn-action btn-lg", "for" => $back], [
				self::_icon("arrow-left")
			]),
			self::create_element("label", ["class" => "item-next btn btn-action btn-lg", "for" => $next], [
				self::_icon("arrow-right")
			]),
			$children
		]);
	}

	public static function _chip(string $text, array $children = [])
	{
		$subs = [$text];
		foreach ($children as $child) {
			$subs[] = $child;
		}
		return self::span(["wyn:chip" => ""], $subs);
	}

	public static function _container($children, array $props = [])
	{
		$props["class"] = isset($props["class"])  ? $props["class"] . " container" : "container";
		return self::div($props, $children);
	}

	public static function _fab(array $props = ["icon" => "plus"])
	{
		$icon = "";
		$label = "";
		$prop["wyn:fab"] = "";
		if (isset($props["icon"])) {
			$icon = self::_icon($props["icon"]);
			unset($props["icon"]);
		}
		if (isset($props["label"])) {
			$label = $props["label"];
			unset($props["label"]);
		}
		$props["wyn:fab"] = "";
		return self::button($icon.$label, $props);
	}

	public static function _icon(string $icon, array $props = []) {
		$props["class"] = isset($props["class"])  ? "icon icon-$icon " . $props["class"] : "icon icon-$icon";
		if (isset($props["size"])) {
			$props["class"] .= " icon-{$props["size"]}";
			unset($props["size"]);
		}
		return self::i("", $props);
	}

	public static function _xCard(array $props = [], array $options = ["title" => "Card"])
	{
		return self::_card($props, [
			isset($options["image"]) ? (self::div(["wyn:card-image" => ""], [
					self::img($options["image"], ["class" => "img-responsive", "alt" => " "])
				])) : null,
			self::div(["wyn:card-header" => ""], [
				self::div(["class" => "card-title h5"], $options["title"]),
				isset($options["subtitle"]) ? (self::div(["class" => "card-subtitle text-gray"], $options["subtitle"])) : null
			]),
			self::div(["wyn:card-body" => ""], $options["body"]),
			self::div(["wyn:card-footer" => ""], $options["footer"])
		]);
	}
}