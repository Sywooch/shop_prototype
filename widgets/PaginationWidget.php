<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\{Html,
    Url};
use app\traits\ExceptionsTrait;

class PaginationWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object yii\data\Pagination
     */
    public $paginator;
    /**
     * @var string CSS class для активного пункта меню
     */
    public $activePageCssClass = 'active';
    /**
     * @var array массив HTML аттрибутов, которые будут применены к тегу-контейнеру
     */
     public $options = ['class'=>'pagination'];
     /**
     * @var string имя тега-контейнера
     */
    public $tag = 'ul';
    
    public function init()
    {
        parent::init();
        
        if (empty($this->paginator)) {
            throw new ErrorException('Не задан объект Pagination!');
        }
    }
    
    /**
     * Конструирует HTML строку пагинации
     * @return string
     */
    public function run()
    {
        try {
            $range = range(1, $this->paginator->pageCount);
            
            $result = [];
            
            foreach ($range as $item) {
                if ($item == 1) {
                    $item = null;
                }
                $result[] = Html::tag('li', Html::a($item ?? 1, Url::current([\Yii::$app->params['pagePointer']=>$item])), Url::current() == $url ? ['class'=>$this->activePageCssClass] : []);
            }
            
            return Html::tag($this->tag, implode('', $result), $this->options);
        } catch(\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
