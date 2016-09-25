<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\{Html,
    Url};
use app\exceptions\ExceptionsTrait;

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
                $this->_result[] = Html::tag('p', Html::a(\Yii::t('base', 'Login'), ['/user/login']));
            } else {
                $user = \Yii::$app->user->identity->name ? \Yii::$app->user->identity->name : \Yii::$app->user->identity->emails->email;
                $this->_result[] = Html::tag('p', Html::a(\Yii::t('base', 'Logout'), ['/user/logout']));
            }
            
            array_unshift($this->_result, Html::tag('p', \Yii::t('base', "Hello, {placeholder}!", ['placeholder'=>Html::encode($user)])));
            
            return implode('', $this->_result);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
