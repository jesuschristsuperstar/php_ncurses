<?php
$classpath =  __DIR__."../../classes/"; 
require_once($classpath."ncurses.base.class.php");
require_once($classpath."ncurses.menu.class.php");


// ********** Example: Menu
/**/
$dialog = new ncurses_menu();

// Set a top title bar
$dialog->set("title_page_header", "PHP ncurses Menu Example v.1.0.0 - Jesus Christ Superstar ");

$dialog->mode("menu","18x62");
$dialog->set("text","\nThis is a menu test.\nPearl Jam is still good.\n");
$dialog->set("title_dialog_header", " Main Menu ");
$dialog->addMenu("Standard","S", "Begin standard installation",1);
$dialog->addMenu("Express","E", "Express installation",2);
$dialog->addMenu("Keymap","K", "Define Keymaps",3);
$dialog->addMenu("Options","O", "View/Set Options",4);
$dialog->addMenu("Run","R", "Start the process",5);
// Show dialog box & get user input
$result = $dialog->stroke();
// Properly cleanup the screen
$dialog->destroy();
// Show what results we got back
print"Return value:\n";

var_dump($result);
/**/