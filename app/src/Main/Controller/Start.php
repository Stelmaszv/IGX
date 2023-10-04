<?php

namespace App\Main\Controller;

use App\Core\Controller\AbstractController;

class Start extends AbstractController
{
    function main() : void
    {
        $this->setTemplete();
        echo $this->getTemplete();
    }
}