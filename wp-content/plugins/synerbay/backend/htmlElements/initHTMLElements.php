<?php
namespace SynerBay\HTMLElement;

include_once 'AbstractElement.php';
include_once 'SystemElement.php';
include_once 'TextElement.php';
include_once 'SelectElement.php';
include_once 'JavascriptElements.php';
include_once 'ButtonElement.php';

new SystemElement();
new SelectElement();
new TextElement();
new JavascriptElements();
new ButtonElement();