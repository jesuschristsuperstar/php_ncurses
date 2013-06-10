<?php
$classpath =  __DIR__."../../classes/"; 
require_once($classpath."ncurses.base.class.php");
require_once($classpath."ncurses.confirm.class.php");

/**/
// ********** Example: Confirm box 
$dialog = new ncurses_confirm();
$dialog->set("title_page_header", "PHP ncurses Confirm Box Example v.1.0.0 - Jesus Christ Superstar ");
$dialog->mode("confirm","7x35");
$dialog->set("text","\nContinue?");
$dialog->set("title_dialog_header", "");
// Show dialog box & get user input
$result = $dialog->stroke();
// Properly cleanup the screen
$dialog->destroy();

// Show what results we got back
print"Return value:\n";
var_dump($result);
/**/