<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    AddressGetSaveAddressService,
    CityGetSaveCityService,
    CountryGetSaveCountryService,
    EmailGetSaveEmailService,
    NameGetSaveNameService,
    PhoneGetSavePhoneService,
    PostcodeGetSavePostcodeService,
    SurnameGetSaveSurnameService};
use app\forms\UserUpdateForm;
use app\models\UsersModel;
use app\savers\ModelSaver;
use app\widgets\AccountChangeDataSuccessWidget;

/**
 * Обновляет данные пользователя
 */
class AccountChangeDataPostService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на обновление данных пользователя
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new UserUpdateForm(['scenario'=>UserUpdateForm::UPDATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
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
                        
                        $rawUsersModel = \Yii::$app->user->identity;
                        $rawUsersModel->scenario = UsersModel::UPDATE;
                        $rawUsersModel->id_name = $namesModel->id;
                        $rawUsersModel->id_surname = $surnamesModel->id;
                        $rawUsersModel->id_phone = $phonesModel->id;
                        $rawUsersModel->id_address = $addressModel->id;
                        $rawUsersModel->id_city = $citiesModel->id;
                        $rawUsersModel->id_country = $countriesModel->id;
                        $rawUsersModel->id_postcode = $postcodesModel->id;
                        if ($rawUsersModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawUsersModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawUsersModel
                        ]);
                        $saver->save();
                        
                        $transaction->commit();
                        
                        return AccountChangeDataSuccessWidget::widget(['template'=>'account-change-data-success.twig']);
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
