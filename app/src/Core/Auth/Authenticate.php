<?php
namespace App\Core\Auth;

use App\Core\MapCollection;
use App\Main\Entity\UserEntity;
use App\Infrastructure\DB\DBInterface;
use App\Settings\AuthenticateSettings;

class Authenticate
{
    protected DBInterface $engine; 

    public function __construct(DBInterface $engine)
    {
        $this->engine = $engine;
    }

    public function register(array $data) : void
    {
        if( !isset($data['email']) || !isset($data['name']) || !isset($data['password']) || !isset($data['roles'])){
            throw new AuthenticateException("Invalid Data! name, password or roles are required !"); 
        }
        
        $table = AuthenticateSettings::TABLE;
        $entity = AuthenticateSettings::ENTITY;
        $solt = bin2hex(random_bytes(AuthenticateSettings::SALD));
        $tableObj = new $table($this->engine);
        
        $objEntity = new $entity(
            $data['name'],
            password_hash($data['password'].$solt, PASSWORD_BCRYPT),
            $data['email'],
            $data['roles']->map(),
            $solt
        );

        $tableObj->add($objEntity);
    }

    public function inLogin()  : bool {
        if($_SESSION !== null){
            return count($_SESSION) > 0;
        } 

        return false;
    }

    public function getUser() : ?UserEntity{
        if($this->inLogin()){
            $table = AuthenticateSettings::TABLE;
            $tableObj = new $table($this->engine);
            return $tableObj->get($_SESSION['id']);
        }

        return null;
    }

    public function logout()  : void {
        session_destroy();
    }

    public function login(array $data) : bool
    {
        if(
            !isset($data['email']) || !isset($data['password'])
        ){
            return false;
        }
            
        $dataQuery = $this->engine->getQueryLoop('SELECT id,'.$this->engine->escapeString(array_keys($data)[0]).','.$this->engine->escapeString(array_keys($data)[1]).', salt FROM `User` WHERE '.$this->engine->escapeString(array_keys($data)[0]).' = "'.$this->engine->escapeString($data['email']).'"');

       
        if(count($dataQuery) > 0){
            $dataQuery = $dataQuery[0];
            if (password_verify($data['password'].$dataQuery['salt'], $dataQuery['password'])) {
                $_SESSION['id'] = $dataQuery['id'];
                return true;
            }
        }else{
            throw new AuthenticateException("Invalid login data !"); 
        }
    }

}
