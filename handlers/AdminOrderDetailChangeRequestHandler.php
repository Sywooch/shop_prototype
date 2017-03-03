<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\services\{AddressGetSaveAddressService,
    CityGetSaveCityService,
    CountryGetSaveCountryService,
    GetCurrentCurrencyModelService,
    NameGetSaveNameService,
    PhoneGetSavePhoneService,
    PostcodeGetSavePostcodeService,
    SurnameGetSaveSurnameService};
use app\forms\{AbstractBaseForm,
    AdminChangeOrderForm};
use app\savers\ModelSaver;
use app\models\{CurrencyInterface,
    PurchasesModel};
use app\finders\PurchaseIdFinder;
use app\widgets\AdminOrderDataWidget;
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на обновление заказа
 */
class AdminOrderDetailChangeRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на отмену заказа
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::SAVE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(PurchaseIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $purchasesModel = $finder->find();
                        if (empty($purchasesModel)) {
                            throw new ErrorException($this->emptyError('purchasesModel'));
                        }
                        
                        $service = \Yii::$app->registry->get(NameGetSaveNameService::class, [
                            'name'=>$form->name
                        ]);
                        $namesModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(SurnameGetSaveSurnameService::class, [
                            'surname'=>$form->surname
                        ]);
                        $surnamesModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(PhoneGetSavePhoneService::class, [
                            'phone'=>$form->phone
                        ]);
                        $phonesModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(AddressGetSaveAddressService::class, [
                            'address'=>$form->address
                        ]);
                        $addressModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(CityGetSaveCityService::class, [
                            'city'=>$form->city
                        ]);
                        $citiesModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(CountryGetSaveCountryService::class, [
                            'country'=>$form->country
                        ]);
                        $countriesModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(PostcodeGetSavePostcodeService::class, [
                            'postcode'=>$form->postcode
                        ]);
                        $postcodesModel = $service->get();
                        
                        $purchasesModel->scenario = PurchasesModel::UPDATE_ADMIN;
                        $purchasesModel->id_name = $namesModel->id;
                        $purchasesModel->id_surname = $surnamesModel->id;
                        $purchasesModel->id_phone = $phonesModel->id;
                        $purchasesModel->id_address = $addressModel->id;
                        $purchasesModel->id_city = $citiesModel->id;
                        $purchasesModel->id_country = $countriesModel->id;
                        $purchasesModel->id_postcode = $postcodesModel->id;
                        $purchasesModel->quantity = $form->quantity;
                        $purchasesModel->id_color = $form->id_color;
                        $purchasesModel->id_size = $form->id_size;
                        $purchasesModel->id_delivery = $form->id_delivery;
                        $purchasesModel->id_payment = $form->id_payment;
                        
                        foreach (\Yii::$app->params['orderStatuses'] as $status) {
                            switch ($status) {
                                case $form->status:
                                    $purchasesModel->$status = true;
                                    break;
                                case 'received':
                                    break;
                                default:
                                    $purchasesModel->$status = false;
                            }
                        }
                        
                        if ($purchasesModel->validate() === false) {
                            throw new ErrorException($this->modelError($purchasesModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$purchasesModel
                        ]);
                        $saver->save();
                        
                        $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                            'key'=>HashHelper::createCurrencyKey()
                        ]);
                        $currentCurrencyModel = $service->get();
                        if (empty($currentCurrencyModel)) {
                            throw new ErrorException($this->emptyError('currentCurrencyModel'));
                        }
                        
                        $adminChangeOrderForm = new AdminChangeOrderForm();
                        
                        $adminOrderDataWidgetConfig = $this->adminOrderDataWidgetConfig($purchasesModel, $currentCurrencyModel, $adminChangeOrderForm);
                        $response = AdminOrderDataWidget::widget($adminOrderDataWidgetConfig);
                        
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
     * Возвращает массив конфигурации для виджета AdminOrderDataWidget
     * @params PurchasesModel $purchasesModel
     * @return array
     */
    private function adminOrderDataWidgetConfig(PurchasesModel $purchasesModel, CurrencyInterface $currentCurrencyModel, AbstractBaseForm $adminChangeOrderForm)
    {
        try {
            $dataArray = [];
            
            $dataArray['purchase'] = $purchasesModel;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['form'] = $adminChangeOrderForm;
            $dataArray['template'] = 'admin-order-data.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
