<?php

namespace app\controllers;

use yii\helpers\Url;
use app\helpers\MappersHelper;
use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\models\CommentsModel;

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
            $commentsModel = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_FORM]);
            
            if (\Yii::$app->request->isPost && $commentsModel->load(\Yii::$app->request->post())) {
                if ($commentsModel->validate()) {
                    if (!MappersHelper::setCommentsModel($commentsModel)) {
                        throw new ErrorException('Не удалось обновить данные в БД!');
                    }
                    return $this->redirect(Url::to(['product-detail/index', 'categories'=>$commentsModel->categories, 'subcategory'=>$commentsModel->subcategory, 'id'=>$commentsModel->id_products]));
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
