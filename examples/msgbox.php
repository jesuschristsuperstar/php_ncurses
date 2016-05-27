<?php
use Ncurses\NcursesMsgbox;

$classpath = __DIR__ . '/../src/Ncurses/';
require_once($classpath . 'NcursesBase.php');
require_once($classpath . 'NcursesMsgbox.php');

// ********** Example: Message Dialog
$dialog = new NcursesMsgbox();
$dialog->mode('msgbox', '8x40');
$dialog->setTitlePageHeader('PHP ncurses Message Box Example v.1.0.0 - Jesus Christ Superstar ');
$dialog->setText("This is a simple message box. Please press <enter> to continue.");

// Show dialog box & get user input
$result = $dialog->stroke();
// Properly cleanup the screen
$dialog->destroy();

// Show what results we got back
print "Return value:\n";
var_dump($result);



