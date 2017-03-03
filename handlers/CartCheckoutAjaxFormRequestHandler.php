<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use app\handlers\AbstractBaseHandler;
use app\services\GetCurrentCurrencyModelService;
use app\widgets\CartCheckoutWidget;
use app\finders\{DeliveriesFinder,
    PaymentsFinder};
use app\models\CurrencyInterface;
use app\forms\{AbstractBaseForm,
    CustomerInfoForm};
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос страницы с формой оформления заказа
 */
class CartCheckoutAjaxFormRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на создание формы оформления заказа
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if ($request->isAjax === true) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
                }
                
                $finder = \Yii::$app->registry->get(DeliveriesFinder::class);
                $deliveriesArray = $finder->find();
                if (empty($deliveriesArray)) {
                    throw new ErrorException($this->emptyError('deliveriesArray'));
                }
                
                $finder = \Yii::$app->registry->get(PaymentsFinder::class);
                $paymentsArray = $finder->find();
                if (empty($paymentsArray)) {
                    throw new ErrorException($this->emptyError('paymentsArray'));
                }
                
                $customerInfoForm = new CustomerInfoForm();
                
                if (\Yii::$app->user->isGuest === false) {
                    $usersModel = \Yii::$app->user->identity;
                    $customerInfoForm->email = !empty($usersModel->id_email) ? $usersModel->email->email : null;
                    $customerInfoForm->name = !empty($usersModel->id_name) ? $usersModel->name->name : null;
                    $customerInfoForm->surname = !empty($usersModel->id_surname) ? $usersModel->surname->surname: null;
                    $customerInfoForm->phone = !empty($usersModel->id_phone) ? $usersModel->phone->phone : null;
                    $customerInfoForm->address = !empty($usersModel->id_address) ? $usersModel->address->address : null;
                    $customerInfoForm->city = !empty($usersModel->id_city) ? $usersModel->city->city : null;
                    $customerInfoForm->country = !empty($usersModel->id_country) ? $usersModel->country->country : null;
                    $customerInfoForm->postcode = !empty($usersModel->id_postcode) ? $usersModel->postcode->postcode : null;
                }
                
                $cartCheckoutWidgetConfig = $this->cartCheckoutWidgetConfig($deliveriesArray, $paymentsArray, $currentCurrencyModel, $customerInfoForm);
                
                return CartCheckoutWidget::widget($cartCheckoutWidgetConfig);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CartCheckoutWidget
     * @param array $deliveriesArray
     * @param array $paymentsArray
     * @param CurrencyInterface $currentCurrencyModel
     * @param AbstractBaseForm $customerInfoForm
     * @return array
     */
    private function cartCheckoutWidgetConfig(array $deliveriesArray, array $paymentsArray, CurrencyInterface $currentCurrencyModel, AbstractBaseForm $customerInfoForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['deliveries'] = $deliveriesArray;
            $dataArray['payments'] = $paymentsArray;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['form'] = $customerInfoForm;
            $dataArray['template'] = 'cart-checkout-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
