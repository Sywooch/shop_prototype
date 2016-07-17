<?php

namespace app\filters;

use yii\base\ActionFilter;

/**
 * Отключает CSRF валидацию
 */
class CsrfSwitch extends ActionFilter
{
    /**
     * Вызывается перед действием, отключая csrf валидацию, если был передан GET параметр csrfdisable=1
     * @param object $action действие, перед которым вызывается метод
     * @return boolean true|false
     */
    public function beforeAction($action)
    {
        if (\Yii::$app->request->get('csrfdisable')) {
            \Yii::$app->request->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    
    /**
     * Вызывается после действия, включая csrf валидацию
     * @param object $action действие, после которого вызывается метод
     * @param mixed $result результат выполнения действия
     * @return mixed 
     */
    public function afterAction($action, $result)
    {
        if (\Yii::$app->request->get('csrfdisable')) {
            \Yii::$app->request->enableCsrfValidation = true;
        }
        return parent::afterAction($action, $result);
    }
}
