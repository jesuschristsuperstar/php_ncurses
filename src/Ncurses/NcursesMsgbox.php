<?php

namespace Ncurses;

/**
 * Class NcursesMsgbox
 * @package Ncurses
 */
class NcursesMsgbox extends NcursesBase
{
    /**
     * @var array
     */
    protected $buttons = [
        [
            'text' => 'OK',
            'hotkey' => 'O',
            'return' => true
        ]
    ];

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
     * @return bool
     */
    protected function messageBox()
    {
        //open a dialog box window
        $cord = $this->getCoordinates($this->height, $this->width, 'center', 'middle');
        $win = $this->createDialogWindow($cord['y'], $cord['x'], $this->height, $this->width, true);

        //output dialog text
        $this->strokePara($win, $this->text, $this->height, $this->width, 'center', true);

        //ok button
        $this->configureButtons();

        //wait for input
        do {
            $this->strokeAllButtons($win);
            ncurses_wrefresh($win);
            //get keyboard input
            $status = $this->getButtonInput($win);
        } while ($status === null);

        return true;
    }
}
