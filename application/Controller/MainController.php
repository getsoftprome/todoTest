<?php
namespace Controller;

use Core\Controller;
use Model\Task\Task;


class MainController extends Controller{
    public function mainPage($options){
        $taskModel = new Task();
        $taskList =  $taskModel->getTaskList();

        $this->configPage['taskList'] = $taskList;
        $this->configPage['user'] = $this->user;

        
        $this->view->render('main',$this->configPage);
    }
    public function notFoundPage($options){
        $this->view->render('404page',[]);
    }
}