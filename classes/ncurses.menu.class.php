<?php

class ncurses_menu extends ncurses_base {
    
    //DEFAULTS
    public $buttons = array(
        array(
            "text"=>"Select",
            "hotkey"=>"S",
            "return"=>true
        ),
        array(
            "text"=>"Cancel",
            "hotkey"=>"C",
            "return"=>false
        )
    );
    public $border = 0; //1 to show border
    
    // PUBLIC
    function _menu(){
        
        // open a dialog box window
        $cord = $this->_getCoordinates($this->height,$this->width,"center","middle");
        $win = $this->_createDialogWindow($cord['y'], $cord['x'], $this->height, $this->width, true);

        // output dialog text
        $para_offset_y = $this->_stroke_para($win,$this->text,$this->height,$this->width,"center",true);

        // Create menu sub-window
        $mwin = $this->_createMenuSubWindow($win,$this->height,$this->width, $para_offset_y,$this->border);

        // configure buttons
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
        while( $status === null );

        return($status);
    }
    
    // Wrapper method to initialize Menu items
    function addMenu($label,$hotkey,$desc,$value=NULL){
        $name = "";
        $this->addMenuItem("menu",$name,$label,$hotkey,$desc,$value,false);
    }
}