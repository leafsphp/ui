<!-- markdownlint-disable no-inline-html -->
<p align="center">
  <br><br>
  <img src="https://leafphp.dev/logo-circle.png" height="100"/>
  <br>
</p>

<h1 align="center">Leaf UI [WIP v0.2.0]</h1>

Leaf UI is a PHP library for building user interfaces.

Leaf UI doesn't need a new compiler or any extensive compiling, it's just the same old PHP you write everyday; as such, you can build full scalable Leaf UI powered apps or just sprinkle Leaf UI into your existing HTML/PHP code.

v0.2.0 of Leaf UI is currently in development. It's a complete rewrite of the library and will be released soon. It comes with a lot of new features and a new API. Leaf UI v0.2.0 will allow you to build full scalable Leaf UI powered apps, write reactive UIs all in PHP. You can think of it as a PHP version of React.

## Installing Leaf UI

Like most PHP libraries, we recommend installing Leaf UI with [composer](//getcomposer.org). Just open up your console and type:

```bash
composer require leafs/ui
```

Or install the next version of Leaf UI:

```bash
composer require leafs/ui@dev-next
```

This will install Leaf UI into your application. You don't have to worry about bloating your application: Leaf UI has no external dependencies.

After this, you can use all of Leaf UI's methods and components.

View the [mini documentation here](//leafphp.netlify.app/#//ui/)

## Building your first Leaf UI

Since Leaf UI is modelled after React, everything is a component. You can create your own components and handle your application state in them.

```php
<?php

use Leaf\UI\Core;
use Leaf\UI\Component;

class Test2 extends Component
{
    public $count = 1;

    public function increment()
    {
        $this->count = $this->count + 1;
    }

    public function decrement()
    {
        $this->count = $this->count - 1;
    }

    public function render()
    {
        return Core::createElement('body', [], [
            Core::createElement('div', [], [
                Core::createElement('div', [], 'Static text'),
                Core::createElement('button', ['@click' => 'decrement'], '-'),
                Core::createElement('h1', [], $this->count),
                Core::createElement('button', ['@click' => 'increment'], '+'),
            ]),
            Core::init(),
        ]);
    }
}
```

This component renders some static text, a button to decrement a counter, a counter and a button to increment the counter. The counter is stored in the component's state and is updated when the buttons are clicked.

To actually make this work, you simply need to render this component wherever you want it to appear.

```php
<?php

use Leaf\UI\Core;

require_once __DIR__ . '/vendor/autoload.php';

echo Core::render(new Test2());
```

The most beautiful part about all this is that it can run outside Leaf. It is completely independent of Leaf and can be used in any PHP application.

_This file is still being updated!_

## 💬 Stay In Touch

-   [Twitter](https://twitter.com/leafphp)
-   [Join the forum](https://github.com/leafsphp/leaf/discussions/37)
-   [Chat on discord](https://discord.com/invite/Pkrm9NJPE3)

## 📓 Learning Leaf 3

-   Leaf has a very easy to understand [documentation](https://leafphp.dev) which contains information on all operations in Leaf.
-   You can also check out our [youtube channel](https://www.youtube.com/channel/UCllE-GsYy10RkxBUK0HIffw) which has video tutorials on different topics
-   We are also working on codelabs which will bring hands-on tutorials you can follow and contribute to.

## 😇 Contributing

We are glad to have you. All contributions are welcome! To get started, familiarize yourself with our [contribution guide](https://leafphp.dev/community/contributing.html) and you'll be ready to make your first pull request 🚀.

To report a security vulnerability, you can reach out to [@mychidarko](https://twitter.com/mychidarko) or [@leafphp](https://twitter.com/leafphp) on twitter. We will coordinate the fix and eventually commit the solution in this project.

### Code contributors

<table>
	<tr>
		<td align="center">
			<a href="https://github.com/mychidarko">
				<img src="https://avatars.githubusercontent.com/u/26604242?v=4" width="120px" alt=""/>
				<br />
				<sub>
					<b>Michael Darko</b>
				</sub>
			</a>
		</td>
	</tr>
</table>

## 🤩 Sponsoring Leaf

Your cash contributions go a long way to help us make Leaf even better for you. You can sponsor Leaf and any of our packages on [open collective](https://opencollective.com/leaf) or check the [contribution page](https://leafphp.dev/support/) for a list of ways to contribute.

And to all our existing cash/code contributors, we love you all ❤️

View the [sponsors](https://leafphp.dev/support/) page to see all our sponsors.

## 🤯 Links/Projects

-   [Leaf Docs](https://leafphp.dev)
-   [Skeleton Docs](https://skeleton.leafphp.dev)
-   [Leaf CLI Docs](https://cli.leafphp.dev)
-   [Aloe CLI Docs](https://leafphp.dev/aloe-cli/)
