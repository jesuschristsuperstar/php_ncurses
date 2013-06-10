<?php
$classpath =  __DIR__."../../classes/"; 
require_once($classpath."ncurses.base.class.php");
require_once($classpath."ncurses.notice.class.php");

/**/
// ********** Example: Notice       --BORDE
$dialog = new ncurses_notice();
$dialog->set("title_page_header", "PHP ncurses Notice Example v.1.0.0 - Jesus Christ Superstar ");
$dialog->mode("notice","");
$dialog->set("text","\nA notice box is great\nfor informing people while\na proccess continues...\n");
$dialog->set("title_dialog_header", " Please Wait ");
// Show dialog box & get user input
$result = $dialog->stroke();
// keep going - do work...
sleep(5);
// Properly cleanup the screen
$dialog->destroy();
/**/
