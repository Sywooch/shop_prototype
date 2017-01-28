<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с информацией о количестве посещений сегодня
 */
class ConversionWidget extends AbstractBaseWidget
{
     /**
     * @var int количество покупок сегодня
     */
    private $purchases;
    /**
     * @var int количество посещений сегодня
     */
    private $visits;
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
            
            $purchases = $this->purchases ?? 0;
            $visits = $this->visits ?? 0;
            
            if ((int) $purchases !== 0 && (int) $visits !== 0) {
                $conversion = round(($purchases / $visits) * 100, 2);
            } else {
                $conversion = 0;
            }
            
            $renderArray['conversion'] = sprintf('%s: %s%%', \Yii::t('base', 'Conversion today'), $conversion);
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает количество покупок сегодня свойству ConversionWidget::purchases
     * @param int $purchases
     */
    public function setPurchases(int $purchases)
    {
        try {
            $this->purchases = $purchases;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает количество посещений сегодня свойству ConversionWidget::visits
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
     * Присваивает заголовок свойству ConversionWidget::header
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
     * Присваивает имя шаблона свойству ConversionWidget::template
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