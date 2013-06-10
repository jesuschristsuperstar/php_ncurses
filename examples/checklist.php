<?php
$classpath =  __DIR__."../../classes/"; 
require_once($classpath."ncurses.base.class.php");
require_once($classpath."ncurses.checklist.class.php");


// ********** Example: Checklist
/**/
$dialog = new ncurses_checklist();
// Set a top title bar
$dialog->set("title_page_header", "PHP ncurses Checklist Example v.1.0.0 - Jesus Christ Superstar ");

$dialog->mode("checklist","18x52");
$dialog->set("text","This is a checkbox selection.\nWe are testing the checkboxes.");
$dialog->addChecklist("field-a","Apples","A", "Red & Green Apples",1,true);
$dialog->addChecklist("field-b","Meats","M", "Beef, Pork",2,false);
$dialog->addChecklist("field-c","Seafood","f", "Fish, Shrimp",3,true);
$dialog->addChecklist("field-d","Vegi","V", "Garden Greens",4,true);
$dialog->addChecklist("field-e","Grains","G", "Flour, Nuts",5,false);
$dialog->set("title_dialog_header", " Food List ");

// Show dialog box & get user input
$result = $dialog->stroke();

// Properly cleanup the screen
$dialog->destroy();

// Show what results we got back
print"Return value:\n";var_dump($result);
/**/