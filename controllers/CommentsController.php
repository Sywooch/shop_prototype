<?php

namespace app\controllers;

use yii\helpers\Url;
use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\models\CommentsModel;
use app\mappers\CommentsInsertMapper;

/**
 * Управляет процессом добавления комментария
 */
class CommentsController extends AbstractBaseController
{
    /**
     * Добавляет комментарий к товару
     * @return redirect
     */
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
                    if (!$commentsInsertMapper->setGroup()) {
                        throw new ErrorException('Не удалось обновить данные в БД!');
                    }
                    return $this->redirect(Url::to(['product-detail/index', 'categories'=>$model->categories, 'subcategory'=>$model->subcategory, 'id'=>$model->id_products]));
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
