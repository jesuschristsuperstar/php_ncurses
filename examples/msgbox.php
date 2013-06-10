<?php
$classpath =  __DIR__."../../classes/"; 
require_once($classpath."ncurses.base.class.php");
require_once($classpath."ncurses.msgbox.class.php");

/**/
// ********** Example: Message Dialog
$dialog = new ncurses_msgbox();
$dialog->set("title_page_header", "PHP ncurses Message Box Example v.1.0.0 - Jesus Christ Superstar ");
$dialog->mode("msgbox","8x40");
$dialog->set("text","This is a simple message box. Please press <enter> to continue.");
// Show dialog box & get user input
$result = $dialog->stroke();
// Properly cleanup the screen
$dialog->destroy();

// Show what results we got back
print "Return value:\n";
var_dump($result);
/**/



