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
                        'fields'=>['login', 'password', 'name', 'surname'],
                        'objectsArray'=>[$model],
                    ]);
                    $result = $usersInsertMapper->setGroup();
                    
                    $usersRulesInsertMapper = new UsersRulesInsertMapper([
                        'tableName'=>'users_rules',
                        'fields'=>['id_users', 'id_rules'],
                        'model'=>$model
                    ]);
                    $result = $usersRulesInsertMapper->setGroup();
                }
            }
            
            $resultArray = array_merge(['model'=>$model], $this->getDataForRender());
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
        return $this->render('add-user.twig', $resultArray);
    }
}
