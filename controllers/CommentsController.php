<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use app\models\CommentsModel;
use app\mappers\CommentsInsertMapper;

/**
 * Управляет процессом добавления комментария
 */
class CommentsController extends AbstractBaseController
{
    public function actionAddComment()
    {
        $model = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
        
        if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                $commentsInsertMapper = new CommentsInsertMapper([
                    'tableName'=>'comments',
                    'fields'=>['text', 'name', 'id_emails'],
                    'objectsArray'=>[$model],
                ]);
                $commentsInsertMapper->setGroup();
            }
        }
    }
}
