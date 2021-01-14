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
        <div class="martfury-loader" style="display: none">
           <div class="sb-chase" style="display: none">
              <div class="sb-chase-dot"></div>
              <div class="sb-chase-dot"></div>
              <div class="sb-chase-dot"></div>
              <div class="sb-chase-dot"></div>
              <div class="sb-chase-dot"></div>
              <div class="sb-chase-dot"></div>
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