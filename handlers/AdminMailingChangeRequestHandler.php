<?php

namespace app\handlers;

use yii\base\{ErrorException,
    Model};
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\{AbstractBaseForm,
    AdminMailingForm};
use app\savers\ModelSaver;
use app\models\MailingsModel;
use app\finders\MailingIdFinder;
use app\widgets\AdminMailingDataWidget;

/**
 * Обрабатывает запрос на обновление заказа
 */
class AdminMailingChangeRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на отмену заказа
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new AdminMailingForm(['scenario'=>AdminMailingForm::EDIT]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(MailingIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $mailingsModel = $finder->find();
                        if (empty($mailingsModel)) {
                            throw new ErrorException($this->emptyError('mailingsModel'));
                        }
                        
                        $mailingsModel->scenario = MailingsModel::EDIT;
                        $mailingsModel->attributes = $form->toArray();
                        if ($mailingsModel->validate() === false) {
                            throw new ErrorException($this->modelError($mailingsModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$mailingsModel
                        ]);
                        $saver->save();
                        
                        $mailingForm = new AdminMailingForm();
                        
                        $adminMailingDataWidgetConfig = $this->adminMailingDataWidgetConfig($mailingsModel, $mailingForm);
                        $response = AdminMailingDataWidget::widget($adminMailingDataWidgetConfig);
                        
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
     * Возвращает массив конфигурации для виджета AdminMailingDataWidget
     * @params Model $mailingsModel
     * @param AbstractBaseForm $mailingForm
     * @return array
     */
    private function adminMailingDataWidgetConfig(Model $mailingsModel, AbstractBaseForm $mailingForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['mailing'] = $mailingsModel;
            $dataArray['form'] = $mailingForm;
            $dataArray['template'] = 'admin-mailing-data.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
