<?php
use NcursesWidget\NcursesMenu;

require_once(__DIR__ . '/../vendor/autoload.php');

// ********** Example: Menu
$dialog = new NcursesMenu();

// Set a top title bar
$dialog->setWindowDimensions(18, 62);
$dialog->setTitlePageHeader('PHP ncurses Menu Example v.1.0.0 - Jesus Christ Superstar ');
$dialog->setText("\nThis is a menu test.\nPearl Jam is still good.\n");
$dialog->setTitleDialogHeader(' Main Menu ');

$dialog->addMenu('Standard', 'S', 'Begin standard installation', 1);
$dialog->addMenu('Express', 'E', 'Express installation', 2);
$dialog->addMenu('Keymap', 'K', 'Define Keymaps', 3);
$dialog->addMenu('Options', 'O', 'View/Set Options', 4);
$dialog->addMenu('Run', 'R', 'Start the process', 5);

// Show dialog box & get user input
$result = $dialog->display();
// Properly cleanup the screen
$dialog->destroy();
// Show what results we got back
print"Return value:\n";

var_dump($result);
