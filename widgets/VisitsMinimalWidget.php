<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html};
use app\widgets\AbstractBaseWidget;
use app\helpers\DateHelper;

/**
 * Формирует HTML строку с информацией о количестве посещений сегодня
 */
class VisitsMinimalWidget extends AbstractBaseWidget
{
    /**
     * @var array VisitorsCounterModel
     */
    private $visitors;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку с информацией об отсутствии результатов поиска
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            //$renderArray['header'] = $this->header;
            
            if (!empty($this->visitors)) {
                $renderArray['visitors'] = sprintf('%s: %s', Html::tag('strong', \Yii::t('base', 'Visits')), $this->visitors ?? 0);
            } else {
                $renderArray['visitorsEmpty'] = \Yii::t('base', 'Today no visits');
            }
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает количество посетителей свойству VisitsMinimalWidget::visitors
     * @param int $visitors
     */
    public function setVisitors(int $visitors)
    {
        try {
            $this->visitors = $visitors;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает заголовок свойству VisitsMinimalWidget::header
     * @param string $header
     */
    public function setHeader(string $header)
    {
        try {
            $this->header = $header;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству VisitsMinimalWidget::template
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        try {
            $this->template = $template;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
