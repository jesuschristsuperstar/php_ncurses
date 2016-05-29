<?php
use Ncurses\NcursesMsgbox;

require_once(__DIR__ . '/../vendor/autoload.php');

// ********** Example: Message Dialog
$dialog = new NcursesMsgbox();
$dialog->setWindowDimensions(8, 40);
$dialog->setTitlePageHeader('PHP ncurses Message Box Example v.1.0.0 - Jesus Christ Superstar ');
$dialog->setText("This is a simple message box. Please press <enter> to continue.");

// Show dialog box & get user input
$result = $dialog->display();
// Properly cleanup the screen
$dialog->destroy();

// Show what results we got back
print "Return value:\n";
var_dump($result);



