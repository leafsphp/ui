# Leaf UI

Leaf UI is a PHP library for building user interfaces.

Leaf UI simply takes away the 100s of lines of crazy HTML you'd need to write, so you can focus on only PHP. Leaf UI doesn't need a new compiler or any extensive compiling, it's just the same old PHP you write everyday; as such, you can build full scaleable Leaf UI powered apps or just sprinkle Leaf UI into your existing HTML/PHP code.

## Installing Leaf UI

Like most PHP libraries, we recommend installing Leaf UI with [composer](//getcomposer.org). Just open up your console and type:

```bash
composer require leafs/ui
```

This will install Leaf UI into your application. You don't have to worry about bloating your application: Leaf UI has no external dependencies.

After this, you can use all of Leaf UI's methods and components.

View the [mini documentation here](//leafphp.netlify.app/#/2.1/views/ui/)

## Working With Templates

Templates provide a quick way to scaffold a Leaf UI without sweating the tiny details. To get started, simply swap out the main the `Leaf\UI` package for `Leaf\UI\Template`.

```php
use Leaf\UI\Template as UI;

UI::element(...);
```

```php
$ui = new Leaf\UI\Template;

$ui::element(...);
```

### Template Methods

#### _template

This method let's you create a plain HTML structure consisting which looks somewhat like this when rendered:

```html
<!Doctype html  id="1590108145!Doctype html" />
<html  id="1590108145html">
	<head  id="1590108145head">
		<title  id="1590108145title">{ Title Here }</title>
		<meta name="viewport" content="width=device-width, initial-scale=1"  id="1590108145meta" />
		<link href="vendor/leafs/ui/src/UI/default/default.css" rel="stylesheet"  id="1590108145link" />
		{ Other Head Elements Here}
	</head>
	<body  id="1590108145body">
		{ Body Here }
	</body>
</html>
```
