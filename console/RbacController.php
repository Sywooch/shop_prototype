<?php

namespace app\console;

use yii\base\ErrorException;
use yii\console\Controller;
use yii\helpers\Console;
use app\exceptions\ExceptionsTrait;

/**
 * Инициирует создание RBAC данных авторизации
 */
class RbacController extends Controller
{
    use ExceptionsTrait;
    
    /**
     * @var int ID пользователя, которому будет назначена роль суперпользователя
     */
    public $superUser;
    
    /**
     * Настраивает права доступа
     */
    public function actionSet()
    {
        try {
            if (empty($this->superUser)) {
                throw new ErrorException($this->emptyError('superUser'));
            }
            
            $auth = \Yii::$app->authManager;
            
            $this->stdout(\Yii::t('base/console', 'Create RBAC authorization data...' . PHP_EOL));
            
            # Разрешение на доступ к главной странице админ раздела
            $adminIndexPermission = $auth->createPermission('adminIndexPermission');
            $auth->add($adminIndexPermission);
            
            # Разрешение на доступ к заказам
            $adminOrdersPermission = $auth->createPermission('adminOrdersPermission');
            $auth->add($adminOrdersPermission);
            
            # Роль суперпользователя
            $superUser = $auth->createRole('superUser');
            $auth->add($superUser);
            
            # Присваиваю разрешения суперпользователю
            $auth->addChild($superUser, $adminIndexPermission);
            $auth->addChild($superUser, $adminOrdersPermission);
            
            # Создаю суперпользователя
            $auth->assign($superUser, $this->superUser);
            
            $this->stdout(\Yii::t('base/console', 'Create an authorization RBAC successfully completed!' . PHP_EOL));
            return parent::EXIT_CODE_NORMAL;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->stderr(\Yii::t('base/console', 'Error creating RBAC!' . PHP_EOL . $t->getMessage() . PHP_EOL), Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
    
    /**
     * Удаляет файлы с правами доступа
     */
    public function actionUnset()
    {
        try {
            $this->stdout(\Yii::t('base/console', 'Removing RBAC authorization data...' . PHP_EOL));
            
            $filePaths = glob('/var/www/html/shop/rbac/*.php');
            if (!empty($filePaths)) {
                foreach ($filePaths as $file) {
                    unlink($file);
                }
            }
            
            $this->stdout(\Yii::t('base/console', 'Removing an authorization RBAC successfully completed!' . PHP_EOL));
            return parent::EXIT_CODE_NORMAL;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->stderr(\Yii::t('base/console', 'Error removing RBAC!' . PHP_EOL . $t->getMessage() . PHP_EOL), Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
    
    public function options($actionID)
    {
        return [
            'superUser',
        ];
    }
}
