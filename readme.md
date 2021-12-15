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

### Cash contributors

<table>
	<tr>
		<td align="center">
			<a href="https://opencollective.com/aaron-smith3">
				<img src="https://images.opencollective.com/aaron-smith3/08ee620/avatar/256.png" width="120px" alt=""/>
				<br />
				<sub><b>Aaron Smith</b></sub>
			</a>
		</td>
		<td align="center">
			<a href="https://opencollective.com/peter-bogner">
				<img src="https://images.opencollective.com/peter-bogner/avatar/256.png" width="120px" alt=""/>
				<br />
				<sub><b>Peter Bogner</b></sub>
			</a>
		</td>
		<td align="center">
			<a href="#">
				<img src="https://images.opencollective.com/guest-32634fda/avatar.png" width="120px" alt=""/>
				<br />
				<sub><b>Vano</b></sub>
			</a>
		</td>
	</tr>
</table>

## 🤯 Links/Projects

- [Leaf Docs](https://leafphp.dev)
- [Skeleton Docs](https://skeleton.leafphp.dev)
- [Leaf CLI Docs](https://cli.leafphp.dev)
- [Aloe CLI Docs](https://leafphp.dev/aloe-cli/)
