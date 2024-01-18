<?php
namespace App\Core\Auth;

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
        if( !isset($data['email']) || !isset($data['name']) || !isset($data['password']) || !isset($data['role'])){
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
            $data['role'],
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

    private function foundKey(array $data,$serach){
        $index = 0;
        
        foreach($data as $key => $value){
            if($key === $serach){
                return $index;
            }

            $index++;
        }

        return false;
    }

    public function login(array $data) : bool
    {
        $loginKey = AuthenticateSettings::LOGINBY;

        if (!isset($data[$loginKey]) || !isset($data['password'])) {
            return false;
        }
        
        $login = array_keys($data)[$this->foundKey($data, $loginKey)];
        $password = array_keys($data)[$this->foundKey($data, 'password')];
        
        $escapedLogin = $this->engine->escapeString($login);
        $escapedPassword = $this->engine->escapeString($password);
        
        $sql = sprintf(
            'SELECT id, %s, %s, salt FROM `User` WHERE %s = "%s"',
            $escapedLogin,
            $escapedPassword,
            $escapedLogin,
            $this->engine->escapeString($data[$login])
        );
        
        $dataQuery = $this->engine->getQueryLoop($sql);
        
        if (count($dataQuery) > 0) {
            $dataQuery = $dataQuery[0];
            $saltedPassword = $data['password'] . $dataQuery['salt'];
        
            if (password_verify($saltedPassword, $dataQuery['password'])) {
                $_SESSION['id'] = $dataQuery['id'];
                return true;
            }else{
                throw new AuthenticateException("Invalid login data!");
            }
        } else {
            throw new AuthenticateException("Invalid login data!");
        }
        
    }

}
