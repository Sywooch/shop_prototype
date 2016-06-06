<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use app\models\UsersModel;
use app\mappers\UsersInsertMapper;

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
                    $usersInsertMapper->setOne();
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
}
