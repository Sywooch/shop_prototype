<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\MailingsForm;
use app\savers\ModelSaver;
use app\widgets\AdminMailingsWidget;
use app\models\MailingsModel;
use app\finders\AdminMailingsFinder;

/**
 * Обрабатывает запрос на добавление подписки
 */
class AdminMailingCreateRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Добавляет подписку
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new MailingsForm(['scenario'=>MailingsForm::CREATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $rawMailingsModel = new MailingsModel(['scenario'=>MailingsModel::CREATE]);
                        $rawMailingsModel->attributes = $form->toArray();
                        if ($rawMailingsModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawMailingsModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawMailingsModel
                        ]);
                        $saver->save();
                        
                        $finder = \Yii::$app->registry->get(AdminMailingsFinder::class);
                        $mailingsModelArray = $finder->find();
                        
                        $mailingsForm = new MailingsForm();
                        
                        $adminMailingsWidgetConfig = $this->adminMailingsWidgetConfig($mailingsModelArray, $mailingsForm);
                        $response = AdminMailingsWidget::widget($adminMailingsWidgetConfig);
                        
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
