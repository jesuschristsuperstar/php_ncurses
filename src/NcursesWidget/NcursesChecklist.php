<?php

namespace NcursesWidget;

/**
 * Class NcursesChecklist
 * @package NcursesWidget
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
        $this->addMenuItem('check', $name, $label, $hotkey, $desc, $value, $selected);
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
        $cord = $this->getCoordinates(
            $this->getHeight(),
            $this->getWidth(),
            self::COORD_X_CENTER,
            self::COORD_Y_MIDDLE
        );

        $mainWindow = $this->createDialogWindow($cord['y'], $cord['x'], $this->getHeight(), $this->getWidth(), true);

        // output dialog text
        $paraOffsetY = $this->strokePara(
            $mainWindow,
            $this->getText(),
            $this->getHeight(),
            $this->getWidth(),
            'center',
            true
        );

        // Create menu sub-window
        $menuSubWindow = $this->createMenuSubWindow($mainWindow, $this->getHeight(), $this->getWidth(), $paraOffsetY);

        $this->configureButtons();

        // wait for input
        do {
            $this->strokeAllButtons($mainWindow);
            $this->strokeAllMenuItems($menuSubWindow);
            ncurses_wrefresh($mainWindow);
            ncurses_wrefresh($menuSubWindow);
            //get keyboard input
            $status = $this->getMenuInput($mainWindow);
        } while ($status === null);

        return $status;
    }
}
