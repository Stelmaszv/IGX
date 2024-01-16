<?php

namespace App\Core\Auth;

use App\Main\Collections\Role;
use App\Main\Collections\RolesMapCollection;
use App\Core\Model\ModelValidateException;

trait AuthenticateAction
{
    public function fromActionRegister($POST)  : bool
    {
        
        $POST['roles'] = new RolesMapCollection(); 
        $POST['roles']->addRole(new Role('create'));

        try{
            $auth = $this->getAuthenticate();
            $auth->register($POST);
        }catch (ModelValidateException $modelValidateException){
            $this->erros[] =  [
                "error" => $modelValidateException->getMessage()
            ];
        }

        return count($this->erros) === 0;
  
    }

    public function fromActionLogin($POST) : bool
    {

        try{
            $auth = $this->getAuthenticate();
            $auth->login($POST);
        }catch (AuthenticateException $Authenticate ){
            $this->erros[] =  [
                "error" => $Authenticate->getMessage()
            ];
        }

        return count($this->erros) === 0;
    
    }
}
