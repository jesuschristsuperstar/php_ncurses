<?php
$classpath =  __DIR__."../../classes/"; 
require_once($classpath."ncurses.base.class.php");
require_once($classpath."ncurses.inputBox.class.php");


/**/
// ********** Example: Inputbox

$dialog = new ncurses_inputBox();

// Set a top title bar
$dialog->set("title_page_header", "PHP ncurses Input Box Example v.1.0.0 - Jesus Christ Superstar ");

$dialog->mode("inputbox","20x60");
$dialog->set("title_dialog_header", " Customer Profile ");

$dialog->set("text","\nInput Box Title"); //title
$dialog->input_indentation = 4;

$fields = array(
    array(
        "label"=>"First Name",
        "name"=>"first_name",
        "value"=>"",
        "length"=>20
    ),
    array(
        "label"=>"Last Name",
        "name"=>"last_name",
        "value"=>"",
        "length"=>20
    )
);
$dialog->set("fields",$fields);

// Show dialog box & get user input
$result = $dialog->stroke();

// Properly cleanup the screen
$dialog->destroy();

// Show what results we got back
print"Return value:\n";var_dump($result);
 
/**/