<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Html;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с данными о количестве заказов
 */
class AdminTodayOrdersMinimalWidget extends AbstractBaseWidget
{
    /**
     * @var int количество заказов
     */
    private $purchases;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку с данными
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
            
            if (!empty($this->purchases)) {
                $renderArray['purchases'] = sprintf('%s: %s', Html::tag('strong', \Yii::t('base', 'Orders')), $this->purchases ?? 0);
            } else {
                $renderArray['purchasesEmpty'] = \Yii::t('base', 'Today no orders');
            }
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает количество заказов свойству AdminTodayOrdersMinimalWidget::purchases
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
     * Присваивает заголовок свойству AdminTodayOrdersMinimalWidget::header
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
     * Присваивает имя шаблона свойству AdminTodayOrdersMinimalWidget::template
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
