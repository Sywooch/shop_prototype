<?php

namespace app\removers;

use yii\base\ErrorException;
use app\removers\{AbstractBaseRemover,
    SessionRemoverInterface};
use app\helpers\SessionHelper;

/**
 * Удаляет данные из сессионного хранилища
 */
class SessionRemover extends AbstractBaseRemover implements SessionRemoverInterface
{
    /**
     * @var array ключи, данные которых будут удалены
     */
    private $keys = [];
    
    /**
     * Удаляет данные из сессионного хранилища
     * @return bool
     */
    public function remove()
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
     * Присваивает значение SessionRemover::keys
     * @param array $keys
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
