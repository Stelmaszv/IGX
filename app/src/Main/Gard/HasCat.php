<?php

namespace App\Main\Gard;

use App\Core\Auth\Gard;
use App\Main\Model\Cats;
use App\Core\Controller\AbstractController;
use App\Core\Controller\UnauthorizedException;

class HasCat implements Gard
{
    public function authorized(AbstractController $abstractController) : void
    {
        $cats = $abstractController->getModel(Cats::class);

        $count = $cats->count([
            [
                'column' => 'name',
                'value' => 'ewf'
            ]
        ]);

        if( $count > 0 ){
            throw new UnauthorizedException('Unauthorized access !');
        }
    }

}
