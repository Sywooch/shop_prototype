<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use app\models\CommentsModel;
use app\mappers\CommentsInsertMapper;
use yii\helpers\Url;

/**
 * Управляет процессом добавления комментария
 */
class CommentsController extends AbstractBaseController
{
    public function actionAddComment()
    {
        try {
            $model = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
            
            if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
                if ($model->validate()) {
                    $commentsInsertMapper = new CommentsInsertMapper([
                        'tableName'=>'comments',
                        'fields'=>['text', 'name', 'id_emails', 'id_products'],
                        'objectsArray'=>[$model],
                    ]);
                    $commentsInsertMapper->setGroup();
                    $this->redirect(Url::to(['products-list/index']));
                }
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
