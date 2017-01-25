<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные поля для выбора статуса заказа
 */
class OrderStatusesFinder extends AbstractBaseFinder
{
    /**
     * @var array
     */
    private $storage = null;
    
    /**
     * Возвращает данные
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                 foreach (\Yii::$app->params['orderStatuses'] as $status) {
                    $this->storage[$status] = \Yii::t('base', mb_convert_case($status, MB_CASE_TITLE));
                }
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
