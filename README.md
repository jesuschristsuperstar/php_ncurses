# php_ncurses

Ncurses based terminal ui library for PHP applications. Works with PHP 5.4 or newer. 

### Current Widgets

- checklist   [one column of checkboxes]
- confirm     [a basic yes/no prompt]
- inputbox    [supports multiple fields] 
- menu        [a select box with up to two columns of descriptive information]
- messagebox  [transition screen on a timer]
- notice      [an alert with an "ok" button]

Examples included. Fully operational. 


### Prerequisites

- You'll need to install PHP-CLI 5.4+
- You'll need to install <a href="http://php.net/manual/en/ncurses.installation.php">the ncurses extension for PHP</a>.
- It seems PHP7 doesn't support the ncurses extension at this time. [See here](https://groups.google.com/forum/#!topic/comp.lang.php/1EqPfC0_NGQ). Funny that I found this out after updating this repo after 4 years. Typical. 

To see if you have the ncurses extension installed:

```
$ php -m
```

If not listed, you'll need to:

```
pecl install ncurses
```

### Composer
`composer require jesuschristsuperstar/php_ncurses`

## License

GNU General Public License v3.0

Copyright (c) 2010 - 2017 jesuschristsuperstar
