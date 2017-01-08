<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\AbstractBaseService;
use app\forms\CommentForm;
use app\services\{EmailGetSaveEmailService,
    NameGetSaveNameService};
use app\savers\ModelSaver;
use app\widgets\CommentSaveInfoWidget;
use app\models\CommentsModel;

/**
 * Сохраняет новый комментарий
 */
class CommentSaveService extends AbstractBaseService
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
                        $service = new EmailGetSaveEmailService();
                        $emailsModel = $service->handle(['email'=>$form->email]);
                        
                        $service = new NameGetSaveNameService();
                        $namesModel = $service->handle(['name'=>$form->name]);
                        
                        $rawCommentsModel = new CommentsModel();
                        $rawCommentsModel->date = time();
                        $rawCommentsModel->text = $form->text;
                        $rawCommentsModel->id_name = $namesModel->id;
                        $rawCommentsModel->id_email = $emailsModel->id;
                        $rawCommentsModel->id_product = $form->id_product;
                        
                        $saver = new ModelSaver([
                            'model'=>$rawCommentsModel
                        ]);
                        $saver->save();
                        
                        $transaction->commit();
                        
                        return CommentSaveInfoWidget::widget(['view'=>'save-comment-info.twig']);
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
