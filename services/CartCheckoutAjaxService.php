<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\services\{AbstractBaseService,
    AddressGetSaveAddressService,
    CityGetSaveCityService,
    CountryGetSaveCountryService,
    EmailGetSaveEmailService,
    NameGetSaveNameService,
    PhoneGetSavePhoneService,
    PostcodeGetSavePostcodeService,
    SurnameGetSaveSurnameService};
use app\forms\CustomerInfoForm;
use app\models\PurchasesModel;
use app\finders\PurchasesSessionFinder;
use app\helpers\HashHelper;
use app\savers\{PurchasesArraySaver,
    SessionModelSaver};
use app\cleaners\SessionCleaner;
use app\widgets\SuccessSendPurchaseWidget;

/**
 * Сохраняет оформленные покупки
 */
class CartCheckoutAjaxService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на сохранение данных о покупках
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $form = new CustomerInfoForm(['scenario'=>CustomerInfoForm::CHECKOUT]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $saver = new SessionModelSaver([
                        'key'=>HashHelper::createCartCustomerKey(),
                        'model'=>$form
                    ]);
                    $saver->save();
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $service = \Yii::$app->registry->get(NameGetSaveNameService::class);
                        $namesModel = $service->handle(['name'=>$form->name]);
                        
                        $service = \Yii::$app->registry->get(SurnameGetSaveSurnameService::class);
                        $surnamesModel = $service->handle(['surname'=>$form->surname]);
                        
                        $service = \Yii::$app->registry->get(EmailGetSaveEmailService::class);
                        $emailsModel = $service->handle(['email'=>$form->email]);
                        
                        $service = \Yii::$app->registry->get(PhoneGetSavePhoneService::class);
                        $phonesModel = $service->handle(['phone'=>$form->phone]);
                        
                        $service = \Yii::$app->registry->get(AddressGetSaveAddressService::class);
                        $addressModel = $service->handle(['address'=>$form->address]);
                        
                        $service = \Yii::$app->registry->get(CityGetSaveCityService::class);
                        $citiesModel = $service->handle(['city'=>$form->city]);
                        
                        $service = \Yii::$app->registry->get(CountryGetSaveCountryService::class);
                        $countriesModel = $service->handle(['country'=>$form->country]);
                        
                        $service = \Yii::$app->registry->get(PostcodeGetSavePostcodeService::class);
                        $postcodesModel = $service->handle(['postcode'=>$form->postcode]);
                        
                        $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, ['key'=>HashHelper::createCartKey()]);
                        $purchasesCollection = $finder->find();
                        
                        $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::SAVE]);
                        
                        $rawPurchasesModelArray = [];
                        
                        foreach ($purchasesCollection as $purchases) {
                            $cloneRawPurchasesModel = clone $rawPurchasesModel;
                            $cloneRawPurchasesModel->id_name = $namesModel->id;
                            $cloneRawPurchasesModel->id_surname = $surnamesModel->id;
                            $cloneRawPurchasesModel->id_email = $emailsModel->id;
                            $cloneRawPurchasesModel->id_phone = $phonesModel->id;
                            $cloneRawPurchasesModel->id_address = $addressModel->id;
                            $cloneRawPurchasesModel->id_city = $citiesModel->id;
                            $cloneRawPurchasesModel->id_country = $countriesModel->id;
                            $cloneRawPurchasesModel->id_postcode = $postcodesModel->id;
                            $cloneRawPurchasesModel->id_product = $purchases->id_product;
                            $cloneRawPurchasesModel->quantity = $purchases->quantity;
                            $cloneRawPurchasesModel->id_color = $purchases->id_color;
                            $cloneRawPurchasesModel->id_size = $purchases->id_size;
                            $cloneRawPurchasesModel->price = $purchases->price;
                            $cloneRawPurchasesModel->id_delivery = $form->id_delivery;
                            $cloneRawPurchasesModel->id_payment = $form->id_payment;
                            $cloneRawPurchasesModel->received = true;
                            $cloneRawPurchasesModel->received_date = time();
                            if ($cloneRawPurchasesModel->validate() === false) {
                                throw new ErrorException($this->modelError($cloneRawPurchasesModel->errors));
                            }
                            $rawPurchasesModelArray[] = $cloneRawPurchasesModel;
                        }
                        
                        $saver = new PurchasesArraySaver([
                            'models'=>$rawPurchasesModelArray
                        ]);
                        $saver->save();
                        
                        $cleaner = new SessionCleaner([
                            'keys'=>[HashHelper::createCartKey(), HashHelper::createCartCustomerKey()],
                        ]);
                        $cleaner->clean();
                        
                        /*$dataArray = [];
                        
                        $service = new GetSuccessSendPurchaseWidgetConfigService();
                        $successSendPurchaseWidgetConfig = $service->handle();
                        $dataArray['successInfo'] = SuccessSendPurchaseWidget::widget($successSendPurchaseWidgetConfig);
                        
                        $dataArray['redirectUrl'] = Url::to(['/products-list/index']);*/
                        
                        $transaction->commit();
                        
                        return Url::to(['/products-list/index']);
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
