<?php

use \Leaf\UI\Core;

it('returns an element', function () {
	$doc = new DOMDocument();
	$doc->loadHTML(
		Core::createElement('p', [], 'Hello World')
	);

	expect(count($doc->getElementsByTagName('p')))->toBe(1);
});

it('allows attribute assignment', function () {
	$doc = new DOMDocument();
	$doc->loadHTML(
		Core::createElement('p', ['title' => 'Hello World'], 'Hello World')
	);
	$pTag = $doc->getElementsByTagName('p')->item(0);

	expect($pTag->getAttribute('title'))->toBe('Hello World');
});

it('allows multiple attribute assignment', function () {
	$doc = new DOMDocument();
	$doc->loadHTML(
		Core::createElement('p', ['title' => 'Hello World', 'class' => 'text-center'], 'Hello World')
	);
	$pTag = $doc->getElementsByTagName('p')->item(0);

	expect($pTag->getAttribute('title'))->toBe('Hello World');
	expect($pTag->getAttribute('class'))->toBe('text-center');
});

it('allows children', function () {
	$doc = new DOMDocument();
	$doc->loadHTML(
		Core::createElement('p', [], 'Hello World')
	);
	$pTag = $doc->getElementsByTagName('p')->item(0);

	expect($pTag->textContent)->toBe('Hello World');
});

it('allows multiple children', function () {
	$doc = new DOMDocument();
	$doc->loadHTML(
		Core::createElement('p', [], ['Hello', 'World'])
	);
	$pTag = $doc->getElementsByTagName('p')->item(0);

	expect($pTag->textContent)->toBe('HelloWorld');
});

it('allows children and attributes', function () {
	$doc = new DOMDocument();
	$doc->loadHTML(
		Core::createElement('p', ['title' => 'Hello World'], 'Hello World')
	);
	$pTag = $doc->getElementsByTagName('p')->item(0);

	expect($pTag->textContent)->toBe('Hello World');
	expect($pTag->getAttribute('title'))->toBe('Hello World');
});

it('allows children and multiple attributes', function () {
	$doc = new DOMDocument();
	$doc->loadHTML(
		Core::createElement('p', ['title' => 'Hello World', 'class' => 'text-center'], 'Hello World')
	);
	$pTag = $doc->getElementsByTagName('p')->item(0);

	expect($pTag->textContent)->toBe('Hello World');
	expect($pTag->getAttribute('title'))->toBe('Hello World');
	expect($pTag->getAttribute('class'))->toBe('text-center');
});

it('allows children and multiple attributes with children as array', function () {
	$doc = new DOMDocument();
	$doc->loadHTML(
		Core::createElement('p', ['title' => 'Hello World', 'class' => 'text-center'], ['Hello', 'World'])
	);
	$pTag = $doc->getElementsByTagName('p')->item(0);

	expect($pTag->textContent)->toBe('HelloWorld');
	expect($pTag->getAttribute('title'))->toBe('Hello World');
	expect($pTag->getAttribute('class'))->toBe('text-center');
});

it('allows children and multiple attributes with children as array and children as array', function () {
	$doc = new DOMDocument();
	$doc->loadHTML(
		Core::createElement('p', ['title' => 'Hello World', 'class' => 'text-center', 'children' => ['Hello', 'World']])
	);
	$pTag = $doc->getElementsByTagName('p')->item(0);

	expect($pTag->textContent)->toBe('HelloWorld');
	expect($pTag->getAttribute('title'))->toBe('Hello World');
	expect($pTag->getAttribute('class'))->toBe('text-center');
});
