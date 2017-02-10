<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\forms\UserUpdateForm;
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
                
                $this->dataArray['accountChangeDataWidgetConfig'] = $this->accountChangeDataWidgetConfig($usersModel);
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountChangeDataWidget
     * @param UsersModel $usersModel
     * @return array
     */
    private function accountChangeDataWidgetConfig(UsersModel $usersModel): array
    {
        try {
            $form = new UserUpdateForm(['scenario'=>UserUpdateForm::UPDATE]);
            
            $form->name = !empty($usersModel->id_name) ? $usersModel->name->name : null;
            $form->surname = !empty($usersModel->id_surname) ? $usersModel->surname->surname: null;
            $form->phone = !empty($usersModel->id_phone) ? $usersModel->phone->phone : null;
            $form->address = !empty($usersModel->id_address) ? $usersModel->address->address : null;
            $form->city = !empty($usersModel->id_city) ? $usersModel->city->city : null;
            $form->country = !empty($usersModel->id_country) ? $usersModel->country->country : null;
            $form->postcode = !empty($usersModel->id_postcode) ? $usersModel->postcode->postcode : null;
            
            $dataArray = [];
            
            $dataArray['form'] = $form;
            $dataArray['header'] = \Yii::t('base', 'Change data');
            $dataArray['template'] = 'account-change-data-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
