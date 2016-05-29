<?php
use NcursesWidget\NcursesInputBox;

require_once(__DIR__ . '/../vendor/autoload.php');

// ********** Example: Inputbox
$dialog = new NcursesInputBox();

// Set a top title bar
$dialog->setWindowDimensions(20, 60);
$dialog->setTitlePageHeader('PHP ncurses Input Box Example v.1.0.0 - Jesus Christ Superstar ');
$dialog->setTitleDialogHeader(' Customer Profile ');
$dialog->setText("\nInput Box Title"); //title
$dialog->setInputIndentation(4);
$dialog->setFields([
    [
        'label' => 'First Name',
        'name' => 'first_name',
        'value' => '',
        'length' => 20
    ],
    [
        'label' => 'Last Name',
        'name' => 'last_name',
        'value' => '',
        'length' => 20
    ]
]);

// Show dialog box & get user input
$result = $dialog->display();

// Properly cleanup the screen
$dialog->destroy();

// Show what results we got back
print "Return value:\n";
var_dump($result);
