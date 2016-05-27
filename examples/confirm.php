<?php

use Ncurses\NcursesConfirm;

$classpath = __DIR__ . '/../src/Ncurses/';
require_once($classpath . 'NcursesBase.php');
require_once($classpath . 'NcursesConfirm.php');

// ********** Example: Confirm box
$dialog = new NcursesConfirm();
$dialog->mode('confirm', '7x35');
$dialog->setTitlePageHeader('PHP ncurses Confirm Box Example v.1.0.0 - Jesus Christ Superstar ');
$dialog->setText("\nContinue?");
$dialog->setTitleDialogHeader('');
// Show dialog box & get user input
$result = $dialog->stroke();
// Properly cleanup the screen
$dialog->destroy();

// Show what results we got back
print "Return value:\n";
var_dump($result);
