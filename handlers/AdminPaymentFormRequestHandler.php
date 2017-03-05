<?php

namespace app\handlers;

use yii\base\{ErrorException,
    Model};
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\{AbstractBaseForm,
    PaymentsForm};
use app\widgets\AdminPaymentFormWidget;
use app\finders\PaymentIdFinder;

/**
 * Обрабатывает запрос на получение данных 
 * с формой редактирования деталей типа оплаты
 */
class AdminPaymentFormRequestHandler extends AbstractBaseHandler
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param array $request
     */
    public function handle($request)
    {
        try {
           $form = new PaymentsForm(['scenario'=>PaymentsForm::GET]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $finder = \Yii::$app->registry->get(PaymentIdFinder::class, [
                        'id'=>$form->id
                    ]);
                    $paymentsModel = $finder->find();
                    if (empty($paymentsModel)) {
                        throw new ErrorException($this->emptyError('paymentsModel'));
                    }
                    
                    $paymentForm = new PaymentsForm();
                    
                    $adminPaymentFormWidgetConfig = $this->adminPaymentFormWidgetConfig($paymentsModel, $paymentForm);
                    
                    return AdminPaymentFormWidget::widget($adminPaymentFormWidgetConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminPaymentFormWidget
     * @param Model $paymentsModel
     * @param AbstractBaseForm $paymentForm
     * @return array
     */
    private function adminPaymentFormWidgetConfig(Model $paymentsModel, AbstractBaseForm $paymentForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['payment'] = $paymentsModel;
            $dataArray['form'] = $paymentForm;
            $dataArray['template'] = 'admin-payment-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
