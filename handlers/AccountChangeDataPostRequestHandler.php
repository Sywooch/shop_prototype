<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\services\{AddressGetSaveAddressService,
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
 * Обрабатывает запрос, 
 * обновляющий данные пользователя
 */
class AccountChangeDataPostRequestHandler extends AbstractBaseHandler
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
            $usersModel = \Yii::$app->user->identity;
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
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
                        
                        $rawUsersModel = $usersModel;
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
                        
                        $response = AccountChangeDataSuccessWidget::widget(['template'=>'paragraph.twig']);
                        
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
