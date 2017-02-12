<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\forms\{AbstractBaseForm,
    UserUpdateForm};
use app\models\UsersModel;

/**
 * Обрабатывает запрос на получение 
 * формы редактирования данных клиента
 */
class AccountChangeDataRequestHandler extends AbstractBaseHandler
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы с настройками аккаунта
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $usersModel = \Yii::$app->user->identity;
                
                $userUpdateForm = new UserUpdateForm(['scenario'=>UserUpdateForm::UPDATE]);
                
                $dataArray = [];
                
                $dataArray['accountChangeDataWidgetConfig'] = $this->accountChangeDataWidgetConfig($userUpdateForm, $usersModel);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountChangeDataWidget
     * @param AbstractBaseForm $userUpdateForm
     * @param UsersModel $usersModel
     * @return array
     */
    private function accountChangeDataWidgetConfig(AbstractBaseForm $userUpdateForm, UsersModel $usersModel): array
    {
        try {
            $userUpdateForm->name = !empty($usersModel->id_name) ? $usersModel->name->name : null;
            $userUpdateForm->surname = !empty($usersModel->id_surname) ? $usersModel->surname->surname: null;
            $userUpdateForm->phone = !empty($usersModel->id_phone) ? $usersModel->phone->phone : null;
            $userUpdateForm->address = !empty($usersModel->id_address) ? $usersModel->address->address : null;
            $userUpdateForm->city = !empty($usersModel->id_city) ? $usersModel->city->city : null;
            $userUpdateForm->country = !empty($usersModel->id_country) ? $usersModel->country->country : null;
            $userUpdateForm->postcode = !empty($usersModel->id_postcode) ? $usersModel->postcode->postcode : null;
            
            $dataArray = [];
            
            $dataArray['form'] = $userUpdateForm;
            $dataArray['header'] = \Yii::t('base', 'Change data');
            $dataArray['template'] = 'account-change-data-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
