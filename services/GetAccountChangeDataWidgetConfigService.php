<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\forms\UserUpdateForm;

/**
 * Возвращает массив конфигурации для виджета AccountChangeDataWidget
 */
class GetAccountChangeDataWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AccountChangeDataWidget
     */
    private $accountChangeDataWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->accountChangeDataWidgetArray)) {
                $dataArray = [];
                
                $form = new UserUpdateForm(['scenario'=>UserUpdateForm::UPDATE]);
                
                $user = \Yii::$app->user->identity;
                
                $form->name = !empty($user->id_name) ? $user->name->name : null;
                $form->surname = !empty($user->id_surname) ? $user->surname->surname: null;
                $form->phone = !empty($user->id_phone) ? $user->phone->phone : null;
                $form->address = !empty($user->id_address) ? $user->address->address : null;
                $form->city = !empty($user->id_city) ? $user->city->city : null;
                $form->country = !empty($user->id_country) ? $user->country->country : null;
                $form->postcode = !empty($user->id_postcode) ? $user->postcode->postcode : null;
                
                $dataArray['form'] = $form;
                $dataArray['view'] = 'account-change-data-form.twig';
                
                $this->accountChangeDataWidgetArray = $dataArray;
            }
            
            return $this->accountChangeDataWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
