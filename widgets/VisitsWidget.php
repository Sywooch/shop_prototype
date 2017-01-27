<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с информацией о количестве посещений сегодня
 */
class VisitsWidget extends AbstractBaseWidget
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
            
            $renderArray['header'] = $this->header;
            
            if (!empty($this->visitors)) {
                ArrayHelper::multisort($this->visitors, 'date', SORT_DESC, SORT_NUMERIC);
                foreach ($this->visitors as $visit) {
                    $renderArray['visits'][] = sprintf('%s - %d %s', \Yii::$app->formatter->asDate($visit->date), $visit->counter, \Yii::t('base', 'visitors'));
                }
            } else {
                $renderArray['visitsEmpty'] = \Yii::t('base', 'Today no visits');
            }
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array VisitorsCounterModel свойству VisitsWidget::visitors
     * @param CurrencyModel $currency
     */
    public function setVisitors(array $visitors)
    {
        try {
            $this->visitors = $visitors;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает заголовок свойству VisitsWidget::header
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
     * Присваивает имя шаблона свойству VisitsWidget::template
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
