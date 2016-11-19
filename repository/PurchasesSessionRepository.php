<?php

namespace app\repository;

use yii\base\ErrorException;
use app\repository\{AbstractBaseRepository,
    GetGroupRepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\helpers\SessionHelper;
use app\models\{PurchasesCompositInterface,
    PurchasesModel};

class PurchasesSessionRepository extends AbstractBaseRepository implements GetGroupRepositoryInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object PurchasesCompositInterface
     */
    private $items;
    
    /**
     * Возвращает PurchasesCompositInterface, содержащий коллекцию PurchasesModel, 
     * представляющих товары в корзине
     * @param string $key ключ для поиска данных в сессионном хранилище
     * @return PurchasesCompositInterface или null
     */
    public function getGroup($key)
    {
        try {
            if (empty($this->items)) {
                throw new ErrorException(ExceptionsTrait::emptyError('items'));
            }
            
            if ($this->items->isEmpty()) {
                $data = SessionHelper::read($key);
                if (!empty($data)) {
                    foreach ($data as $purchase) {
                        $this->items->add(\Yii::createObject(array_merge(['class'=>PurchasesModel::class], $purchase)));
                    }
                }
            }
            
            return !empty($this->items) ? $this->items : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchasesCompositInterface свойству PurchasesSessionRepository::items
     * @param object $composit PurchasesCompositInterface
     */
    public function setItems(PurchasesCompositInterface $composit)
    {
        try {
            $this->items = $composit;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
