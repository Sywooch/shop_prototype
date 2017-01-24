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
    public $visitors;
    /**
     * @var string заголовок
     */
    public $header;
    /**
     * @var string имя шаблона
     */
    public $view;
    
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
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
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
            
            return $this->render($this->view, $renderArray);
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
}
