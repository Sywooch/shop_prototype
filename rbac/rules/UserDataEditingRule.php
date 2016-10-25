<?php

namespace app\rbac\rules;

use yii\rbac\Rule;
use app\exceptions\ExceptionsTrait;

/**
 * Задает правило, проверяющее равенство ID аккаунта, 
 * к которому пытается получить доступ текущий пользователь, 
 * и ID его аккаунта
 */
class UserDataEditingRule extends Rule
{
    use ExceptionsTrait;
    
    /**
     * @var string имя правила
     */
    public $name = 'isOwnUserData';
    
    /**
     * Выполняет проверку
     * @return bool
     */
    public function execute($user, $item, $params): bool
    {
        try {
            if (\Yii::$app->request->get('userId')) {
                return ($user->id == \Yii::$app->request->get('userId')) ? true : false;
            }
            
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
