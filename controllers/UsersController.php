<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use app\models\UsersModel;
use app\mappers\UsersInsertMapper;
use app\mappers\UsersRulesInsertMapper;
use app\factories\UsersRulesAutonomicFactory;

/**
 * Управляет работой с пользователями
 */
class UsersController extends AbstractBaseController
{
    /**
     * Управляет процессом создания учетной записи
     */
    public function actionAddUser()
    {
        try {
            $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
            
            if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
                if ($model->validate()) {
                    $usersInsertMapper = new UsersInsertMapper([
                        'tableName'=>'users',
                        'fields'=>['login', 'password', 'name'],
                        'objectsOne'=>$model
                    ]);
                    $result = $usersInsertMapper->setOne();
                    
                    $usersRulesAutonomicFactory = new UsersRulesAutonomicFactory(['dataArray'=>$this->getUsersRulesObjectsArray($model)]);
                    $usersRulesObjectsArray = $usersRulesAutonomicFactory->getObjects();
                    
                    $usersRulesInsertMapper = new UsersRulesInsertMapper([
                        'tableName'=>'users_rules',
                        'fields'=>['id_users', 'id_rules'],
                        'objectsArray'=>$usersRulesObjectsArray,
                    ]);
                    $result = $usersRulesInsertMapper->setGroup();
                }
            }
            
            $dataForRender = $this->getDataForRender();
            $resultArray = array_merge(['model'=>$model], $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $this->render('add-user.twig', $resultArray);
    }
    
    /**
     * Создает массивы для создания строк таблицы users_rules из данных модели UsersModel
     * @param object $model объект app\models\UsersModel
     * @return array
     */
    private function getUsersRulesObjectsArray($model)
    {
        $result = array();
        $rulesForUser = $model->rulesFromForm;
        foreach ($rulesForUser as $rule) {
            $result[] = ['id_users'=>$model->id, 'id_rules'=>$rule];
        }
        return $result;
    }
}
