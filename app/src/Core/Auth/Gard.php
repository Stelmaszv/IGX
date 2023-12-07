<?php

namespace App\Core\Auth;

use App\Core\Controller\AbstractController;

interface Gard
{
    public function authorized(AbstractController $abstractController) : void;
}
