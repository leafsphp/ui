<?php

namespace Leaf\UI;

class Component
{
    /**
     * @var string $key The unique key of the component
     */
    public $key;

    public function __construct()
    {
        $this->key = Utils::randomId(strtolower(get_class($this)));
    }

    /**
     * @return string
     */
    public function render()
    {
        // 
    }
}
