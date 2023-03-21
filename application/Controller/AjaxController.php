<?php
namespace Controller;

use Core\Controller;
use ReflectionMethod;

class AjaxController extends Controller{
    public function ajax($options){
        if(!isset($options['model'])||!isset($options['method'])){
            echo $this->response('Error','Wrong request');
            return false;
        }
        $options['model'] = 'Model\\'.$options['model'];
        if(!class_exists($options['model'])){
            echo $this->response('Error','Method not found');
            return false;
        }

        $model = new $options['model']();
        $method = $options['method'];

        if(!in_array($options['method'], $model::getAjaxAllowedMethods())){
            echo $this->response('Error','Not allowed method');
            return false;
        }

        unset($options['model']);
        unset($options['method']);

        $methodParameters = new ReflectionMethod($model, $method);
        $methodParameters = $methodParameters->getParameters();

        $callParams = [];

        foreach ($methodParameters as $param){
            if(!isset($options[$param->name])){
                echo $this->response('Error','Wrong method parameters');
                return false;
            }
            $callParams[$param->name] = $options[$param->name];
        }

        echo $this->response('Success',call_user_func_array([$model,$method],$callParams));
        return true;
    }

    private function response($status, $content){
        return json_encode(['status' => $status, 'content' => $content]);
    }
}