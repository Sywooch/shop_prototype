<?php

namespace app\handlers;

use yii\base\{ErrorException,
    Model};
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\{AbstractBaseForm,
    CommentForm};
use app\savers\ModelSaver;
use app\models\CommentsModel;
use app\finders\CommentIdFinder;
use app\widgets\AdminCommentDataWidget;

/**
 * Обрабатывает запрос на обновление заказа
 */
class AdminCommentChangeRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на отмену заказа
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new CommentForm(['scenario'=>CommentForm::EDIT]);
            
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
                        if (empty($commentsModel)) {
                            throw new ErrorException($this->emptyError('commentsModel'));
                        }
                        
                        $commentsModel->scenario = CommentsModel::EDIT;
                        $commentsModel->id = $form->id;
                        $commentsModel->text = $form->text;
                        $commentsModel->active = $form->active;
                        if ($commentsModel->validate() === false) {
                            throw new ErrorException($this->modelError($commentsModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$commentsModel
                        ]);
                        $saver->save();
                        
                        $commentForm = new CommentForm();
                        
                        $adminCommentDataWidgetConfig = $this->adminCommentDataWidgetConfig($commentsModel, $commentForm);
                        $response = AdminCommentDataWidget::widget($adminCommentDataWidgetConfig);
                        
                        $transaction->commit();
                        
                        return $response;
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
    
    /**
     * Возвращает массив конфигурации для виджета AdminCommentDataWidget
     * @params Model $commentsModel
     * @param AbstractBaseForm $commentForm
     * @return array
     */
    private function adminCommentDataWidgetConfig(Model $commentsModel, AbstractBaseForm $commentForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['commentsModel'] = $commentsModel;
            $dataArray['form'] = $commentForm;
            $dataArray['template'] = 'admin-comment-data.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
