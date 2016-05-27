<?php
namespace Ncurses;

/**
 * Class NcursesInputBox
 * @package Ncurses
 */
class NcursesInputBox extends NcursesBase
{
    /**
     * @var array
     */
    protected $buttons = [
        [
            'text' => 'Accept',
            'hotkey' => 'A',
            'return' => '#FORM#'
        ],
        [
            'text' => 'Cancel',
            'hotkey' => 'C',
            'return' => false
        ]
    ];

    /**
     * indentation of text fields from left of box
     * @var int
     */
    protected $inputIndentation = 0;

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @return array
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * @param array $buttons
     * @return $this
     */
    public function setButtons($buttons)
    {
        $this->buttons = $buttons;

        return $this;
    }

    /**
     * @return int
     */
    public function getInputIndentation()
    {
        return $this->inputIndentation;
    }

    /**
     * @param int $inputIndentation
     * @return $this
     */
    public function setInputIndentation($inputIndentation)
    {
        $this->inputIndentation = $inputIndentation;

        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function inputbox()
    {
        // open a dialog box window
        $cord = $this->getCoordinates($this->height, $this->width, "center", "middle");
        $win = $this->createDialogWindow($cord['y'], $cord['x'], $this->height, $this->width, true);

        // Create menu sub-window
        // Controls alignment of menu title
        $para_offset_y = $this->strokePara($win, $this->text, $this->height, $this->width, "center", true);
        $cord_x = $this->inputIndentation;

        //ORIGINAL CODE CENTERED INPUT FIELDS.
        //$para_offset_y = $this->_stroke_para($win,$this->text,$this->height,$this->width,"center",true);
        //$cord_x = round(($this->width/2) - ($this->length/2) );

        foreach ($this->fields as $key => $val) {
            $cord_y = $para_offset_y + ($key * 2);
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
        $this->configureButtons();

        // wait for input
        do {
            $this->strokeAllButtons($win);

            //THIS IS WHERE THE INPUT BOXES ARE POSITIONED
            $this->strokeInputBoxes($win);
            ncurses_wrefresh($win);

            // get keyboard input
            if ($this->focusCat() === 'B') {
                $status = $this->getButtonInput($win);
            } else {
                $status = $this->getTextboxInput($win);
            }
        } while ($status === null);

        if (isset($status['val'])) {
            $status = $status['val'];
        }

        return $status;
    }

    /**
     * Defines an Input text box input type.
     * @param $win
     * @param $name
     * @param $label
     * @param $cord_y
     * @param $cord_x
     * @param $max_length
     * @param $init_val
     */
    protected function addInputBox($win, $name, $label, $cord_y, $cord_x, $max_length, $init_val)
    {
        $this->inputboxList[$this->inputboxTotal]['win'] = $win;    // resource window
        $this->inputboxList[$this->inputboxTotal]['name'] = $name;
        $this->inputboxList[$this->inputboxTotal]['label'] = $label;

        //DROP INPUT BOX TO ONE LINE BELOW LABEL
        $this->inputboxList[$this->inputboxTotal]['y'] = $cord_y + 1;
        $this->inputboxList[$this->inputboxTotal]['x'] = $cord_x;
        $this->inputboxList[$this->inputboxTotal]['max_length'] = $max_length;    // max allowed input size

        // set a default value - if given.
        if ($init_val != "") {
            $len = strlen($init_val);
            for ($c = 0; $c < $len; $c++) {
                $char = substr($init_val, $c, 1);
                $this->inputboxList[$this->inputboxTotal]['val'][$c] = $char;
            }
            $this->inputboxList[$this->inputboxTotal]['val_cursor'] = $c;
            $this->inputboxList[$this->inputboxTotal]['val_len'] = $c;
        } else {
            $this->inputboxList[$this->inputboxTotal]['val'] = array();
            $this->inputboxList[$this->inputboxTotal]['val_cursor'] = 0;
            $this->inputboxList[$this->inputboxTotal]['val_len'] = 0;
        }

        // add to master focus list
        $this->focusList[$this->focusTotal] = 'I-' . $this->inputboxTotal;

        $this->inputboxTotal++;
        $this->focusTotal++;
    }

    /**
     * Displays all input text boxes defined
     */
    protected function strokeInputBoxes()
    {
        for ($i = 0; $i < $this->inputboxTotal; $i++) {
            $this->drawInputBox($i);
        }
    }

    /**
     * Displays specified input text box
     * @param $index
     */
    protected function drawInputBox($index)
    {
        $label_offset = 0;

        $win = &$this->inputboxList[$index]['win'];
        $label = &$this->inputboxList[$index]['label'];
        $hotkey = &$this->inputboxList[$index]['hot'];
        $cord_y = &$this->inputboxList[$index]['y'];
        $cord_x = &$this->inputboxList[$index]['x'];
        $max_length = &$this->inputboxList[$index]['max_length'];

        ncurses_wcolor_set($win, 2);

        // draws the label
        if ($label != "") {
            ncurses_mvwaddstr($win, $cord_y, $cord_x, $label);
            $label_offset = strlen($label); // overide offset due to label length
        }

        // draws border for input field
        //$this->_drawThinBorders($win,$cord_y-1,($cord_x+$label_offset),3,$max_length+2,"out");
        //ncurses_wmove ( $win, $y+$height-1, $x+1 );

        // draws input box contents
        $this->drawInputBoxContents($index);

        // draws bottom border
        ncurses_wcolor_set($win, 2);
        ncurses_wmove($win, $cord_y + 1, $cord_x + $label_offset + 1);
        ncurses_whline($win, 175, 20); // bottom line
    }

    /**
     * Displays specified input text box value contents
     * @param $index
     */
    protected function drawInputBoxContents($index)
    {
        $win = &$this->inputboxList[$index]['win'];
        $val = &$this->inputboxList[$index]['val'];
        $cord_y = &$this->inputboxList[$index]['y'];
        $cord_x = &$this->inputboxList[$index]['x'];
        $label = &$this->inputboxList[$index]['label'];

        // label offset
        $label_offset = 0;
        if ($label !== '') {
            $label_offset = strlen($label); // overide offset due to label length
        }

        // output the value
        for ($n = 0; $n < $this->inputboxList[$index]['max_length']; $n++) {
            // Set content color based on has focus or not.
            if (($this->focusCat() === 'I') && ($this->focusSubindex() == $index)) {
                if ($n == $this->inputboxList[$index]['val_cursor']) {
                    //CURSOR COLOR - RED
                    ncurses_wcolor_set($win, 1);
                } else {
                    ncurses_wcolor_set($win, 5);
                }
            } else {
                ncurses_wcolor_set($win, 2);
            }

            // print chars that exist
            if (isset($val[$n])) {
                $char = $val[$n];
            } else {
                $char = ' ';
            }
            ncurses_mvwaddstr($win, $cord_y, ($cord_x + $label_offset + 1 + $n), $char);
        }
    }
}
