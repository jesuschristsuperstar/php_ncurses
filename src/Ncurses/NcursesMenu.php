<?php

namespace Ncurses;

/**
 * Class NcursesMenu
 * @package Ncurses
 */
class NcursesMenu extends NcursesBase
{
    /**
     * @var array
     */
    protected $buttons = [
        [
            'text' => 'Select',
            'hotkey' => 'S',
            'return' => true
        ],
        [
            'text' => 'Cancel',
            'hotkey' => 'C',
            'return' => false
        ]
    ];

    /**
     * @var int 1 to show border
     */
    protected $border = 0;

    /**
     * Wrapper method to initialize Menu items
     * @param $label
     * @param $hotkey
     * @param $desc
     * @param null $value
     */
    public function addMenu($label, $hotkey, $desc, $value = null)
    {
        $name = '';
        $this->addMenuItem('menu', $name, $label, $hotkey, $desc, $value, false);
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
     * @return int
     */
    public function getBorder()
    {
        return $this->border;
    }

    /**
     * @param int $border
     * @return $this
     */
    public function setBorder($border)
    {
        $this->border = $border;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function menu()
    {
        // open a dialog box window
        $cord = $this->getCoordinates($this->height, $this->width, "center", "middle");
        $win = $this->createDialogWindow($cord['y'], $cord['x'], $this->height, $this->width, true);

        // output dialog text
        $para_offset_y = $this->strokePara($win, $this->text, $this->height, $this->width, "center", true);

        // Create menu sub-window
        $mwin = $this->createMenuSubWindow($win, $this->height, $this->width, $para_offset_y, $this->border);

        // configure buttons
        $this->configureButtons();

        // wait for input
        do {
            $this->strokeAllButtons($win);
            $this->strokeAllMenuItems($mwin);
            ncurses_wrefresh($win);
            ncurses_wrefresh($mwin);
            //get keyboard input
            $status = $this->getMenuInput($win);
        } while ($status === null);

        return ($status);
    }
}
