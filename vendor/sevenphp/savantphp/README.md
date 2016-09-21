SavantPHP - The Simplest Templating System For PHP minimalist
=============================================================

SavantPHP is a simple and minimalistic, yet object-oriented, template system for PHP. It aims at ONE thing, being a simple & lightweight TEMPLATING system. We focus only on that ONE thing. PHP can do everything, so do everything with it, just use SavantPHP for only separating the "view/templating" concern from your business logic, that's it.

It has a proper **namespace** support and installable nicely via **composer** (unlike it's previous oldish one - see Credit section below)

Unlike other template systems, SavantPHP does not compile your templates into PHP; instead, it uses PHP itself as its template language so you don't need to learn a new markup system and you can use and access any method or functions within your app inside those template.

It is not a competitor to any of the likes of Dwoo or Twig..etc. SavantPHP is for those PHP/templating minimalists - **"I just want to separate my front layer(view) from the back layer(code logic)" in the most simple and effective way and that's it, nothing more, nothing less, nothing else (no added crap).**

## How To Install SavantPHP

```php
$ composer require sevenphp/savantphp
```

### Why Use SavantPHP for Templates?


- Has **namespace** support
- Is installable via **composer**
- Is not jammed with lots of wrappers, inbuilt compiling..etc like others
- The code footprint is small and you can easily follow through the Classes and get the hang of it quickly
- You don't need to learn a new language or markup to create a template. The template language is PHP, and the template file is a regular PHP file.
- Because your template script is a regular PHP script, you can sprinkle it with comments and use phpDocumentor to document it.
- No need to assign variable to an array before you can use it inside your template. **The variable usage is direct** and simple. (see example code below)

Example:

```php
/* file.php */

use SavantPHP\SavantPHP;
$yourConfigBag = [
    \SavantPHP\SavantPHP::TPL_PATH_LIST => ['/path/to/yourViews/', '/path/to/someOtherFolder/anotherViewFolder/'], //as you can see, set all possible places where your template will reside
    \SavantPHP\SavantPHP::CONTAINER     => $yourContainer //can be anything, e.g a pimple container
];
$tpl = new SavantPHP($yourConfigBag);
$tpl->mynameis = 'Wasseem';

$tpl->setTemplate('file.tpl.php');
$tpl->display(); //or $response = $tpl->getOutput();

```

```php
/* file.tpl.php | See how other **master templates** are also included within a template */

<html>
<head></head>
<body>
    <p>Hello, my name is <?php if(isset($this->mynameis)) echo $this->escape($this->mynameis) ?></p>

    <?php echo $this->includeTemplate('master/sidebar.tpl.php')?>

<?php echo $this->includeTemplate('master/footer.tpl.php')?>

```

Voila! Simple huh?

### Attention

- It's your responsibility to filter and sanitize your input. The aim with **SavantPHP** is only to act as a templating system, the rest is upon you!
- But I will explain in the incoming documentation / blog post of how you can filter/sanitize your output inside a template file when not possible directly within your code logic.

### CHANGELOG

- Please see [CHANGELOG](CHANGELOG.md) for details.

### NOTE

- I have removed the Filter & Plugin System. If you need them, see version tagged v1.0.0
- see [CHANGELOG](CHANGELOG.md) for all changes, as SavantPHP is NOT backward compatible with the old savant php.

### CREDIT

- This project has been revamped/retouched by me, Khayrattee, on Dec 2015. I'm a die-hard fan of the philosophy of Savant. Thus I took the initiative to fork the old Savant & rework it to keep the spirit of Savant which was **started & created by The Mighty 'Paul M. Jones' who is the author of [Aura PHP](https://github.com/auraphp).**
- This project was handed over to Brett Bieber. While I'm sad he is not modernizing the old savant and keeping it abreast, I do understand he might have other priorities and commitments. Anyway, the new SavantPHP is here!
- This project is hence inspired from [https://github.com/saltybeagle/Savant3]


### The Future

- I have been a big fan and user of the previous Savant PHP. I decided to keep it alive and I'm still using it.
- I use SavantPHP on a frequent basis and my pet projects fully use it - so I'm going to support it however I can (grateful if **YOU** contribute too) so that it can be used with the latest tools available & latest PHP version available. This would be very helpful to many of people out there who like me & YOU are inline with the aim above. So do this! cheers.
- SavantPHP still makes very much sense using as it honors the KISS principle. Consider this SavantPHP Reloaded!

### Your Contribution

- Please help in testing and improving it.
- Send your pull request on github.
- And report any bugs either via email (savantphp@7php.com) or via github issues.
(thanks guys!)


### TODO
- Add Tests suite
- See if any further refactoring or performance tweak is needed
