<?php

namespace app\cleaners;

use yii\base\ErrorException;
use app\cleaners\AbstractBaseCleaner;
use app\helpers\SessionHelper;

/**
 * Сохранаяет данные в сессионном хранилище
 */
class SessionCleaner extends AbstractBaseCleaner
{
    /**
     * @var array ключи, данные которых будут удалены
     */
    private $keys;
    
    /**
     * Удаляет данные из сессионного хранилища
     * @return bool
     */
    public function clean()
    {
        try {
            if (empty($this->keys)) {
                throw new ErrorException($this->emptyError('keys'));
            }
            
            SessionHelper::remove($this->keys);
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array SessionCleaner::keys
     */
    public function setKeys(array $keys)
    {
        try {
            $this->keys = $keys;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
