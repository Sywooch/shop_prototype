<?php

namespace app\actions;

use yii\base\Action;
use app\exceptions\ExceptionsTrait;

/**
 * Базовый класс action-классов
 */
abstract class AbstractBaseAction extends Action
{
    use ExceptionsTrait;
    
    /**
     * @var array массив данных для передачи в представление
     */
    protected $_renderArray = [];
    
    public function init()
    {
        try {
            parent::init();
            
            if (!empty($this->additions)) {
                $this->loadAdditions();
            }
            
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Загружает дополнительные данные, необходимые для рендеринга страницы
     */
    protected function loadAdditions()
    {
        try {
            foreach ($this->additions as $name=>$addition) {
                if (is_array($addition) && array_key_exists('class', $addition)) {
                    $addition = \Yii::createObject($addition);
                }
                $this->_renderArray[$name] = $addition;
            }
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
