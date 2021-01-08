<?php

namespace SynerBay\HTMLElement;


class SystemElement extends AbstractElement
{
    public function init()
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
        $title = 'Login required';
        $content = 'In order to subscribe you need to be signed in. Please create or login <a href="/my-account">here</a>';
        $this->generateModal('login', $title, $content);
    }
}