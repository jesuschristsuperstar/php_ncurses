<?php

namespace Ncurses;

/**
 * Class NcursesChecklist
 * @package Ncurses
 */
class NcursesChecklist extends NcursesBase
{
    /**
     * @var array
     */
    protected $buttons = [
        [
            'text' => 'OK',
            'hotkey' => 'O',
            'return' => true
        ],
        [
            'text' => 'Cancel',
            'hotkey' => 'C',
            'return' => false
        ]
    ];


    /**
     * Wrapper method to initialize Checkbox items
     * @param $name
     * @param $label
     * @param $hotkey
     * @param $desc
     * @param null $value
     * @param bool $selected
     */
    public function addChecklist($name, $label, $hotkey, $desc, $value = null, $selected = false)
    {
        $this->addMenuItem("check", $name, $label, $hotkey, $desc, $value, $selected);
    }

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
     * @return mixed
     */
    public function display()
    {
        ncurses_init();
        $this->initScreen();

        // open a dialog box window
        $cord = $this->getCoordinates($this->height, $this->width, "center", "middle");
        $win = $this->createDialogWindow($cord['y'], $cord['x'], $this->height, $this->width, true);

        // output dialog text
        $para_offset_y = $this->strokePara($win, $this->text, $this->height, $this->width, "center", true);

        // Create menu sub-window
        $mwin = $this->createMenuSubWindow($win, $this->height, $this->width, $para_offset_y);

        $this->configureButtons();

        // wait for input
        do {
            $this->strokeAllButtons($win);
            $this->strokeAllMenuItems($mwin);
            ncurses_wrefresh($win);
            ncurses_wrefresh($mwin);
            // get keyboard input			
            $status = $this->getMenuInput($win);
        } while ($status === null);

        return ($status);
    }
}
