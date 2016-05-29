<?php
use Ncurses\NcursesChecklist;

require_once(__DIR__ . '/../vendor/autoload.php');

// ********** Example: Checklist
$dialog = new NcursesChecklist();

// Set a top title bar
$dialog->setWindowDimensions(18, 52);
$dialog->setTitlePageHeader('PHP ncurses Checklist Example v.1.0.0 - Jesus Christ Superstar ');
$dialog->setText("This is a checkbox selection.\nWe are testing the checkboxes.");
$dialog->setTitleDialogHeader(' Food List ');

$dialog->addChecklist("field-a", "Apples", "A", "Red & Green Apples", 1, true);
$dialog->addChecklist("field-b", "Meats", "M", "Beef, Pork", 2, false);
$dialog->addChecklist("field-c", "Seafood", "f", "Fish, Shrimp", 3, true);
$dialog->addChecklist("field-d", "Vegi", "V", "Garden Greens", 4, true);
$dialog->addChecklist("field-e", "Grains", "G", "Flour, Nuts", 5, false);

// Show dialog box & get user input
$result = $dialog->display();

// Properly cleanup the screen
$dialog->destroy();

// Show what results we got back
print "Return value:\n";
var_dump($result);
