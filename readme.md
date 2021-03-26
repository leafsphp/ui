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

View the [mini documentation here](//leafphp.netlify.app/#//ui/)

## Working with Leaf UI

What does leaf UI offer you?

Instead of this:

```php
<?php
// all your logic

echo "<section class=\"box\">
  <h2 class=\"box-title\">Item name</h2>
  <p class=\"box-body\">
    Your body here
  </p>
</section>";
?>
```

You get to write this:

```php
<?php
// all your logic

echo section(["class" => "box"], [
  h2("Item name"),
  p("Your body here")
]);
?>
```

In the end, leaf ui allows you to write your whole app peacefully without having to deal with weird strings, interpollation and all that. Just write PHP!

*This file is still being updated!*

Built with ❤ by [**Mychi Darko**](https://mychi.netlify.app)
