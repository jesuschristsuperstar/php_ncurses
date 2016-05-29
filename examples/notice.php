<?php
use NcursesWidget\NcursesNotice;

require_once(__DIR__ . '/../vendor/autoload.php');

// ********** Example: Notice
$dialog = new NcursesNotice();
$dialog->setTitlePageHeader('PHP ncurses Notice Example v.1.0.0 - Jesus Christ Superstar ');
$dialog->setTitleDialogHeader(' Please Wait ');

// Show dialog box & get user input
for ($i = 0; $i < 5; $i++) {
    $dialog->setText(
        sprintf(
            "\nA notice box is great\nfor informing people while\na proccess continues.%s\n",
            str_repeat('.', $i)
        )
    );

    $dialog->display();
    sleep(1);
}

$dialog->destroy(); // Properly cleanup the screen
