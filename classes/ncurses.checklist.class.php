<?php

class ncurses_checklist extends ncurses_base {
    
    // PUBLIC
    function _checklist(){
        // open a dialog box window
        $cord = $this->_getCoordinates($this->height,$this->width,"center","middle");
        $win = $this->_createDialogWindow($cord['y'], $cord['x'], $this->height, $this->width, true);

        // output dialog text
        $para_offset_y = $this->_stroke_para($win,$this->text,$this->height,$this->width,"center",true);

        // Create menu sub-window
        $mwin = $this->_createMenuSubWindow($win,$this->height, $this->width, $para_offset_y);

        // configure buttons
        $ok_offset_x_yes = round( ($this->width - 19) / 2 );
        $ok_offset_x_no = $ok_offset_x_yes + 9;		
        $this->_addbutton("[ OK ]","O",true,$this->height-2,$ok_offset_x_yes);
        $this->_addbutton("[ Cancel ]","C",false,$this->height-2,$ok_offset_x_no);

        // wait for input
        do
                {
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