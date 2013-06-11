<?php

class ncurses_msgbox extends ncurses_base {
    
    //DEFAULTS
    public $buttons = array(
        array(
            "text"=>"OK",
            "hotkey"=>"O",
            "return"=>true
        )
    );
    
    public function _messagebox(){
		
        // open a dialog box window
        $cord = $this->_getCoordinates($this->height,$this->width,"center","middle");
        $win = $this->_createDialogWindow($cord['y'], $cord['x'], $this->height, $this->width, true);

        // output dialog text
        $this->_stroke_para($win,$this->text,$this->height,$this->width,"center",true);

        // ok button
        $this->configure_buttons();

        // wait for input
        do
        {
            $this->_strokeAllButtons($win);
            ncurses_wrefresh($win);
            // get keyboard input			
            $status = $this->_getButtonInput($win);			
        }
        while( $status === NULL );

        return(true);
    }
}