<?php
use Ncurses\NcursesNotice;

$classpath = __DIR__ . '/../src/Ncurses/';
require_once($classpath . 'NcursesBase.php');
require_once($classpath . 'NcursesNotice.php');

// ********** Example: Notice       --BORDE
$dialog = new NcursesNotice();
$dialog->mode('notice', '');
$dialog->setTitlePageHeader('PHP ncurses Notice Example v.1.0.0 - Jesus Christ Superstar ');
$dialog->setText("\nA notice box is great\nfor informing people while\na proccess continues...\n");
$dialog->setTitleDialogHeader(' Please Wait ');

// Show dialog box & get user input
$result = $dialog->stroke();
sleep(5); // keep going - do work...
$dialog->destroy(); // Properly cleanup the screen
