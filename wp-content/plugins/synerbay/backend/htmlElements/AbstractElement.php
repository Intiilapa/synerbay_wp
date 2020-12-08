<?php

namespace SynerBay\Element;

use SynerBay\Traits\WPActionLoader;

class AbstractElement
{
    use WPActionLoader;

    protected function generateModal($name, $title, $content)
    {
        echo '
            <div id="mf-'. $name .'-popup" class="martfury-modal mf-'.$name.'-popup mf-newsletter-popup" tabindex="-1" aria-hidden="true">
                <div class="mf-modal-overlay"></div>
                <div class="modal-content">
                <div class="modal-header" style="padding-left: 20px;">
                    <h5 class="modal-title">'.$title.'</h5>
                </div>
                <a href="#" class="close-modal">
                    <i class="icon-cross"></i>
                </a>
                <div class="newletter-content" style="margin-bottom: 30px;">
                    '. $content .'
                </div>
                </div>
            </div>
        ';
    }
}