<!-- markdownlint-disable no-inline-html -->
<p align="center">
  <br><br>
  <img src="https://leafphp.dev/logo-circle.png" height="100"/>
  <br>
</p>

<h1 align="center">Leaf UI [WIP v0.2.0]</h1>

Leaf UI is a PHP library for building user interfaces.

Leaf UI doesn't need a new compiler or any extensive compiling, it's just the same old PHP you write everyday; as such, you can build full scalable Leaf UI powered apps or just sprinkle Leaf UI into your existing HTML/PHP code.

v0.2.0 of Leaf UI is currently in development, it is a complete rewrite of the library that comes with a lot of new features and a new API. Leaf UI v0.2.0 will allow you to build full scalable Leaf UI powered apps, write reactive UIs all in PHP. You can think of it as a PHP version of React.

## Installing Leaf UI

Like most PHP libraries, we recommend installing Leaf UI with the [Leaf CLI](https://cli.leafphp.dev):

```bash
leaf install ui@dev-next
```

Or with [composer](//getcomposer.org). Just open up your console and type:

```bash
composer require leafs/ui:dev-next
```

After this, you can use all of Leaf UI's methods and components.

View the [documentation here](https://staging.ui.leafphp.dev/)

## Building your first Leaf UI

Since Leaf UI is modelled after React, everything is a component. You can create your own components and handle your application state in them.

```php
<?php

use Leaf\UI\Component;

class Test2 extends Component
{
    // every component needs a unique key
    public $key = "test2";
    public $count = 1;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }

    public function render()
    {
        // your UI will go here
        return '
            <body>
                <div>
                    <div>Static text</div>
                    <button @click="decrement">-</button>
                    <h1>{{ $count }}</h1>
                    <button @click="increment">+</button>
                </div>
            </body>
        ';
    }
}
```

This component renders some static text, a button to decrement a counter, a counter and a button to increment the counter. The counter is stored in the component's state and is updated when the buttons are clicked.

To actually make this work, you simply need to render this component wherever you want it to appear.

```php
<?php

use Leaf\UI;

require __DIR__ . '/vendor/autoload.php';

UI::render(new Test2());
```

The most beautiful part about all this is that it can run outside Leaf. It is completely independent of Leaf or any other framework and can be used in any PHP application.

_This file is still being updated!_

***Docs @ https://staging.ui.leafphp.dev are still being updated.***

## 💬 Stay In Touch

- [Twitter](https://twitter.com/leafphp)
- [Join the forum](https://github.com/leafsphp/leaf/discussions/37)
- [Chat on discord](https://discord.com/invite/Pkrm9NJPE3)

## 📓 Learning Leaf 3

- Leaf has a very easy to understand [documentation](https://leafphp.dev) which contains information on all operations in Leaf.
- You can also check out our [youtube channel](https://www.youtube.com/channel/UCllE-GsYy10RkxBUK0HIffw) which has video tutorials on different topics
- We are also working on codelabs which will bring hands-on tutorials you can follow and contribute to.

## 😇 Contributing

We are glad to have you. All contributions are welcome! To get started, familiarize yourself with our [contribution guide](https://leafphp.dev/community/contributing.html) and you'll be ready to make your first pull request 🚀.

To report a security vulnerability, you can reach out to [@mychidarko](https://twitter.com/mychidarko) or [@leafphp](https://twitter.com/leafphp) on twitter. We will coordinate the fix and eventually commit the solution in this project.

## 🤩 Sponsoring Leaf

Your cash contributions go a long way to help us make Leaf even better for you. You can sponsor Leaf and any of our packages on [open collective](https://opencollective.com/leaf) or check the [contribution page](https://leafphp.dev/support/) for a list of ways to contribute.

And to all our existing cash/code contributors, we love you all ❤️

View the [sponsors](https://leafphp.dev/support/) page to see all our sponsors.
