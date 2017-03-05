<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\PaymentsForm;
use app\models\PaymentsModel;
use app\removers\PaymentsModelRemover;
use app\widgets\AdminPaymentsWidget;
use app\finders\{AdminPaymentsFinder,
    PaymentIdFinder};

/**
 * Обрабатывает запрос на удаление данных о форме оплаты
 */
class AdminPaymentDeleteRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Обновляет данные о форме оплаты
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new PaymentsForm(['scenario'=>PaymentsForm::DELETE]);
            
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
                        
                        $paymentsModel->scenario = PaymentsModel::DELETE;
                        if ($paymentsModel->validate() === false) {
                            throw new ErrorException($this->modelError($paymentsModel->errors));
                        }
                        
                        $remover = new PaymentsModelRemover([
                            'model'=>$paymentsModel
                        ]);
                        $remover->remove();
                        
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
