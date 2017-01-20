<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\AbstractBaseWidget;
use app\models\UsersModel;

/**
 * Формирует HTML строку с текущими контактными данными
 */
class AccountContactsWidget extends AbstractBaseWidget
{
    /**
     * @var UsersModel
     */
    private $user;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с данными
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->user)) {
                throw new ErrorException($this->emptyError('user'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['userHeader'] = \Yii::t('base', 'Current contact details');
            
            $set = [];
            
            $set['email'] = !empty($this->user->id_email) ? $this->user->email->email : null;
            $set['emailHeader'] = \Yii::t('base', 'Email');
            $set['name'] = !empty($this->user->id_name) ? $this->user->name->name: null;
            $set['nameHeader'] = \Yii::t('base', 'Name');
            $set['surname'] = !empty($this->user->id_surname) ? $this->user->surname->surname: null;
            $set['surnameHeader'] = \Yii::t('base', 'Surname');
            $set['phone'] = !empty($this->user->id_phone) ? $this->user->phone->phone : null;
            $set['phoneHeader'] = \Yii::t('base', 'Phone');
            $set['address'] = !empty($this->user->id_address) ? $this->user->address->address : null;
            $set['addressHeader'] = \Yii::t('base', 'Address');
            $set['city'] = !empty($this->user->id_city) ? $this->user->city->city : null;
            $set['cityHeader'] = \Yii::t('base', 'City');
            $set['country'] = !empty($this->user->id_country) ? $this->user->country->country : null;
            $set['countryHeader'] = \Yii::t('base', 'Country');
            $set['postcode'] = !empty($this->user->id_postcode) ? $this->user->postcode->postcode : null;
            $set['postcodeHeader'] = \Yii::t('base', 'Postcode');
            
            $renderArray['user'] = $set;
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает UsersModel свойству AccountContactsWidget::user
     * @param UsersModel $user
     */
    public function setUser(UsersModel $user)
    {
        try {
            $this->user = $user;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
