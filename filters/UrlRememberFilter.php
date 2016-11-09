<?php

namespace app\filters;

use yii\base\ActionFilter;
use yii\helpers\Url;
use app\exceptions\ExceptionsTrait;

/**
 * Запоминает текущий URL
 */
class UrlRememberFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * @var string имя, под которым будет сохранен URL
     */
    public $name;
    
    public function afterAction($action, $result)
    {
        try {
            if (!empty($this->name)) {
                Url::remember(Url::current(), $this->name);
            }
            
            return parent::afterAction($action, $result);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
