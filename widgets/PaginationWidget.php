<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\{Html,
    Url};
use app\exceptions\ExceptionsTrait;

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
     * 2 ссылки на предыдущие и 2 ссылки на следующие страницы за текущей
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
    private $_tags = array();
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->paginator)) {
                throw new ErrorException(\Yii::t('base/errors', 'Not Evaluated {placeholder}!', ['placeholder'=>'$paginator']));
            }
            
            $this->pageRange = ceil($this->pageRange);
            if ($this->pageRange % 2 === 0) {
                ++$this->pageRange;
            }
            $this->pageRange = $this->pageRange < 3 ? 3 : $this->pageRange;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Конструирует HTML строку пагинации
     * @return string
     */
    public function run()
    {
        try {
            if ($this->paginator->pageCount < 2) {
                return '';
            }
            
            $range = $this->getRange();
            
            if (!is_array($range) || empty($range)) {
                throw new ErrorException(\Yii::t('base/errors', 'Incorrect data!'));
            }
            
            foreach ($range as $number) {
                if ($number == 1) {
                    $number = null;
                }
                $url = Url::current([\Yii::$app->params['pagePointer']=>$number]);
                if (Url::current() == $url || (\Yii::$app->request->get(\Yii::$app->params['pagePointer']) > $this->paginator->pageCount && $number == $this->paginator->pageCount)) {
                    $this->_tags[] = Html::tag($this->childTag, $number ?? 1, ['class'=>$this->activePageCssClass]);
                    continue;
                }
                $this->_tags[] = Html::tag($this->childTag, Html::a($number ?? 1, $url));
            }
            
            if ($this->edges) {
                array_unshift($this->_tags, Html::tag($this->childTag, Html::a(\Yii::t('base', 'First'), Url::current([\Yii::$app->params['pagePointer']=>null]))));
                array_push($this->_tags, Html::tag($this->childTag, Html::a(\Yii::t('base', 'Last'), Url::current([\Yii::$app->params['pagePointer']=>$this->paginator->pageCount]))));
            }
            
            return Html::tag($this->tag, implode(Html::tag($this->childTag, $this->separator), $this->_tags), $this->options);
        } catch(\Exception $e) {
            $this->throwException($e, __METHOD__);
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
            if (!$this->checkMinPage()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
            }
            
            $this->_nextMax = $currentPage + $aroundPages;
            if (!$this->checkMaxPage()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
            }
            
            if (count(range($this->_prevMin, $this->_nextMax)) < $this->pageRange) {
                if ($this->_prevMin == 1) {
                    if (!$this->scale('up')) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
                    }
                    if (!$this->checkMaxPage()) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
                    }
                } elseif ($this->_nextMax == $this->paginator->pageCount) {
                    if (!$this->scale('down')) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
                    }
                    if (!$this->checkMinPage()) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
                    }
                }
            }
            
            return range($this->_prevMin, $this->_nextMax);
        } catch(\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Увеличивает максимальный номер следующих страниц, 
     * или уменьшает минимальный номер предыдущих страниц, 
     * если диапазон страниц меньше ожидаемого
     * @param string $direction задает направление изменений: 
     * - up инкремент максимального номера
     * - down декремент минимального номера
     */
    private function scale($direction)
    {
        try {
            if (empty($direction) || !in_array($direction, ['up', 'down'], true)) {
                throw new ErrorException(\Yii::t('base/errors', 'Incorrect data!'));
            }
            
            for ($i = 0; $i <= $this->pageRange - count(range($this->_prevMin, $this->_nextMax)); ++$i) {
                $direction == 'up' ? ++$this->_nextMax : --$this->_prevMin;
            }
            
            return true;
        } catch(\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Устанавливает минимальный номер для предыдущих страниц, 
     * устанавливает его равным 1, если полученное в результате вычислений значение меньше 1
     * @return bool
     */
    private function checkMinPage()
    {
        try {
            if ($this->_prevMin < 1) {
                $this->_prevMin = 1;
            }
            
            return true;
        } catch(\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Устанавливает максимальный номер для следующих страниц, 
     * устанавливает его равным $this->paginator->pageCount, 
     * если полученное в результате вычислений значение больше $this->paginator->pageCount
     * @return bool
     */
    private function checkMaxPage()
    {
        try {
            if ($this->_nextMax > $this->paginator->pageCount) {
                $this->_nextMax = $this->paginator->pageCount;
            }
            
            return true;
        } catch(\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
