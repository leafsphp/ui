<?php

test('Returns an element', function () {
	$doc = new DOMDocument();
	$doc->loadHTML(
		\Leaf\UI\Core::createElement('p', [], 'Hello World')
	);

	expect(count($doc->getElementsByTagName('p')))->toBe(1);
});

test('Element has a default id assigned', function () {
	$doc = new DOMDocument();
	$doc->loadHTML(
		\Leaf\UI\Core::createElement('p', [], 'Hello World')
	);
	$pTag = $doc->getElementsByTagName('p')->item(0);

	expect(gettype($pTag->getAttribute('id')))->toBe('string');
});
