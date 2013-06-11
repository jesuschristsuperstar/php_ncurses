php_ncurses
===========

This library is a rework of J Randolph Smith's 2007 nDialog class, found 
<a href="http://www.phpclasses.org/package/3654-PHP-Display-dialog-windows-in-text-consoles.html">here</a>
at the time of writing.

In spite of the limitations of Randolph's script, it was the best available php
ncurses widget library on the internet in June 2013. His original nDialog class is a single class written 
for php 4 - this class extracts each widget into a class on its own, adds some significant 
widget configuration features, and is written for > PHP 5. 

All widgets have been tested successfully under PHP 5.4.15 on Ubuntu 10.04, using the php ncurses 
install method noted below.

After spending a little time on improvements I thought I'd save some one else the time of having to do so. Please
freely contribute any feedback, suggestions, or improvements.


###Changes###

The version here has abstracted the 6 original widgets into separate classes for easier editing and management.
Errors in the input box widget were fixed.
Multiple, custom fields are now supported in input box widget.
Dialog borders were fixed.
Submenu borders are now optional and are now asterisks when enabled.
Multiple, custom buttons are now supported for each widget.



###Current Widgets:###

- checklist   [one column of checkboxes]
- confirm     [a basic yes/no prompt]
- inputbox    [supports multiple fields] 
- menu        [a select box with up to two columns of descriptive information]
- messagebox  [transition screen on a timer]
- notice      [an alert with an "ok" button]

Examples included. Fully operational. 


Prerequisite: You'll need to install the ncurses extension for PHP.

###To install php ncurses on Ubuntu 10.04 / PHP-CLI###

    apt-get install php5-dev ncurses-dev libncursesw5-dev
    pecl install ncurses
    nano /etc/php5/cli/php.ini  # paste "extension=ncurses.so" somewhere