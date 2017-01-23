<?php

namespace app\console;

use yii\console\Controller;
use yii\helpers\Console;
use app\exceptions\ExceptionsTrait;
use app\rbac\rules\AccountPermissionRule;

/**
 * Инициирует создание RBAC данных авторизации
 */
class RbacController extends Controller
{
    use ExceptionsTrait;
    
    /**
     * Настраивает права доступа
     */
    public function actionSet()
    {
        try {
            $auth = \Yii::$app->authManager;
            
            $this->stdout(\Yii::t('base/console', 'Create RBAC authorization data...' . PHP_EOL));
            
            $this->stdout(\Yii::t('base/console', 'Create an authorization RBAC successfully completed!' . PHP_EOL));
            return parent::EXIT_CODE_NORMAL;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->stderr(\Y::t('base/console', 'Error creating RBAC!' . PHP_EOL), Console::FG_RED);
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
            $this->stderr(\Y::t('base/console', 'Error removing RBAC!' . PHP_EOL), Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
}
