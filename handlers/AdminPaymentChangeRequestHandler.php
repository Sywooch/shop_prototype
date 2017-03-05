<?php

namespace app\handlers;

use yii\base\{ErrorException,
    Model};
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\{AbstractBaseForm,
    PaymentsForm};
use app\savers\ModelSaver;
use app\models\PaymentsModel;
use app\finders\PaymentIdFinder;
use app\widgets\AdminPaymentDataWidget;

/**
 * Обрабатывает запрос на обновление заказа
 */
class AdminPaymentChangeRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на отмену заказа
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new PaymentsForm(['scenario'=>PaymentsForm::EDIT]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(PaymentIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $paymentsModel = $finder->find();
                        if (empty($paymentsModel)) {
                            throw new ErrorException($this->emptyError('paymentsModel'));
                        }
                        
                        $paymentsModel->scenario = PaymentsModel::EDIT;
                        $paymentsModel->attributes = $form->toArray();
                        if ($paymentsModel->validate() === false) {
                            throw new ErrorException($this->modelError($paymentsModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$paymentsModel
                        ]);
                        $saver->save();
                        
                        $paymentForm = new PaymentsForm();
                        
                        $adminPaymentDataWidgetConfig = $this->adminPaymentDataWidgetConfig($paymentsModel, $paymentForm);
                        $response = AdminPaymentDataWidget::widget($adminPaymentDataWidgetConfig);
                        
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
     * Возвращает массив конфигурации для виджета AdminPaymentDataWidget
     * @params Model $paymentsModel
     * @param AbstractBaseForm $paymentForm
     * @return array
     */
    private function adminPaymentDataWidgetConfig(Model $paymentsModel, AbstractBaseForm $paymentForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['payment'] = $paymentsModel;
            $dataArray['form'] = $paymentForm;
            $dataArray['template'] = 'admin-payment-data.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
