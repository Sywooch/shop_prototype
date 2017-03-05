<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\PaymentsForm;
use app\savers\ModelSaver;
use app\widgets\AdminPaymentsWidget;
use app\models\PaymentsModel;
use app\finders\AdminPaymentsFinder;

/**
 * Обрабатывает запрос на создание формы оплаты
 */
class AdminPaymentCreateRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Добавляет форму оплаты
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new PaymentsForm(['scenario'=>PaymentsForm::CREATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $rawPaymentsModel = new PaymentsModel(['scenario'=>PaymentsModel::CREATE]);
                        $rawPaymentsModel->attributes = $form->toArray();
                        if ($rawPaymentsModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawPaymentsModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawPaymentsModel
                        ]);
                        $saver->save();
                        
                        $finder = \Yii::$app->registry->get(AdminPaymentsFinder::class);
                        $paymentsModelArray = $finder->find();
                        
                        $paymentsForm = new PaymentsForm();
                        
                        $adminPaymentsWidgetConfig = $this->adminPaymentsWidgetConfig($paymentsModelArray, $paymentsForm);
                        $response = AdminPaymentsWidget::widget($adminPaymentsWidgetConfig);
                        
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
