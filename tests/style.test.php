<?php

use \Leaf\UI\Core;

it('should create style tag', function () {
    $doc = new DOMDocument();
    $doc->loadHTML(
        Core::createStyles(['body' => 'background: red;'])
    );

    expect(count($doc->getElementsByTagName('style')))->toBe(1);
});

it('should compile to css', function () {
    $doc = new DOMDocument();
    $doc->loadHTML(
        Core::createStyles(['body' => 'background: red;'])
    );
    $styleTag = $doc->getElementsByTagName('style')->item(0);

    expect($styleTag->nodeValue)->toBe('body{background:red}');
});

it('should compile to css with multiple selectors', function () {
    $doc = new DOMDocument();
    $doc->loadHTML(
        Core::createStyles(['body' => 'background: red;', 'h1' => 'color: blue;'])
    );
    $styleTag = $doc->getElementsByTagName('style')->item(0);

    expect($styleTag->nodeValue)->toBe('body{background:red}h1{color:blue}');
});

it('should compile to css with multiple styles', function () {
    $doc = new DOMDocument();
    $doc->loadHTML(
        Core::createStyles(['body' => 'background: red; color: blue;'])
    );
    $styleTag = $doc->getElementsByTagName('style')->item(0);

    expect($styleTag->nodeValue)->toBe('body{background:red;color:blue}');
});

it('should compile to css with multiple styles as an array', function () {
    $doc = new DOMDocument();
    $doc->loadHTML(
        Core::createStyles(['body' => ['background: red;', 'color: blue;']])
    );
    $styleTag = $doc->getElementsByTagName('style')->item(0);

    expect($styleTag->nodeValue)->toBe('body{background:red;color:blue}');
});

it('should compile to css with multiple styles as an array without colons', function () {
    $doc = new DOMDocument();
    $doc->loadHTML(
        Core::createStyles(['body' => ['background: red', 'color: blue']])
    );
    $styleTag = $doc->getElementsByTagName('style')->item(0);

    expect($styleTag->nodeValue)->toBe('body{background:red;color:blue}');
});

it('should compile to css with multiple styles as an associative array without colons', function () {
    $doc = new DOMDocument();
    $doc->loadHTML(
        Core::createStyles(['body' => ['background' => 'red', 'color' => 'blue']])
    );
    $styleTag = $doc->getElementsByTagName('style')->item(0);

    expect($styleTag->nodeValue)->toBe('body{background:red;color:blue}');
});

it('should compile to css with multiple styles and selectors as an array', function () {
    $doc = new DOMDocument();
    $doc->loadHTML(
        Core::createStyles([
            'body' => ['background: red;', 'color: blue;'],
            'section' => ['background: yellow;', 'color: blue;']
        ])
    );
    $styleTag = $doc->getElementsByTagName('style')->item(0);

    expect($styleTag->nodeValue)->toBe('body{background:red;color:blue}section{background:yellow;color:blue}');
});

it('should compile to css with media queries', function () {
    $doc = new DOMDocument();
    $doc->loadHTML(
        Core::createStyles(['@media (min-width: 768px)' => ['body' => 'background: red;']])
    );
    $styleTag = $doc->getElementsByTagName('style')->item(0);

    expect($styleTag->nodeValue)->toBe('@media (min-width:768px){body{background:red}}');
});

it('should compile to css with media queries and multiple styles', function () {
    $doc = new DOMDocument();
    $doc->loadHTML(
        Core::createStyles(['@media (min-width: 768px)' => ['body' => 'background: red;', 'h1' => 'color: blue;']])
    );
    $styleTag = $doc->getElementsByTagName('style')->item(0);

    expect($styleTag->nodeValue)->toBe('@media (min-width:768px){body{background:red}h1{color:blue}}');
});

it('should compile to css with media queries and multiple styles as an array', function () {
    $doc = new DOMDocument();
    $doc->loadHTML(
        Core::createStyles([
            '@media (min-width: 768px)' => [
                'body' => 'background: red;',
                'h1' => ['color: blue;', 'font-size: 20px;']
            ]
        ])
    );
    $styleTag = $doc->getElementsByTagName('style')->item(0);

    expect($styleTag->nodeValue)->toBe('@media (min-width:768px){body{background:red}h1{color:blue;font-size:20px}}');
});

it('should compile to css with media queries and multiple styles and multiple media queries', function () {
    $doc = new DOMDocument();
    $doc->loadHTML(
        Core::createStyles([
            '@media (min-width: 768px)' => ['body' => 'background: red;', 'h1' => 'color: blue;'],
            '@media (min-width: 1024px)' => ['body' => 'background: green;', 'h1' => 'color: yellow;']
        ])
    );
    $styleTag = $doc->getElementsByTagName('style')->item(0);

    expect($styleTag->nodeValue)->toBe('@media (min-width:768px){body{background:red}h1{color:blue}}@media (min-width:1024px){body{background:green}h1{color:yellow}}');
});

it('should compile to css with media queries and multiple styles and multiple media queries and multiple styles', function () {
    $doc = new DOMDocument();
    $doc->loadHTML(
        Core::createStyles([
            '@media (min-width: 768px)' => ['body' => 'background: red;', 'h1' => 'color: blue;'],
            '@media (min-width: 1024px)' => ['body' => 'background: green;', 'h1' => 'color: yellow;'],
            'p' => 'color: red;'
        ])
    );
    $styleTag = $doc->getElementsByTagName('style')->item(0);

    expect($styleTag->nodeValue)->toBe('@media (min-width:768px){body{background:red}h1{color:blue}}@media (min-width:1024px){body{background:green}h1{color:yellow}}p{color:red}');
});
