<?php

class ncurses_confirm extends ncurses_base {
    

    public function _confirm(){
        
        // open a dialog box window
        $cord = $this->_getCoordinates($this->height,$this->width,"center","middle");
        $win = $this->_createDialogWindow($cord['y'], $cord['x'], $this->height, $this->width, true);

        // output dialog text
        $this->_stroke_para($win,$this->text,$this->height,$this->width,"center",true);

        // configure buttons
        $ok_offset_x_yes = round( ($this->width - 16) / 2 );
        $ok_offset_x_no = $ok_offset_x_yes + 10;		
        $this->_addbutton("[ Yes ]","Y",TRUE,$this->height-2,$ok_offset_x_yes);
        $this->_addbutton("[ No ]","N",FALSE,$this->height-2,$ok_offset_x_no);

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