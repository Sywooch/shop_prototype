<?php

namespace app\updaters;

use yii\base\ErrorException;
use app\updaters\{AbstractBaseUpdater,
    UpdaterInterface};
use app\models\CurrencyModel;

/**
 * Удаляет данные из СУБД
 */
class CurrencyMainUpdater extends AbstractBaseUpdater implements UpdaterInterface
{
    /**
     * Обновляет данные
     * @return int
     */
    public function update()
    {
        try {
            $result = CurrencyModel::updateAll(['[[currency.main]]'=>0]);
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
