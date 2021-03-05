<?php

namespace SynerBay\Functions;

class FunctionLoader {
    public function __construct()
    {
        add_action( 'setup_theme', [$this, 'synerbayLoadFunctions'], 1);
    }

    public function synerbayLoadFunctions() {
        // itt kell behúzni a fájlokat
        require_once 'system.php';
        require_once 'martfury.php';
    }
}

new FunctionLoader();
