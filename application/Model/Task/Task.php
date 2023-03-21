<?php
namespace Model\Task;
use Model\User\User;
use PDO;

class Task extends \Core\Model {
    private $table = 'todo_tasks';

    public function __construct()
    {
        self::addAjaxAllowedMethods('getTaskList');
        self::addAjaxAllowedMethods('create');
        self::addAjaxAllowedMethods('changeStatus');
        self::addAjaxAllowedMethods('changeTaskText');
    }

    public function getCount(){
        $stmt = self::getPdoInstance()->prepare(
            "SELECT COUNT(*) `count` FROM $this->table"
        );
        $stmt->execute([]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getTaskList($limit = 3, $offset = 0, $orderBy = 'id', $orderByType = 'ASC'){
        $orderByColumns = ['email','user_name','status','id'];
        $orderByTypes = ['ASC','DESC'];
        if(in_array($orderBy,$orderByColumns) && in_array($orderByType, $orderByTypes)){
            $orderBy = "ORDER BY $orderBy ".$orderByType;
        }else{
            $orderBy = "";
        }


        $stmt = self::getPdoInstance()->prepare(
            "SELECT * FROM $this->table $orderBy LIMIT :offset,:limit"
        );

        $stmt->execute([
            ':limit' => $limit,
            ':offset' => $offset
        ]);

        return [
            'pages' => ceil($this->getCount()/$limit),
            'items' => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ];
    }

    public function create($username, $email, $taskText){
        $username = mb_substr(trim(strip_tags($username)),0, 100);
        $email = mb_substr(trim(strip_tags($email)), 0, 100);
        $taskText = mb_substr(trim(strip_tags($taskText)), 0, 200);

        $stmt = self::getPdoInstance()->prepare(
            "INSERT INTO $this->table(`user_name`,`email`,`task_text`) VALUES(?,?,?)"
        );
        $stmt->execute([$username,$email,$taskText]);
        return self::getPdoInstance()->lastInsertId();
    }

    public function changeStatus($taskId, $status){
        $userModel = new User();
        $user = $userModel->getUser($_COOKIE['hash']);
        if(empty($user)){
            return 'Not allowed';
        }

        $stmt = self::getPdoInstance()->prepare(
            "UPDATE $this->table SET status = ? WHERE id = ?"
        );
        $stmt->execute([$status,$taskId]);

        return true;
    }

    public function changeTaskText($taskId,$taskText){
        $userModel = new User();
        $user = $userModel->getUser($_COOKIE['hash']);
        if(empty($user)){
            return 'Not allowed';
        }

        $stmt = self::getPdoInstance()->prepare(
            "UPDATE $this->table SET task_text = ?, admin_changed = 1 WHERE id = ?"
        );
        $stmt->execute([$taskText,$taskId]);

        return true;
    }

}