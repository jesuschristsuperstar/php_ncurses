<?php

class ncurses_inputBox extends ncurses_base {
    
    //DEFAULTS
    public $buttons =  array(
        array(
            "text"=>"Accept",
            "hotkey"=>"A",
            "return"=>"#FORM#"
        ),
        array(
            "text"=>"Cancel",
            "hotkey"=>"C",
            "return"=>false
        )
    );
    public $input_indentation = 0; //indentation of text fields from left of box      
    public $fields = array();
    

    public function _inputbox(){

        // open a dialog box window
        $cord = $this->_getCoordinates($this->height,$this->width,"center","middle");
        $win = $this->_createDialogWindow($cord['y'], $cord['x'], $this->height, $this->width, true);

        // Create menu sub-window
        // Controls alignment of menu title
        $para_offset_y = $this->_stroke_para($win,$this->text,$this->height,$this->width,"center",true);
        $cord_x = $this->input_indentation;

//ORIGINAL CODE CENTERED INPUT FIELDS.
//$para_offset_y = $this->_stroke_para($win,$this->text,$this->height,$this->width,"center",true);
//$cord_x = round(($this->width/2) - ($this->length/2) );   

        foreach($this->fields as $key=>$val){
            $cord_y =  $para_offset_y + ($key*2);
            // output dialog text
            $this->addInputBox(
                $win, 
                $this->fields[$key]['name'],
                $this->fields[$key]['label'],
                $cord_y,
                $cord_x,
                $this->fields[$key]['length'],
                $this->fields[$key]['value']
            );
        }

        // configure buttons
        $this->configure_buttons();

        // wait for input
        do
        {
            $this->_strokeAllButtons($win);

            //THIS IS WHERE THE INPUT BOXES ARE POSITIONED
            $this->_strokeInputBoxes($win);
            ncurses_wrefresh($win);

            // get keyboard input
            if( $this->_focusCat() == "B" ){
                $status = $this->_getButtonInput($win);
            }
            else{
                $status = $this->_getTextboxInput($win);
            }
        }
        while( $status === NULL );

        if(isset($status['val'])){ 
            $status = $status['val']; 
        }

        return $status;
    }
    
    
    
   
    // Defines an Input text box input type.
    public function addInputBox($win,$name,$label,$cord_y,$cord_x,$max_length,$init_val){

        $this->inputbox_list[$this->inputbox_total]['win'] = $win;	// resource window
        $this->inputbox_list[$this->inputbox_total]['name'] = $name;
        $this->inputbox_list[$this->inputbox_total]['label'] = $label;

        //DROP INPUT BOX TO ONE LINE BELOW LABEL
        $this->inputbox_list[$this->inputbox_total]['y'] = $cord_y + 1;
        $this->inputbox_list[$this->inputbox_total]['x'] = $cord_x;
        $this->inputbox_list[$this->inputbox_total]['max_length'] = $max_length;	// max allowed input size

        // set a default value - if given.
        if( $init_val != "" ){
            $len = strlen($init_val);
            for($c=0;$c<$len;$c++){
                $char = substr($init_val,$c,1);
                $this->inputbox_list[$this->inputbox_total]['val'][$c] = $char;
            }
            $this->inputbox_list[$this->inputbox_total]['val_cursor'] = $c;
            $this->inputbox_list[$this->inputbox_total]['val_len'] = $c;
        }
        else{
            $this->inputbox_list[$this->inputbox_total]['val'] = array();
            $this->inputbox_list[$this->inputbox_total]['val_cursor'] = 0;
            $this->inputbox_list[$this->inputbox_total]['val_len'] = 0;
        }

        // add to master focus list
        $this->_focus_list[$this->_focus_total] = "I-" . $this->inputbox_total;

        $this->inputbox_total++;
        $this->_focus_total++;		
    }
    
    
    // Displays all input text boxes defined
    public function _strokeInputBoxes(){
        for($i=0; $i< $this->inputbox_total; $i++){
            $this->_drawInputBox($i);
        }
    }


    // Displays specified input text box
    public function _drawInputBox($index){
        $label_offset = 0;

        $win 	= &$this->inputbox_list[$index]['win'];
        $label 	= &$this->inputbox_list[$index]['label'];
        $hotkey 	= &$this->inputbox_list[$index]['hot'];
        $cord_y 	= &$this->inputbox_list[$index]['y'];
        $cord_x 	= &$this->inputbox_list[$index]['x'];
        $max_length = &$this->inputbox_list[$index]['max_length'];

        ncurses_wcolor_set($win,2);

        // draws the label
        if($label != ""){
            ncurses_mvwaddstr( $win, $cord_y, $cord_x, $label );
            $label_offset = strlen($label); // overide offset due to label length
        }

        // draws border for input field
//$this->_drawThinBorders($win,$cord_y-1,($cord_x+$label_offset),3,$max_length+2,"out");
        //ncurses_wmove ( $win, $y+$height-1, $x+1 ); 

        // draws input box contents
        $this->_drawInputBoxContents($index);

        // draws bottom border
        ncurses_wcolor_set($win,2);
        ncurses_wmove ( $win, $cord_y + 1, $cord_x + $label_offset +1); 
        ncurses_whline ( $win, 175, 20 ); // bottom line

        return;
    }


    // Displays specified input text box value contents
    public function _drawInputBoxContents($index){	

        $win 	= &$this->inputbox_list[$index]['win'];
        $val 	= &$this->inputbox_list[$index]['val'];
        $cord_y 	= &$this->inputbox_list[$index]['y'];
        $cord_x 	= &$this->inputbox_list[$index]['x'];
        $label 	= &$this->inputbox_list[$index]['label'];

        // label offset
        $label_offset = 0;
        if($label != ""){
            $label_offset = strlen($label); // overide offset due to label length
        }

        // output the value
        for($n=0; $n < $this->inputbox_list[$index]['max_length']; $n++){
            // Set content color based on has focus or not.
            if( ( $this->_focusCat() == "I" ) && ( $this->_focusSubindex() == $index ) )
            {
                if($n == $this->inputbox_list[$index]['val_cursor']){
                    //CURSOR COLOR - RED            
                    ncurses_wcolor_set($win,1);
                }
                else{
                    ncurses_wcolor_set($win,5);
                }
            }
            else{
                ncurses_wcolor_set($win,2);
            }

            // print chars that exist
            if(isset($val[$n])){
                $char = $val[$n];
            }
            else{
                $char = " ";
            }
            ncurses_mvwaddstr( $win, $cord_y, ($cord_x+$label_offset+1+$n), $char );
        }
    }
        
}