<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\{Html,
    Url};
use app\exceptions\ExceptionsTrait;
use app\helpers\SessionHelper;
use app\interfaces\SearchFilterInterface;

/**
 * Формирует HTML строку с информацией о текущем статусе аутентификации
 */
class UserInfoWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object BaseFIltersInterface для поиска данных по запросу
     */
    private $filterClass;
    /**
     * @var string сценарий поиска
     */
    public $filterScenario;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с информацией о текущем пользователе
     * @return string
     */
    public function run()
    {
        try {
            if (\Yii::$app->user->isGuest == false) {
                if ($user = $this->filterClass->search($this->filterScenario)) {
                    $authenticated = true;
                }
            }
            
            return $this->render($this->view, ['user'=>$user ?? \Yii::t('base', 'Guest'), 'authenticated'=>$authenticated ?? false]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function setFilterClass(SearchFilterInterface $filterClass)
    {
        try {
            $this->filterClass = $filterClass;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
