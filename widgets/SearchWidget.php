<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\{Html,
    Url};
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с формой поиска
 */
class SearchWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var array массив результирующих строк
     */
    private $_result = [];
    
    /**
     * Конструирует HTML строку с формой поиска
     * @return string
     */
    public function run()
    {
        try {
            $this->_result[] = Html::beginForm('/search', 'GET', ['name'=>'search-form', 'id'=>'search-form']);
            $this->_result[] = Html::textInput(\Yii::$app->params['searchKey'], \Yii::$app->request->get(\Yii::$app->params['searchKey']), ['size'=>40]);
            $this->_result[] = Html::submitButton(\Yii::t('base', 'Search'));
            $this->_result[] = Html::endForm();
            
            return !empty($this->_result) ? Html::tag('p', implode('', $this->_result), ['id'=>'search-form']) : '';
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
