<?php

namespace Ncurses;

/**
 * Class NcursesNotice
 * @package Ncurses
 */
class NcursesNotice extends NcursesBase
{
    protected $minWidth = 30;

    /**
     * @return int
     */
    public function getMinWidth()
    {
        return $this->minWidth;
    }

    /**
     * @param int $minWidth
     * @return $this
     */
    public function setMinWidth($minWidth)
    {
        $this->minWidth = $minWidth;

        return $this;
    }

    protected function notice()
    {
        if ((int) $this->width === 0 || (int) $this->height === 0) {
            // auto-detect required height & width based on text
            $this->width = $this->height = 0;
            $text = str_replace("\n", "|", $this->text); // decode any linebreaks
            $lines = explode("|", $text);
            foreach ($lines as $line) {
                $len = strlen($line);
                if ($len > $this->width) {
                    $this->width = $len;
                }
                $this->height++;
            }
        }

        // open a dialog box window
        $width = max(array($this->width, $this->minWidth));

        $cord = $this->getCoordinates($this->height, $width, "center", "middle");

        $this->nwin = $this->createDialogWindow($cord['y'], $cord['x'], $this->height + 2, $width, true, 2);

        $this->strokePara($this->nwin, $this->text, $this->height, $width, "center", false);

        ncurses_wrefresh($this->nwin);
    }
}
