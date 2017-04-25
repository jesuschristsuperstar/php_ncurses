# PHP Ncurses Widget Library

Ncurses based terminal ui library for PHP applications. Works with PHP 5.4 or newer. 

### Widget List

- Checklist   [one column of checkboxes]
---
![php_nurses_checklist](https://cloud.githubusercontent.com/assets/4656976/25381950/8a3c1480-296a-11e7-839a-08ee20d1cd29.png)

- Confirm     [a basic yes/no prompt]
---
![php_nurses_confirm](https://cloud.githubusercontent.com/assets/4656976/25381949/8a36eafa-296a-11e7-9392-11e133d483e3.png)

- Inputbox    [supports multiple fields] 
---
![php_nurses_input_box](https://cloud.githubusercontent.com/assets/4656976/25381948/8a364000-296a-11e7-8e09-6149b8b23142.png)

- Menu        [a select box with up to two columns of descriptive information]
---
![php_nurses_menu](https://cloud.githubusercontent.com/assets/4656976/25381953/8c3e87a4-296a-11e7-87e7-fe3d9297ea70.png)

- Messagebox  [transition screen on a timer]
---
![php_nurses_msgbox](https://cloud.githubusercontent.com/assets/4656976/25381954/8c3f1106-296a-11e7-887c-0ad3ce317d72.png)

- Notice      [an alert with an "ok" button]
---
![php_nurses_notice](https://cloud.githubusercontent.com/assets/4656976/25382301/d3cc6248-296b-11e7-8895-cc7043e6f724.png)

### Prerequisites

- You'll need to install PHP-CLI 5.4+
- You'll need to install <a href="http://php.net/manual/en/ncurses.installation.php">the ncurses extension for PHP</a>.
- Please note that it PHP7+ doesn't support the ncurses extension at this time. [See here](https://groups.google.com/forum/#!topic/comp.lang.php/1EqPfC0_NGQ). 

To see if you have the ncurses extension installed:

```
$ php -m
```

If not listed, you'll need to:

```
pecl install ncurses
```

### Composer

- I didn't upload this to Packagist, because you can install directly from Github by adding the following to your composer.json:

```
"require": {
    "jesuschristsuperstar/php_ncurses": "*"
  },
  "repositories": [ 
    {
      "url":"https://github.com/jesuschristsuperstar/php_ncurses.git",
      "type":"git"
    }
  ]
```

## License

GNU General Public License v3.0

Copyright (c) 2010 - 2017 jesuschristsuperstar
