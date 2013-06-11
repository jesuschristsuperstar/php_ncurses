<?php

class ncurses_confirm extends ncurses_base {
    
    //DEFAULTS
    public $buttons = array(
        array(
            "text"=>"Yes",
            "hotkey"=>"Y",
            "return"=>true
        ),
        array(
            "text"=>"No",
            "hotkey"=>"N",
            "return"=>false
        )
    );

    public function _confirm(){
        
        // open a dialog box window
        $cord = $this->_getCoordinates($this->height,$this->width,"center","middle");
        $win = $this->_createDialogWindow($cord['y'], $cord['x'], $this->height, $this->width, true);

        // output dialog text
        $this->_stroke_para($win,$this->text,$this->height,$this->width,"center",true);

        // configure buttons
        $this->configure_buttons();

        // wait for input
        do{
            $this->_strokeAllButtons($win);
            ncurses_wrefresh($win);
            // get keyboard input			
            $status = $this->_getButtonInput($win);			
        }
        while( $status === NULL );

        return($status);
    }
    
}