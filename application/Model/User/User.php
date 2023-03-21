<?php
namespace Model\User;
use PDO;

class User extends \Core\Model {
    private $table = 'todo_users';

    public function __construct()
    {
        self::addAjaxAllowedMethods('auth');
        self::addAjaxAllowedMethods('logout');
    }

    public function auth($login,$password){
        $login = trim($login);
        $password = hash('SHA256',trim($password));
        $stmt = self::getPdoInstance()->prepare(
            "SELECT * FROM $this->table WHERE login = ? AND password = ?"
        );

        $stmt->execute([$login, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
            return false;
        }

        $hash = hash('SHA256',$login.$password.rand(999999,9999999).time());
        $stmt = self::getPdoInstance()->prepare(
            "UPDATE $this->table SET hash = ? WHERE id = ?"
        );
        $stmt->execute([$hash, $user['id']]);

        setcookie('hash',$hash,time() + 36000);

        return true;
    }

    public function logout(){
        setcookie('hash','',0);
        return true;
    }

    public function getUser($hash){
        $stmt = self::getPdoInstance()->prepare(
            "SELECT * FROM $this->table WHERE hash = ?"
        );
        $stmt->execute([$hash]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}