<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\{Html,
    Url};
use app\exceptions\ExceptionsTrait;

/**
 * Формирует HTML строку с данными пагинации
 */
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
    public $activePage = 'active';
    /**
     * @var array массив HTML аттрибутов, которые будут применены к тегу-контейнеру
     */
     public $options = ['class'=>'pagination'];
    /**
     * @var string имя тега-контейнера
     * - <ul> маркированный список
     * - <ol> нумерованный
     */
    public $tag = 'ul';
    /**
     * @var string имя дочернего для тега-контейнера тега, обрамляющего ссылки на страницы
     */
    public $childTag = 'il';
    /**
     * @var string символ, разделяющий ссылки
     */
    public $separator = ' / ';
    /**
     * @var int общее количество ссылок на страницы, например, 
     * значение 5 создает: указатель на текущую страницу, а также 
     * 2 ссылки на предыдущие и 2 ссылки на следующие за текущей страницы
     */
    public $pageRange = 5;
    /**
     * @var bool флаг, определяющий, нужно ли добавлять ссылки на первую и последнюю страницы
     */
    public $edges = true;
    /**
     * @var int минимальный номер для предыдущих страниц
     */
    private $_prevMin;
    /**
     * @var int максимальный номер для следующих страниц
     */
    private $_nextMax;
    /**
     * @var array массив ссылок на страницы, обернутых в тег $this->childTag
     */
    private $_tags = [];
    /**
     * @var int номер текущей страницы
     */
    private $_pagePointer;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->paginator)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'yii\data\Pagination']));
            }
            
            $this->pageRange = ceil($this->pageRange);
            
            $this->pageRange = $this->pageRange < 3 ? 3 : $this->pageRange;
            
            if ($this->pageRange % 2 === 0) {
                ++$this->pageRange;
            }
            
            $this->_pagePointer = \Yii::$app->request->get(\Yii::$app->params['pagePointer']) ?? 1;
            
            if ($this->_pagePointer > $this->paginator->pageCount) {
                $this->_pagePointer = $this->paginator->pageCount;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует HTML строку пагинации
     * @return string
     */
    public function run(): string
    {
        try {
            if ($this->paginator->pageCount >= 2) {
                
                $range = $this->getRange();
                
                foreach ($range as $number) {
                    if ($this->_pagePointer == $number) {
                        $this->_tags[] = Html::tag($this->childTag, $number, ['class'=>$this->activePage]);
                        continue;
                    }
                    $this->_tags[] = Html::tag($this->childTag, Html::a($number, Url::current([\Yii::$app->params['pagePointer']=>($number == 1) ? null : $number])));
                }
                
                if ($this->edges) {
                    array_unshift($this->_tags, Html::tag($this->childTag, Html::a(\Yii::t('base', 'First'), Url::current([\Yii::$app->params['pagePointer']=>null]))));
                    array_push($this->_tags, Html::tag($this->childTag, Html::a(\Yii::t('base', 'Last'), Url::current([\Yii::$app->params['pagePointer']=>$this->paginator->pageCount]))));
                }
            }
            
            return !empty($this->_tags) ? Html::tag($this->tag, implode(Html::tag($this->childTag, $this->separator), $this->_tags), $this->options) : '';
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует диапазон ссылок на страницы в зависимости от номера текущей страницы
     * @return array
     */
    private function getRange(): array
    {
        try {
            $currentPage = $this->paginator->page + 1;
            $aroundPages = floor(($this->pageRange - 1) / 2);
            
            $this->_prevMin = $currentPage - $aroundPages;
            $this->checkMinPage();
            
            $this->_nextMax = $currentPage + $aroundPages;
            $this->checkMaxPage();
            
            if ($this->pageRange > count(range($this->_prevMin, $this->_nextMax))) {
                if ($this->_prevMin == 1) {
                    $this->scale(true);
                    $this->checkMaxPage();
                } elseif ($this->_nextMax == $this->paginator->pageCount) {
                    $this->scale(false);
                    $this->checkMinPage();
                }
            }
            
            return range($this->_prevMin, $this->_nextMax);
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Увеличивает максимальный номер следующих страниц, 
     * или уменьшает минимальный номер предыдущих страниц, 
     * @param bool $direction задает направление изменений: 
     * - true инкремент максимального номера
     * - false декремент минимального номера
     */
    private function scale(bool $direction)
    {
        try {
            while ($this->pageRange > count(range($this->_prevMin, $this->_nextMax))) {
                $direction ? ++$this->_nextMax : --$this->_prevMin;
            }
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Устанавливает минимальный номер для предыдущих страниц
     */
    private function checkMinPage()
    {
        try {
            if ($this->_prevMin < 1) {
                $this->_prevMin = 1;
            }
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Устанавливает максимальный номер для следующих страниц
     */
    private function checkMaxPage()
    {
        try {
            if ($this->_nextMax > $this->paginator->pageCount) {
                $this->_nextMax = $this->paginator->pageCount;
            }
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
