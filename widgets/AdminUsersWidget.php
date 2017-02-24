<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminUsersWidget extends AbstractBaseWidget
{
    /**
     * @var array UsersModel
     */
    private $users;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку с данными
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            if (!empty($this->users)) {
                foreach ($this->users as $user) {
                    $set = [];
                    $set['id'] = $user->id;
                    $set['email'] = $user->email->email;
                    $set['name'] = !empty($user->id_name) ? $user->name->name: null;
                    $set['surname'] = !empty($user->id_surname) ? $user->surname->surname: null;
                    $set['phone'] = !empty($user->id_phone) ? $user->phone->phone : null;
                    $set['address'] = !empty($user->id_address) ? $user->address->address : null;
                    $set['city'] = !empty($user->id_city) ? $user->city->city : null;
                    $set['country'] = !empty($user->id_country) ? $user->country->country : null;
                    $set['postcode'] = !empty($user->id_postcode) ? $user->postcode->postcode : null;
                    $set['orders'] = count($user->orders);
                    
                    $set['href'] = Url::to(['/admin/user-detail', \Yii::$app->params['userId']=>$user->id]);
                    $set['hrefText'] = \Yii::t('base', 'Change');
                    
                    $renderArray['users'][] = $set;
                }
                
                $renderArray['emailHeader'] = \Yii::t('base', 'Email');
                $renderArray['nameHeader'] = \Yii::t('base', 'Name');
                $renderArray['surnameHeader'] = \Yii::t('base', 'Surname');
                $renderArray['phoneHeader'] = \Yii::t('base', 'Phone');
                $renderArray['addressHeader'] = \Yii::t('base', 'Address');
                $renderArray['cityHeader'] = \Yii::t('base', 'City');
                $renderArray['countryHeader'] = \Yii::t('base', 'Country');
                $renderArray['postcodeHeader'] = \Yii::t('base', 'Postcode');
                $renderArray['ordersHeader'] = \Yii::t('base', 'Orders');
            } else {
                $renderArray['usersEmpty'] = \Yii::t('base', 'No users');
            }
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminUsersWidget::users
     * @param array $users
     */
    public function setUsers(array $users)
    {
        try {
            $this->users = $users;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminUsersWidget::header
     * @param string $header
     */
    public function setHeader(string $header)
    {
        try {
            $this->header = $header;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminUsersWidget::template
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        try {
            $this->template = $template;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
