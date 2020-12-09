<?php

namespace SynerBay\HTMLElement;


class SystemElement extends AbstractElement
{
    public function __construct()
    {
        $this->addAction('loader');
        $this->addAction('loginModal');
    }

    public function loader()
    {
        echo '
            <div id="example-4" class="loader" style="display: none;">
                <div id="ball-container-1" class="ball-container">
                    <div id="ball-1" class="ball"></div>
                </div>
                <div id="ball-container-2" class="ball-container">
                    <div id="ball-2" class="ball"></div>
                </div>
                <div id="ball-container-3" class="ball-container">
                    <div id="ball-3" class="ball"></div>
                </div>
            </div> 
        ';
    }

    public function loginModal()
    {
        $title = '[angol szöveg!!] popup címe';
        $content = '[angol szöveg!!] A funkciót csak belépés után érhető el!<br>Ugrás a belépés oldalra: <a href="synerbay.com">[Remco kellene a login url - Link]</a>';
        $this->generateModal('login', $title, $content);
    }
}