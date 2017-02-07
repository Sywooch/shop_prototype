<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html};
use app\widgets\AbstractBaseWidget;
use app\helpers\DateHelper;
use app\models\VisitorsCounterInterface;

/**
 * Формирует HTML строку с информацией о количестве посещений сегодня
 */
class VisitsMinimalWidget extends AbstractBaseWidget
{
    /**
     * @var int количество посещений
     */
    private $visits;
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
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            if (!empty($this->visits)) {
                $renderArray['text'] = sprintf('%s:', \Yii::t('base', 'Visits'));
                $renderArray['visits'] = $this->visits;
            } else {
                $renderArray['visitsEmpty'] = \Yii::t('base', 'Today no visits');
            }
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает количество посещений свойству VisitsMinimalWidget::visits
     * @param int $visits
     */
    public function setVisits(int $visits)
    {
        try {
            $this->visits = $visits;
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
