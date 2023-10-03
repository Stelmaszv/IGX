<?php

namespace App\Main\Controller;

use App\Core\Controller\AbstractController;

class Start extends AbstractController
{
    function main() : void
    {
        echo '<pre>';
        var_dump($this->getRoute()->getParams()['id']);
        echo '<pre>';
    }
}