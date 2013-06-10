<?php

class ncurses_notice extends ncurses_base {
    
    function _notice(){
        if( ($this->height == 0) || ($this->height == 0) )
                {
                // auto-detect required height & width based on text
                $this->width = $this->height = 0;		
                $text = str_replace ( "\n", "|", $this->text ); // decode any linebreaks		
                $lines = explode("|",$text);		
                foreach($lines as $line)
                        {
                        $len = strlen($line);			
                        if($len > $this->width)
                                { $this->width = $len; }
                        $this->height++;
                        }
                }

        // open a dialog box window
        $cord = $this->_getCoordinates($this->height,$this->width,"center","middle");
        $this->nwin = $this->_createDialogWindow($cord['y'], $cord['x'], $this->height+2, $this->width+2, true, 2);

        $this->_stroke_para($this->nwin,$this->text,$this->height,$this->width+2,"center",false);		
        ncurses_wrefresh($this->nwin);
    }
}