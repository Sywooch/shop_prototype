<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\services\{EmailGetSaveEmailService,
    NameGetSaveNameService};
use app\forms\CommentForm;
use app\savers\ModelSaver;
use app\widgets\CommentSaveSuccessWidget;
use app\models\CommentsModel;

/**
 * Сохраняет новый комментарий
 */
class CommentSaveRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на сохранение нового комментария
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new CommentForm(['scenario'=>CommentForm::SAVE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction  = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $service = \Yii::$app->registry->get(EmailGetSaveEmailService::class, [
                            'email'=>$form->email
                        ]);
                        $emailsModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(NameGetSaveNameService::class, [
                            'name'=>$form->name
                        ]);
                        $namesModel = $service->get();
                        
                        $rawCommentsModel = new CommentsModel(['scenario'=>CommentsModel::SAVE]);
                        $rawCommentsModel->date = time();
                        $rawCommentsModel->text = $form->text;
                        $rawCommentsModel->id_name = $namesModel->id;
                        $rawCommentsModel->id_email = $emailsModel->id;
                        $rawCommentsModel->id_product = $form->id_product;
                        if ($rawCommentsModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawCommentsModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawCommentsModel
                        ]);
                        $saver->save();
                        
                        $response = CommentSaveSuccessWidget::widget(['template'=>'comment-save-success.twig']);
                        
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
}
