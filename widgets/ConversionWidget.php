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
    public $purchases;
    /**
     * @var int количество посещений сегодня
     */
    public $visits;
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
            
            $purchases = $this->purchases ?? 0;
            $visits = $this->visits ?? 0;
            
            if ((int) $purchases !== 0 && (int) $visits !== 0) {
                $conversion = round(($purchases / $visits) * 100, 2);
            } else {
                $conversion = 0;
            }
            
            $renderArray['conversion'] = sprintf('%s: %s%%', \Yii::t('base', 'Conversion today'), $conversion);
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
