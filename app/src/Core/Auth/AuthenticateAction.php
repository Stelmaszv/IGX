<?php

namespace App\Core\Auth;

use App\Main\Collections\Role;
use App\Main\Collections\RolesMapCollection;
use App\Core\Model\ModelValidateException;

trait AuthenticateAction
{
    public function fromAuthActionRegister($POST)  : bool
    {
        
        $POST['roles'] = new RolesMapCollection(); 
        $POST['roles']->addRole(new Role('create'));

        try{
            $auth = $this->getAuthenticate();
            $auth->register($POST);
        }catch (ModelValidateException $modelValidateException){
            $this->errors[] =  [
                "error" => $modelValidateException->getMessage()
            ];
        }

        return count($this->errors) === 0;
  
    }

    public function fromAuthActionLogin($POST) : bool
    {

        try{
            $auth = $this->getAuthenticate();
            $auth->login($POST);
        }catch (AuthenticateException $Authenticate ){
            $this->errors[] =  [
                "error" => $Authenticate->getMessage()
            ];
        }

        return count($this->errors) === 0;
    
    }
}
