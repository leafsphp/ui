<?php

namespace Leaf;

use Leaf\UI\Component;
use Leaf\UI\Core;

/**
 * Leaf UI
 * ---------------------
 * A PHP library for building user interfaces.
 * 
 * @author Michael Darko <mychi.darko@gmail.com>
 */
class UI
{
    /**
     * @inheritDoc
     */
    public static function render(Component $component)
    {
        return Core::render($component);
    }
}
