<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    AddressGetSaveAddressService,
    CityGetSaveCityService,
    CountryGetSaveCountryService,
    NameGetSaveNameService,
    PhoneGetSavePhoneService,
    PostcodeGetSavePostcodeService,
    SurnameGetSaveSurnameService};
use app\forms\AdminChangeOrderForm;
use app\savers\ModelSaver;
use app\models\PurchasesModel;
use app\finders\PurchaseIdFinder;
use app\widgets\AdminOrderDataWidget;

/**
 * Отменят заказ
 */
class AdminOrderDetailChangeService extends AbstractBaseService
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
                        $finder = \Yii::$app->registry->get(PurchaseIdFinder::class, ['id'=>$form->id]);
                        $purchasesModel = $finder->find();
                        
                        if (empty($purchasesModel)) {
                            throw new ErrorException($this->emptyError('purchasesModel'));
                        }
                        
                        $service = \Yii::$app->registry->get(NameGetSaveNameService::class);
                        $namesModel = $service->handle(['name'=>$form->name]);
                        
                        $service = \Yii::$app->registry->get(SurnameGetSaveSurnameService::class);
                        $surnamesModel = $service->handle(['surname'=>$form->surname]);
                        
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
                        
                        $service = \Yii::$app->registry->get(GetAdminOrderDataWidgetConfigService::class);
                        $adminOrderDataWidgetConfig = $service->handle(['id'=>$form->id]);
                        
                        $transaction->commit();
                        
                        return AdminOrderDataWidget::widget($adminOrderDataWidgetConfig);
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
