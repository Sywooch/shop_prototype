<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\collections\PaginationInterface;

/**
 * Формирует HTML строку с данными пагинации
 */
class PaginationWidget extends AbstractBaseWidget
{
    /**
     * @var object PaginationInterface
     */
    private $pagination;
    /**
     * @var string CSS класс активного пункта меню
     */
    public $activePage = 'active';
    /**
     * @var string имя тега-контейнера, обрамляющего ссылки на страницы
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
    private $prevMin;
    /**
     * @var int максимальный номер для следующих страниц
     */
    private $nextMax;
    /**
     * @var array массив ссылок на страницы, обернутых в тег $this::childTag
     */
    private $tags = [];
    /**
     * @var int номер текущей страницы
     */
    private $pagePointer;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку пагинации
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->pagination)) {
                throw new ErrorException($this->emptyError('pagination'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            if ($this->pagination->pageCount >= 2) {
                
                $this->settings();
                
                $range = $this->getRange();
                
                foreach ($range as $number) {
                    if ($this->pagePointer === (int) $number) {
                        $this->tags[] = Html::tag($this->childTag, $number, ['class'=>$this->activePage]);
                        continue;
                    }
                    $this->tags[] = Html::tag($this->childTag, Html::a($number, Url::current([\Yii::$app->params['pagePointer']=>($number == 1) ? null : $number])));
                }
                
                if ($this->edges) {
                    array_unshift($this->tags, Html::tag($this->childTag, Html::a(\Yii::t('base', 'First'), Url::current([\Yii::$app->params['pagePointer']=>null]))));
                    array_push($this->tags, Html::tag($this->childTag, Html::a(\Yii::t('base', 'Last'), Url::current([\Yii::$app->params['pagePointer']=>$this->pagination->pageCount]))));
                }
            }
            
            $renderArray = [];
            
            $renderArray['pagination'] = !empty($this->tags) ? implode(Html::tag($this->childTag, $this->separator), $this->tags) : '';
            
            return $this->render($this->view, $renderArray);
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Нормализует значения свойств
     */
    private function settings()
    {
        try {
            if (empty($this->pageRange) || $this->pageRange < 3) {
                $this->pageRange = 3;
            }
            
            $this->pageRange = (int) ceil($this->pageRange);
            
            if ((int) $this->pageRange % 2 === 0) {
                ++$this->pageRange;
            }
            
            $this->pagePointer = $this->pagination->page + 1;
        } catch (\Throwable $t) {
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
            $aroundPages = floor(($this->pageRange - 1) / 2);
            
            $this->prevMin = $this->pagePointer - $aroundPages;
            $this->checkMinPage();
            
            $this->nextMax = $this->pagePointer + $aroundPages;
            $this->checkMaxPage();
            
            if ($this->pageRange > count(range($this->prevMin, $this->nextMax))) {
                if ((int) $this->prevMin === 1) {
                    $this->scale(true);
                    $this->checkMaxPage();
                } elseif ((int) $this->nextMax === (int) $this->pagination->pageCount) {
                    $this->scale(false);
                    $this->checkMinPage();
                }
            }
            
            return range($this->prevMin, $this->nextMax);
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
            while ($this->pageRange > count(range($this->prevMin, $this->nextMax))) {
                $direction ? ++$this->nextMax : --$this->prevMin;
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
            if ($this->prevMin < 1) {
                $this->prevMin = 1;
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
            if ($this->nextMax > $this->pagination->pageCount) {
                $this->nextMax = $this->pagination->pageCount;
            }
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет объект пагинации
     * @param object $pagination PaginationInterface
     */
    public function setPagination(PaginationInterface $pagination)
    {
        try {
            $this->pagination = $pagination;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
