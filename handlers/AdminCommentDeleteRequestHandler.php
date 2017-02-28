<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\CommentForm;
use app\finders\CommentIdFinder;
use app\models\CommentsModel;
use app\removers\CommentsModelRemover;

/**
 * Обрабатывает запрос на обновление данных товара
 */
class AdminCommentDeleteRequestHandler extends AbstractBaseHandler
{
    /**
     * Обновляет данные товара
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new CommentForm(['scenario'=>CommentForm::DELETE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(CommentIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $commentsModel = $finder->find();
                        
                        $commentsModel->scenario = CommentsModel::DELETE;
                        if ($commentsModel->validate() === false) {
                            throw new ErrorException($this->modelError($commentsModel->errors));
                        }
                        
                        $remover = new CommentsModelRemover([
                            'model'=>$commentsModel
                        ]);
                        $remover->remove();
                        
                        $transaction->commit();
                        
                        return \Yii::t('base', 'Deleted');
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        throw $t;
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
