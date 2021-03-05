<?php

namespace SynerBay\HTMLElement;


class SystemElement extends AbstractElement
{
    public function init()
    {
        $this->addAction('loader');
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
}