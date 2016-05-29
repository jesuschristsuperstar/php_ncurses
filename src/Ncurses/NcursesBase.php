<?php

namespace Ncurses;

    /* * ********************* NcursesBase.php  *******************************
     *   Copyright (C) 2007 by J Randolph Smith                                *
     *   johns@servangle.net                                                   *
     *                                                                         *
     *   Copyright (C) 2016 by Viacheslav Sychov                               *
     *   dev@sychov.pro                                                        *
     *                                                                         *
     *   This program is free software; you can redistribute it and/or modify  *
     *   it under the terms of the GNU General Public License as published by  *
     *   the Free Software Foundation; either version 2 of the License, or     *
     *   (at your option) any later version.                                   *
     *                                                                         *
     *   This program is distributed in the hope that it will be useful,       *
     *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
     *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
     *   GNU General Public License for more details.                          *
     *                                                                         *
     *   You should have received a copy of the GNU General Public License     *
     *   along with this program; if not, write to the                         *
     *   Free Software Foundation, Inc.,                                       *
     *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
     * ************************************************************************* */

/**
 * Class NcursesBase
 * @package Ncurses
 */
abstract class NcursesBase
{
    /**
     * @var string
     */
    protected $titlePageHeader;

    /**
     * @var string
     */
    protected $titleDialogHeader;

    /**
     * @var string
     */
    protected $mode; // tracks the dialog mode

    /**
     * @var string
     */
    protected $text;

    /**
     * @var integer
     */
    protected $height, $width;

    /**
     * @var integer
     */
    protected $length;

    /**
     * @var mixed
     */
    protected $defaultVal;

    /**
     * @var integer
     */
    protected $screenMaxWidth, $screenMaxHeight; // auto-detect current screen size

    /**
     * @var array
     */
    protected $buttonList;

    /**
     * @var integer
     */
    protected $buttonTotal;

    /**
     * @var integer
     */
    protected $buttonCursor;

    /**
     * @var array
     */
    protected $inputBoxList;

    /**
     * @var integer
     */
    protected $inputBoxTotal;

    /**
     * @var array array of menu items
     */
    protected $menuList;

    /**
     * @var integer
     */
    protected $menuTotal;

    /**
     * @var integer
     */
    protected $menuCursor;

    /**
     * @var integer
     */
    protected $menuLabelWidth;

    /**
     * @var integer
     */
    protected $menuDescWidth;

    /**
     * notice window handler
     * @var resource
     */
    protected $nwin;

    /**
     * shadow window handler
     * @var resource
     */
    protected $swin;

    /**
     * text char array
     * @var mixed
     */
    protected $textBoxChar;

    /**
     * current length of text
     * @var integer
     */
    protected $textBoxLen;

    /**
     * @var integer
     */
    protected $textBoxMax;

    /**
     * @var array
     */
    protected $focusList;

    /**
     * @var integer
     */
    protected $focusTotal;

    /**
     * @var integer
     */
    protected $focusCursor;

    /**
     * @return mixed
     */
    abstract public function display();

    /**
     * NcursesBase constructor.
     */
    public function __construct()
    {
        $this->titlePageHeader = '';
        $this->reset();
    }

    public function configureButtons()
    {
        $button_padding = '2';
        $offset = 0;
        $count = count($this->buttons);

        $combined_length = 0;

        //GET COMBINED LENGTH OF ALL BUTTONS SO CAN TIGHTLY CENTER THEM
        foreach ($this->buttons as $key => $val) {
            //ENCLOSE BUTTONS WITH BRACKETS AND ADD TRAILING SPACE(S)
            $this->buttons[$key]['text'] = '[ ' . $val['text'] . ' ]';
            $combined_length += strlen($this->buttons[$key]['text']);
        }

        $left = round($this->width / 2) - round($combined_length / 2);

        foreach ($this->buttons as $key => $val) {

            $val['hotkey'] = (!isset($val['hotkey'])) ? '' : $val['hotkey'];

            //EQUIDISTANT CENTER EACH BUTTON
            $this->addButton($val['text'], $val['hotkey'], $val['return'], $this->height - 2, $left);
            $left = $left + strlen($val['text']) + 2; //+2 to leave 2 spaces between buttons
        }
    }

    /**
     * @param $height
     * @param $width
     */
    public function setWindowDimensions($height, $width)
    {
        $this->reset();

        // set hieght/width
        $this->setHeight((int) $height);
        $this->setWidth((int) $width);
    }

    protected function reset()
    {
        $this->test = '';
        $this->size = '';
        $this->height = 0;
        $this->width = 0;

        $this->buttonList = [];
        $this->buttonTotal = 0;
        $this->buttonCursor = 0;

        $this->inputBoxList = [];
        $this->inputBoxTotal = 0;

        $this->menuList = [];
        $this->menuTotal = 0;
        $this->menuCursor = 0;
        $this->menuLabelWidth = 0;
        $this->menuDescWidth = 0;
        $this->nwin = $this->swin = null;
        $this->textBoxChar = null;
        $this->textBoxLen = $textbox_max = 0;

        $this->focusList = [];
        $this->focusTotal = 0;
        $this->focusCursor = 0;
    }

    public function destroy()
    {
        ncurses_clear();
        ncurses_echo();
        ncurses_refresh();
        ncurses_end();
    }

    protected function initScreen()
    {
        ncurses_curs_set(0);
        ncurses_noecho();
        $fullscreen = ncurses_newwin(0, 0, 0, 0);
        ncurses_getmaxyx($fullscreen, $this->screenMaxHeight, $this->screenMaxWidth);
        ncurses_delwin($fullscreen);

        //COLOR SCHEMES
        ncurses_start_color();
        // text color, background color
        /*
          COLOR_BLACK   0
          COLOR_RED     1
          COLOR_GREEN   2
          COLOR_YELLOW  3
          COLOR_BLUE    4
          COLOR_MAGENTA 5
          COLOR_CYAN    6
          COLOR_WHITE   7
         */
        ncurses_init_pair(1, NCURSES_COLOR_WHITE, NCURSES_COLOR_RED);
        ncurses_init_pair(2, NCURSES_COLOR_BLACK, NCURSES_COLOR_WHITE);
        ncurses_init_pair(3, NCURSES_COLOR_WHITE, NCURSES_COLOR_WHITE);
        ncurses_init_pair(4, NCURSES_COLOR_BLACK, NCURSES_COLOR_RED);
        ncurses_init_pair(5, NCURSES_COLOR_WHITE, NCURSES_COLOR_BLUE);
        ncurses_init_pair(6, NCURSES_COLOR_YELLOW, NCURSES_COLOR_BLUE);
        ncurses_init_pair(7, NCURSES_COLOR_BLUE, NCURSES_COLOR_WHITE);

        ncurses_color_set(5);
        ncurses_erase();
        ncurses_mvhline(0, 0, 0, $this->screenMaxWidth);
        ncurses_attron(NCURSES_A_BOLD);
        ncurses_mvaddstr(0, 1, $this->titlePageHeader);
        ncurses_attroff(NCURSES_A_BOLD);
        for ($y = 1; $y < $this->screenMaxHeight; $y++) {
            ncurses_mvhline($y, 0, 32, $this->screenMaxWidth);
        }
        ncurses_refresh();
    }

    protected function drawDialogBorders(&$win, $y, $x)
    {
        // Draw boarders
        //  ============
        // ||          ||
        // ||          ||
        // ||          ||
        //  ------------
        // ||          ||
        //  ============
        ncurses_wattron($win, NCURSES_A_BOLD);
        ncurses_wcolor_set($win, 3);
        ncurses_wmove($win, 0, 1);
        ncurses_whline($win, 205, $x - 2); // top line

        ncurses_wmove($win, $y - 3, 1);
        ncurses_whline($win, 0, $x - 2);  // middle line

        ncurses_wmove($win, 1, 0);
        ncurses_wvline($win, 186, $y - 2); // left line

        ncurses_wmove($win, 0, 0);
        ncurses_waddch($win, 201);   // top left

        ncurses_wmove($win, $y - 1, 0);
        ncurses_waddch($win, 200);   // bottom left

        ncurses_wmove($win, $y - 3, 0);
        ncurses_waddch($win, 199);   // middle left

        ncurses_wcolor_set($win, 2);

        //ncurses_wmove ( $win, $y-1, 1 ); 	ncurses_whline ( $win, 203, $x-2 ); // bottom line
        ncurses_wmove($win, $y - 1, 1);
        ncurses_wborder($win, 0, 0, 0, 0, 0, 0, 0, 0); // bottom line


        ncurses_wattroff($win, NCURSES_A_BOLD);
    }

    /**
     * Returns the Category of the current focus cursor
     * @return mixed
     */
    protected function focusCat()
    {
        // get current focus category
        list($cat, $index) = explode('-', $this->focusList[$this->focusCursor]);

        return $cat;
    }

    /**
     * Returns the Sub-index of the current focus cursor
     * @return mixed
     */
    protected function focusSubindex()
    {
        // get current focus index
        list($cat, $index) = explode('-', $this->focusList[$this->focusCursor]);

        return $index;
    }

    /**
     * Returns the top right x,y coordinates based on desired window size and alignment.
     * @param $height
     * @param $width
     * @param string $hoz_just
     * @param string $ver_just
     * @return array
     */
    protected function getCoordinates($height, $width, $hoz_just = "center", $ver_just = "middle")
    {
        $result = array('x' => 0, 'y' => 0);

        switch ($hoz_just) {
            case"center":
                // Calculate offsets to center window based on window size
                $result['x'] = round(($this->screenMaxWidth - $width) / 2);
                break;

            case"right":
                $result['x'] = $this->screenMaxWidth - $width;
                break;

            case"left":
            default:
                break;
        }

        switch ($ver_just) {
            case"middle":
                // Calculate offsets to center window based on window size
                $result['y'] = round(($this->screenMaxHeight - $height) / 2);
                break;

            case"bottom":
                $result['y'] = $this->screenMaxHeight - $height;
                break;

            case"top":
            default:
                break;
        }

        return ($result);
    }

    /**
     * @param $cord_y
     * @param $cord_x
     * @param $height
     * @param $width
     * @param bool $shadow
     * @param int $bordertype
     * @return resource
     */
    protected function createDialogWindow($cord_y, $cord_x, $height, $width, $shadow = false, $bordertype = 1)
    {

        $win = ncurses_newwin($height, $width, $cord_y, $cord_x);

        // shadow (optional)
        if ($shadow) {
            $this->swin = ncurses_newwin($height, $width, $cord_y + 1, $cord_x + 1);
            ncurses_wrefresh($this->swin);
        }

        // fill window
        ncurses_wcolor_set($win, 3);
        for ($row = 0; $row < $height; $row++) {
            ncurses_wmove($win, $row, 0);
            ncurses_whline($win, 32, $width);
        }

        // Draw the borders
        if ($bordertype == 1) {
            $this->drawDialogBorders($win, $height, $width);
        } else {
            $this->drawThinBorders($win, 0, 0, $height, $width);
        }

        // Draw dialog box title -- (optional)
        if ($this->titleDialogHeader != "") {

            $len = strlen($this->titleDialogHeader);
            $x = round(($width - $len) / 2);
            ncurses_wcolor_set($win, 1);
            ncurses_wattron($win, NCURSES_A_BOLD);
            ncurses_mvwaddstr($win, 0, $x, $this->titleDialogHeader);
            ncurses_wattroff($win, NCURSES_A_BOLD);
            ncurses_wcolor_set($win, 2);
        }


        return ($win);
    }

    /**
     * @param $win
     * @param $y
     * @param $x
     * @param $height
     * @param $width
     */
    protected function drawThinTopLeft(&$win, $y, $x, $height, $width)
    {

        ncurses_wmove($win, $y, $x + 1);
        ncurses_whline($win, 42, $width - 2); // top line

        ncurses_wmove($win, $y + 1, $x);
        ncurses_wvline($win, 42, $height - 2); // left line

        ncurses_wmove($win, $y, $x);
        ncurses_waddch($win, 42);   // top left

        ncurses_wmove($win, $y + $height - 1, $x);
        ncurses_waddch($win, 42);   // bottom left
    }

    /**
     * @param $win
     * @param $y
     * @param $x
     * @param $height
     * @param $width
     */
    protected function drawThinBotRight(&$win, $y, $x, $height, $width)
    {

        ncurses_wmove($win, $y + $height - 1, $x + 1);
        ncurses_whline($win, 42, $width - 2); // bottom line

        ncurses_wmove($win, $y + 1, $x + $width - 1);
        ncurses_wvline($win, 42, $height - 2); // right line

        ncurses_wmove($win, $y, $x + $width - 1);
        ncurses_waddch($win, 42);

        // top right
        ncurses_wmove($win, $y + $height - 1, $x + $width - 1);
        ncurses_waddch($win, 42);    // bottom right
    }

    /**
     * @param $win
     * @param $y
     * @param $x
     * @param $height
     * @param $width
     * @param string $type
     */
    protected function drawThinBorders(&$win, $y, $x, $height, $width, $type = "out")
    {
        // Draw boarders
        //  ------------
        // |			|
        // |			|
        // |			|
        // |			|
        //  ------------
        ncurses_wattron($win, NCURSES_A_BOLD);
        if ($type === 'out') {
            $this->drawThinTopLeft($win, $y, $x, $height, $width);
        } else {
            $this->drawThinBotRight($win, $y, $x, $height, $width);
        }

        //BOTTOM COLOR - REMOVED / LOOKS LIKE SHIT
        //ncurses_wcolor_set($win,1);

        if ($type === 'out') {
            $this->drawThinBotRight($win, $y, $x, $height, $width);
        } else {
            $this->drawThinTopLeft($win, $y, $x, $height, $width);
        }
        ncurses_wattroff($win, NCURSES_A_BOLD);
        ncurses_wborder($win, 0, 0, 0, 0, 0, 0, 0, 0); // bottom line
    }

    /**
     * outputs a paragraph text and returns offset lines taken up.
     * @param $win
     * @param $text
     * @param $y
     * @param $x
     * @param string $align
     * @param bool $wrap
     * @return int
     */
    protected function strokePara(&$win, $text, $y, $x, $align = 'center', $wrap = true)
    {
        if ($wrap) {
            $text = wordwrap($text, ($x - 2), "|");
        }
        $text = str_replace("\n", "|", $text); // decode linebreaks

        $lines = explode("|", $text);

        $curs_y = 1;
        foreach ($lines as $line) {
            $len = strlen($line) + 1;
            if ($align === 'center') { // text alignment
                $text_offset_x = round(($x - $len) / 2);
            } else {
                $text_offset_x = 1;
            }
            ncurses_mvwaddstr($win, $curs_y, $text_offset_x, $line);
            $curs_y++;
        }

        return ($curs_y - 1);  // return lines taken up
    }

    /**
     * initializes a button input type.
     * @param $name
     * @param $hot
     * @param $value
     * @param $y
     * @param $x
     */
    protected function addButton($name, $hot, $value, $y, $x)
    {
        $this->buttonList[$this->buttonTotal]['name'] = $name;
        $this->buttonList[$this->buttonTotal]['hot'] = $hot;
        $this->buttonList[$this->buttonTotal]['value'] = $value;
        $this->buttonList[$this->buttonTotal]['y'] = $y;
        $this->buttonList[$this->buttonTotal]['x'] = $x;

        // add to master focus list
        $this->focusList[$this->focusTotal] = "B-" . $this->buttonTotal;

        $this->buttonTotal++;
        $this->focusTotal++;
    }

    /**
     * Check if any user key input matches button hotkey designations
     * @param $key
     * @return null | mixed the value assigned to that button.
     */
    protected function checkHotButtons($key)
    {
        for ($n = 0; $n < $this->buttonTotal; $n++) {
            $thischar = strtolower(chr($key));
            $hotchar = strtolower($this->buttonList[$n]['hot']);

            if ($thischar == $hotchar) {
                return ($this->buttonList[$n]['value']);
            }
        }

        return (null);
    }

    /**
     * Check if any user key input matches Menu item hotkey designations
     * @param $key
     * @return null | mixed - the value assigned to that Menu item.
     */
    protected function checkHotMenuItems($key)
    {
        for ($n = 0; $n < $this->menuTotal; $n++) {
            $thischar = strtolower(chr($key));
            $hotchar = strtolower($this->menuList[$n]['hot']);

            if ($thischar == $hotchar) {
                if ($this instanceof NcursesChecklist) { // checklists support
                    $this->menuList[$n]['selected'] = ($this->menuList[$n]['selected'] == false ? true : false);

                    return (null);
                } else {
                    $this->menuCursor = $n;

                    return ($this->menuList[$n]['value']);
                }
            }
        }

        return (null);
    }

    /**
     * @return array name/value of all defined input boxes
     */
    protected function returnInputboxVals()
    {
        $results = array();

        for ($n = 0; $n < $this->inputBoxTotal; $n++) {
            $name = $this->inputBoxList[$n]['name'];
            $value = implode('', $this->inputBoxList[$n]['val']); // merge value array to string
            $results[$name] = $value;
        }

        return ($results);
    }

    /**
     * Displays all defined button types
     * @param $win
     */
    protected function strokeAllButtons(&$win)
    {
        for ($i = 0; $i < $this->buttonTotal; $i++) {
            ncurses_mvwaddstr($win, $this->buttonList[$i]['y'], $this->buttonList[$i]['x'], "");
            $len = strlen($this->buttonList[$i]['name']);

            for ($n = 0; $n < $len; $n++) {
                $char = substr($this->buttonList[$i]['name'], $n, 1);
                $ord = ord($char);
                if ($char == $this->buttonList[$i]['hot']) { // highlight char that is the hotkey
                    if (($this->focusCat() === 'B') && ($i == $this->focusSubindex())) {
                        ncurses_wcolor_set($win, 6);
                    } else {
                        ncurses_wcolor_set($win, 7);
                    }
                    ncurses_wattron($win, NCURSES_A_BOLD);
                    ncurses_waddch($win, $ord);
                    ncurses_wattroff($win, NCURSES_A_BOLD);
                } else {
                    if (($this->focusCat() === 'B') && ($i == $this->focusSubindex())) {
                        ncurses_wcolor_set($win, 5);
                    } else {
                        ncurses_wcolor_set($win, 2);
                    }
                    ncurses_waddch($win, $ord);
                }
            }
        }
    }

    /**
     * Gets user input and acts based on button navigation style
     * @param $win
     * @return array|mixed|null|string
     */
    protected function getButtonInput(&$win)
    {
        $exit = null;
        $keyPressed = ncurses_getch();
        //print" {".$keyPressed."} ";

        switch ($keyPressed) {
            case NCURSES_KEY_RIGHT:
            case NCURSES_KEY_DOWN:
            case 9: // tab
                $this->focusCursor++;
                if ($this->focusCursor >= $this->focusTotal) {
                    $this->focusCursor = 0;
                }
                break;


            case NCURSES_KEY_LEFT:
            case NCURSES_KEY_UP:
            case 353: // shift-tab
                $this->focusCursor--;
                if ($this->focusCursor < 0) {
                    $this->focusCursor = $this->focusTotal - 1;
                }
                break;

            case 32: //space
            case 13: //enter
                // get current focus
                list($ftype, $fnum) = explode("-", $this->focusList[$this->focusCursor]);
                // send the button value we are focused on
                $exit = $this->buttonList[$fnum]['value'];

                if ($exit === '#FORM#') {// Support for inputbox
                    $exit = $this->returnInputboxVals();
                }

                break;

            default:
                // check if any button hotkeys were pressed - return 'null' if no match
                $exit = $this->checkHotButtons($keyPressed);
                break;
        }

        return ($exit);
    }

    /**
     * Gets user input and acts based on textbox navigation style
     * @param $win
     * @return null
     */
    protected function getTextboxInput(&$win)
    {
        $exit = null;
        $char = null;
        $keyPressed = ncurses_getch();
        if ($keyPressed < 256) { // is this an ascii code/
            $char = chr($keyPressed);
        }
        //print" {".$keyPressed."} ";

        switch ($keyPressed) {
            case NCURSES_KEY_DOWN:
            case 9: // tab
            case 13: // enter
                $this->focusCursor++;
                if ($this->focusCursor >= $this->focusTotal) {
                    $this->focusCursor = 0;
                }
                break;


            case NCURSES_KEY_UP:
            case 353: // shift-tab
                $this->focusCursor--;
                if ($this->focusCursor < 0) {
                    $this->focusCursor = $this->focusTotal - 1;
                }
                break;


            case NCURSES_KEY_RIGHT:
                $ii = $this->focusSubindex(); // get selected inputbox
                $val_len = &$this->inputBoxList[$ii]['val_len'];
                $cursor = &$this->inputBoxList[$ii]['val_cursor'];
                if ($cursor < $val_len) {
                    $cursor++;
                } else {
                    $crap = ncurses_beep();
                }
                break;


            case NCURSES_KEY_LEFT:
                $ii = $this->focusSubindex(); // get selected inputbox
                $cursor = &$this->inputBoxList[$ii]['val_cursor'];
                if ($cursor > 0) {
                    $cursor--;
                } else {
                    $crap = ncurses_beep();
                }
                break;


            case 263: // backspace
                $ii = $this->focusSubindex(); // get selected inputbox

                $cursor = &$this->inputBoxList[$ii]['val_cursor'];
                $value = &$this->inputBoxList[$ii]['val'];
                $value_len = &$this->inputBoxList[$ii]['val_len'];

                if ($cursor > 0) {
                    $sub1 = array_slice($value, 0, $cursor - 1);
                    $sub2 = array_slice($value, $cursor);
                    $value = array_merge($sub1, $sub2);
                    $cursor--;
                    $value_len = count($value);
                } else {
                    $crap = ncurses_beep();
                }


                // Refresh current input box
                $this->drawInputBoxContents($ii);
                break;

            case 330: // del key
                $ii = $this->focusSubindex(); // get selected inputbox

                $cursor = &$this->inputBoxList[$ii]['val_cursor'];
                $value = &$this->inputBoxList[$ii]['val'];
                $value_len = &$this->inputBoxList[$ii]['val_len'];

                $sub1 = array_slice($value, 0, $cursor);
                $sub2 = array_slice($value, $cursor + 1);
                $value = array_merge($sub1, $sub2);

                $value_len = count($value);

                // Refresh current input box
                $this->drawInputBoxContents($ii);
                break;

            default:
                $ii = $this->focusSubindex(); // get selected inputbox

                $value = &$this->inputBoxList[$ii]['val'];
                $value_len = &$this->inputBoxList[$ii]['val_len'];
                $value_max = &$this->inputBoxList[$ii]['max_length'];
                $cursor = &$this->inputBoxList[$ii]['val_cursor'];

                if (($keyPressed >= 32) && ($keyPressed <= 126)) {
                    if ($value_len < $value_max) {
                        $sub1 = array_slice($value, 0, $cursor);
                        $sub2 = array_slice($value, $cursor);
                        $new = array($char);
                        $value = array_merge($sub1, $new, $sub2);

                        //$value[$cursor] = $char;
                        $cursor++;
                        $value_len++;
                    } else {
                        $crap = ncurses_beep();
                    }
                }

                // Refresh current input box
                $this->drawInputBoxContents($ii);
                break;
        }

        return ($exit);
    }

    /**
     * Gets user input and acts based on Menu/Checklist navigation style
     * @param $win
     * @return array|bool|mixed|null
     */
    protected function getMenuInput(&$win)
    {
        $exit = null;
        $keyPressed = ncurses_getch();
        //print" {".$keyPressed."} ";

        switch ($keyPressed) {
            case NCURSES_KEY_DOWN:
                $this->menuCursor++;
                if ($this->menuCursor >= $this->menuTotal) {
                    $this->menuCursor = 0;
                }
                break;

            case NCURSES_KEY_UP:
                $this->menuCursor--;
                if ($this->menuCursor < 0) {
                    $this->menuCursor = $this->menuTotal - 1;
                }
                break;

            case NCURSES_KEY_RIGHT:
            case 9: // tab
                $this->focusCursor++;
                if ($this->focusCursor >= $this->focusTotal) {
                    $this->focusCursor = 0;
                }
                break;

            case NCURSES_KEY_LEFT :
            case 353: // shift-tab
                $this->focusCursor--;
                if ($this->focusCursor < 0) {
                    $this->focusCursor = $this->focusTotal - 1;
                }
                break;

            case 32: // space
                if ($this instanceof NcursesChecklist) {
                    // toggle item selection
                    $this->menuList[$this->menuCursor]['selected'] = ($this->menuList[$this->menuCursor]['selected'] == false ? true : false);
                }
                break;

            case 13: //enter
                $ii = $this->focusSubindex(); // get selected inputbox
                if ($this instanceof NcursesChecklist) {
                    if ($this->buttonList[$ii]['value'] === true) {
                        return $this->getAllCheckboxVals();
                    } else {
                        return $this->buttonList[$ii]['value'];
                    }
                }

                if ($this instanceof NcursesMenu) {
                    if ($this->buttonList[$ii]['value'] === true) {
                        return $this->menuList[$this->menuCursor]['value'];
                    } else {
                        return $this->buttonList[$ii]['value'];
                    }
                }

                return $this->buttonList[$ii]['value'];

            default:
                // check if any button hotkeys were pressed - return 'null' if no match
                $hot = $this->checkHotButtons($keyPressed); // try trap for hot buttons 1st.

                if ($hot === true) { // was hot button pressed?
                    if ($this instanceof NcursesChecklist) { // checklist support -- (optional)
                        $exit = $this->getAllCheckboxVals();
                    } else { // menu selection
                        // return the menu value we are focused on
                        $exit = $this->menuList[$this->menuCursor]['value'];
                    }
                } elseif ($hot === false) {
                    $exit = false;
                } else { // $hot == null , so let see if there is a hot-checkbox match
                    $exit = $this->checkHotMenuItems($keyPressed);
                }

                break;
        }

        return ($exit);
    }

    /**
     * Returns array name/value of selected check boxes
     * @return array|bool|null
     */
    protected function getAllCheckboxVals()
    {
        $exit = null;
        for ($n = 0; $n < $this->menuTotal; $n++) {
            // return all items selected
            if ($this->menuList[$n]['selected']) {
                $name = &$this->menuList[$n]['name'];
                $value = &$this->menuList[$n]['value'];
                $exit[$name] = $value;
            }
        }
        if (!is_array($exit)) { // if nothing was selected we still need to return something
            $exit = true;
        }

        return ($exit);
    }

    /**
     * Initializes Menu Items (or Checklist) types
     * @param $type
     * @param $name
     * @param $label
     * @param $hotkey
     * @param $desc
     * @param null $value
     * @param bool $selected
     */
    protected function addMenuItem($type, $name, $label, $hotkey, $desc, $value = null, $selected = false)
    {
        $this->menuList[$this->menuTotal]['name'] = $name;
        $this->menuList[$this->menuTotal]['type'] = $type;
        $this->menuList[$this->menuTotal]['selected'] = $selected;
        $this->menuList[$this->menuTotal]['label'] = $label;
        $this->menuList[$this->menuTotal]['desc'] = $desc;
        $this->menuList[$this->menuTotal]['hot'] = $hotkey;
        $this->menuList[$this->menuTotal]['value'] = ($value === null ? $this->menuTotal : $value); // default value if non given
        $this->menuList[$this->menuTotal]['y'] = $this->menuTotal;
        $this->menuList[$this->menuTotal]['x'] = 0;
        $this->menuTotal++;

        $len = strlen($label);
        if ($len > $this->menuLabelWidth) {
            $this->menuLabelWidth = $len;
        } // track largest label width

        $len = strlen($desc);
        if ($len > $this->menuDescWidth) {
            $this->menuDescWidth = $len;
        } // track largest description width
    }

    /**
     * Displays all Menu Items (or Checklist) to screen
     * @param $win
     */
    protected function strokeAllMenuItems(&$win)
    {
        for ($i = 0; $i < $this->menuTotal; $i++) {
            if ($this->menuCursor == $i) {
                ncurses_wcolor_set($win, 5);
            } else {
                ncurses_wcolor_set($win, 2);
            }

            // output Checkbox -- (optional)
            if ($this instanceof NcursesChecklist) { // for checklist type menus
                $sel = ($this->menuList[$i]['selected'] == true ? 'X' : ' ');
                ncurses_mvwaddstr($win, $this->menuList[$i]['y'], $this->menuList[$i]['x'], '[' . $sel . ']');
                ncurses_mvwaddstr($win, $this->menuList[$i]['y'], $this->menuList[$i]['x'] + 4, '');
                $desc_offset = 5;
            } else {
                ncurses_mvwaddstr($win, $this->menuList[$i]['y'], $this->menuList[$i]['x'], '');
                $desc_offset = 2;
            }

            // output menu item label
            $len = strlen($this->menuList[$i]['label']);
            for ($n = 0; $n < $len; $n++) {
                $char = substr($this->menuList[$i]['label'], $n, 1);
                $ord = ord($char);
                if ($char == $this->menuList[$i]['hot']) { // highlight char that is the hotkey
                    //SET HOTKEY COLOR ON SELECTED ITEM
                    if ($this->menuCursor == $i) {
                        ncurses_wcolor_set($win, 5);
                    } //SET HOTKEY COLOR ON UNSELECTED ITEM
                    else {
                        ncurses_wcolor_set($win, 7);
                    }

                    ncurses_wattron($win, NCURSES_A_BOLD);
                    ncurses_waddch($win, $ord);
                    ncurses_wattroff($win, NCURSES_A_BOLD);
                } else {
                    if ($this->menuCursor == $i) {
                        ncurses_wcolor_set($win, 5);
                    } else {
                        ncurses_wcolor_set($win, 2);
                    }
                    ncurses_waddch($win, $ord);
                }
            }
            // output menu item description
            if ($this->menuCursor == $i) {
                ncurses_wcolor_set($win, 5);
            } else {
                ncurses_wcolor_set($win, 2);
            }
            ncurses_mvwaddstr($win, $this->menuList[$i]['y'],
                $this->menuList[$i]['x'] + $this->menuLabelWidth + $desc_offset, $this->menuList[$i]['desc']);
        }
    }

    /**
     * Displays and creates a sub-window to contain the Menu Items
     * @param $win
     * @param $parent_height
     * @param $parent_width
     * @param $para_offset_y
     * @param int $border
     * @return resource - ID of the sub-window
     */
    protected function createMenuSubWindow(&$win, $parent_height, $parent_width, $para_offset_y, $border = 0)
    {
        // auto-detect window width based on menu contents
        if ($this instanceof NcursesChecklist) {
            $menu_width = 1 + 4 + $this->menuLabelWidth + 2 + $this->menuDescWidth + 1;
        } else {
            $menu_width = 1 + $this->menuLabelWidth + 2 + $this->menuDescWidth + 1;
        }

        // Draw borders
        $cord['y'] = 1 + $para_offset_y;
        $cord['x'] = round(($parent_width - $menu_width) / 2);
        //$cord = $this->getCoordinates($parent_height,$menu_width,"center","middle");
        $y = $this->menuTotal + 2;
        $x = $menu_width;

        if ($border != 0) {
            $this->drawThinBorders($win, $cord['y'], $cord['x'], $y, $x);
        }

        // draw window inside borders
        $cord = $this->getCoordinates($parent_height, $menu_width, "center", "middle");
        $swin = ncurses_newwin($y - 2, $x - 2, $cord['y'] + $para_offset_y + 2, $cord['x'] + 1);

        // fill window
        ncurses_wcolor_set($swin, 2);
        for ($row = 0; $row < $y - 2; $row++) {
            ncurses_wmove($swin, $row, 0);
            ncurses_whline($swin, 32, $x - 2);
        }

        return ($swin);
    }

    /**
     * @return string
     */
    public function getTitlePageHeader()
    {
        return $this->titlePageHeader;
    }

    /**
     * @param string $titlePageHeader
     * @return $this
     */
    public function setTitlePageHeader($titlePageHeader)
    {
        $this->titlePageHeader = $titlePageHeader;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitleDialogHeader()
    {
        return $this->titleDialogHeader;
    }

    /**
     * @param mixed $titleDialogHeader
     * @return $this
     */
    public function setTitleDialogHeader($titleDialogHeader)
    {
        $this->titleDialogHeader = $titleDialogHeader;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param mixed $length
     * @return $this
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultVal()
    {
        return $this->defaultVal;
    }

    /**
     * @param mixed $defaultVal
     * @return $this
     */
    public function setDefaultVal($defaultVal)
    {
        $this->defaultVal = $defaultVal;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getScreenMaxWidth()
    {
        return $this->screenMaxWidth;
    }

    /**
     * @param mixed $screenMaxWidth
     * @return $this
     */
    public function setScreenMaxWidth($screenMaxWidth)
    {
        $this->screenMaxWidth = $screenMaxWidth;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getScreenMaxHeight()
    {
        return $this->screenMaxHeight;
    }

    /**
     * @param mixed $screenMaxHeight
     * @return $this
     */
    public function setScreenMaxHeight($screenMaxHeight)
    {
        $this->screenMaxHeight = $screenMaxHeight;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getButtonList()
    {
        return $this->buttonList;
    }

    /**
     * @param mixed $buttonList
     * @return $this
     */
    public function setButtonList($buttonList)
    {
        $this->buttonList = $buttonList;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getButtonTotal()
    {
        return $this->buttonTotal;
    }

    /**
     * @param mixed $buttonTotal
     * @return $this
     */
    public function setButtonTotal($buttonTotal)
    {
        $this->buttonTotal = $buttonTotal;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getButtonCursor()
    {
        return $this->buttonCursor;
    }

    /**
     * @param mixed $buttonCursor
     * @return $this
     */
    public function setButtonCursor($buttonCursor)
    {
        $this->buttonCursor = $buttonCursor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInputBoxList()
    {
        return $this->inputBoxList;
    }

    /**
     * @param mixed $inputBoxList
     * @return $this
     */
    public function setInputBoxList($inputBoxList)
    {
        $this->inputBoxList = $inputBoxList;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInputBoxTotal()
    {
        return $this->inputBoxTotal;
    }

    /**
     * @param mixed $inputBoxTotal
     * @return $this
     */
    public function setInputBoxTotal($inputBoxTotal)
    {
        $this->inputBoxTotal = $inputBoxTotal;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMenuList()
    {
        return $this->menuList;
    }

    /**
     * @param mixed $menuList
     * @return $this
     */
    public function setMenuList($menuList)
    {
        $this->menuList = $menuList;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMenuTotal()
    {
        return $this->menuTotal;
    }

    /**
     * @param mixed $menuTotal
     * @return $this
     */
    public function setMenuTotal($menuTotal)
    {
        $this->menuTotal = $menuTotal;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMenuCursor()
    {
        return $this->menuCursor;
    }

    /**
     * @param mixed $menuCursor
     * @return $this
     */
    public function setMenuCursor($menuCursor)
    {
        $this->menuCursor = $menuCursor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMenuLabelWidth()
    {
        return $this->menuLabelWidth;
    }

    /**
     * @param mixed $menuLabelWidth
     * @return $this
     */
    public function setMenuLabelWidth($menuLabelWidth)
    {
        $this->menuLabelWidth = $menuLabelWidth;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMenuDescWidth()
    {
        return $this->menuDescWidth;
    }

    /**
     * @param mixed $menuDescWidth
     * @return $this
     */
    public function setMenuDescWidth($menuDescWidth)
    {
        $this->menuDescWidth = $menuDescWidth;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNwin()
    {
        return $this->nwin;
    }

    /**
     * @param mixed $nwin
     * @return $this
     */
    public function setNwin($nwin)
    {
        $this->nwin = $nwin;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSwin()
    {
        return $this->swin;
    }

    /**
     * @param mixed $swin
     * @return $this
     */
    public function setSwin($swin)
    {
        $this->swin = $swin;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTextBoxChar()
    {
        return $this->textBoxChar;
    }

    /**
     * @param mixed $textBoxChar
     * @return $this
     */
    public function setTextBoxChar($textBoxChar)
    {
        $this->textBoxChar = $textBoxChar;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTextBoxLen()
    {
        return $this->textBoxLen;
    }

    /**
     * @param mixed $textBoxLen
     * @return $this
     */
    public function setTextBoxLen($textBoxLen)
    {
        $this->textBoxLen = $textBoxLen;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTextBoxMax()
    {
        return $this->textBoxMax;
    }

    /**
     * @param mixed $textBoxMax
     * @return $this
     */
    public function setTextBoxMax($textBoxMax)
    {
        $this->textBoxMax = $textBoxMax;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFocusList()
    {
        return $this->focusList;
    }

    /**
     * @param mixed $focusList
     * @return $this
     */
    public function setFocusList($focusList)
    {
        $this->focusList = $focusList;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFocusTotal()
    {
        return $this->focusTotal;
    }

    /**
     * @param mixed $focusTotal
     * @return $this
     */
    public function setFocusTotal($focusTotal)
    {
        $this->focusTotal = $focusTotal;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFocusCursor()
    {
        return $this->focusCursor;
    }

    /**
     * @param mixed $focusCursor
     * @return $this
     */
    public function setFocusCursor($focusCursor)
    {
        $this->focusCursor = $focusCursor;

        return $this;
    }
}
