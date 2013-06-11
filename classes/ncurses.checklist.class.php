<?php

class ncurses_checklist extends ncurses_base {
    
    //DEFAULT
    public $buttons = array(
        array(
            "text"=>"OK",
            "hotkey"=>"O",
            "return"=>true
        ),
        array(
            "text"=>"Cancel",
            "hotkey"=>"C",
            "return"=>false
        )
    );
    
    
    // PUBLIC
    function _checklist(){
        // open a dialog box window
        $cord = $this->_getCoordinates($this->height,$this->width,"center","middle");
        $win = $this->_createDialogWindow($cord['y'], $cord['x'], $this->height, $this->width, true);

        // output dialog text
        $para_offset_y = $this->_stroke_para($win,$this->text,$this->height,$this->width,"center",true);

        // Create menu sub-window
        $mwin = $this->_createMenuSubWindow($win,$this->height, $this->width, $para_offset_y);

        $this->configure_buttons();

        // wait for input
        do{
            $this->_strokeAllButtons($win);
            $this->_strokeAllMenuItems($mwin);
            ncurses_wrefresh($win);
            ncurses_wrefresh($mwin);
            // get keyboard input			
            $status = $this->_getMenuInput($win);			
        }
        while( $status === NULL );

        return($status);
    }
    

    // Wrapper method to initialize Checkbox items
    function addChecklist($name,$label,$hotkey,$desc,$value=NULL,$selected=false){
        $this->addMenuItem("check",$name,$label,$hotkey,$desc,$value,$selected);
    }
}