<?php
use NcursesWidget\NcursesConfirm;

require_once(__DIR__ . '/../vendor/autoload.php');

// ********** Example: Confirm box
$dialog = new NcursesConfirm();
$dialog->setWindowDimensions(7, 35);
$dialog->setTitlePageHeader('PHP ncurses Confirm Box Example v.1.0.0 - Jesus Christ Superstar ');
$dialog->setText("\nContinue?");
$dialog->setTitleDialogHeader('');
// Show dialog box & get user input
$result = $dialog->display();
// Properly cleanup the screen
$dialog->destroy();

// Show what results we got back
print "Return value:\n";
var_dump($result);
