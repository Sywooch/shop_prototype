<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\{Html,
    Url};
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с информацией о текущем статусе аутентификации,
 * кнопки Login, Registration, Logout
 */
class UserInfoWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var array массив результирующих строк
     */
    private $_result = [];
    
    /**
     * Конструирует HTML строку с информацией и текущем пользователе
     * @return string
     */
    public function run(): string
    {
        try {
            if (\Yii::$app->user->isGuest) {
                $user = \Yii::t('base', 'Guest');
                
                $login = Html::a(\Yii::t('base', 'Login'), ['/user/login']);
                $registartion = Html::a(\Yii::t('base', 'Registration'), ['/user/registration']);
                $this->_result[] = Html::tag('p', $login . ' ' . $registartion);
            } else {
                $user = \Yii::$app->user->identity->name ? \Yii::$app->user->identity->name : \Yii::$app->user->identity->emails->email;
                
                $form = Html::beginForm(['/user/logout'], 'POST');
                $form .= Html::input('hidden', 'userId', \Yii::$app->user->id);
                $form .= Html::submitButton(\Yii::t('base', 'Logout'));
                $form .= Html::endForm();
                $this->_result[] = $form;
            }
            
            array_unshift($this->_result, Html::tag('p', \Yii::t('base', "Hello, {placeholder}!", ['placeholder'=>Html::encode($user)])));
            
            return implode('', $this->_result);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
