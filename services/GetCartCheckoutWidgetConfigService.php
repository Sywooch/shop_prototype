<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService};
use app\forms\CustomerInfoForm;
use app\finders\{DeliveriesFinder,
    PaymentsFinder};

/**
 * Возвращает массив данных для CartCheckoutWidget
 */
class GetCartCheckoutWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array данные для CartCheckoutWidget
     */
    private $cartCheckoutWidgetArray = [];
    
    /**
     * Возвращает массив данных для CartCheckoutWidget
     * @param $request
     * @return mixed
     */
    public function handle($request=null)
    {
        try {
            if (empty($this->cartCheckoutWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(DeliveriesFinder::class);
                $deliveriesArray = $finder->find();
                if (empty($deliveriesArray)) {
                    throw new ErrorException($this->emptyError('deliveriesArray'));
                }
                $dataArray['deliveries'] = $deliveriesArray;
                
                $finder = \Yii::$app->registry->get(PaymentsFinder::class);
                $paymentsArray = $finder->find();
                if (empty($paymentsArray)) {
                    throw new ErrorException($this->emptyError('paymentsArray'));
                }
                $dataArray['payments'] = $paymentsArray;
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $form = new CustomerInfoForm(['scenario'=>CustomerInfoForm::CHECKOUT]);
                if (\Yii::$app->user->isGuest === false) {
                    $user = \Yii::$app->user->identity;
                    
                    $form->email = !empty($user->id_email) ? $user->email->email : null;
                    $form->name = !empty($user->id_name) ? $user->name->name : null;
                    $form->surname = !empty($user->id_surname) ? $user->surname->surname: null;
                    $form->phone = !empty($user->id_phone) ? $user->phone->phone : null;
                    $form->address = !empty($user->id_address) ? $user->address->address : null;
                    $form->city = !empty($user->id_city) ? $user->city->city : null;
                    $form->country = !empty($user->id_country) ? $user->country->country : null;
                    $form->postcode = !empty($user->id_postcode) ? $user->postcode->postcode : null;
                }
                $dataArray['form'] = $form;
                
                $dataArray['view'] = 'cart-checkout-form.twig';
                
                $this->cartCheckoutWidgetArray = $dataArray;
            }
            
            return $this->cartCheckoutWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
