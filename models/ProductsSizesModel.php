<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;
use app\models\{SizesModel,
    ProductsModel};

/**
 * Представляет данные таблицы products_sizes
 */
class ProductsSizesModel extends AbstractBaseModel
{
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'products_sizes';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Выполняет пакетное сохранение
     * @param object $productsModel экземпляр ProductsModel
     * @param object $sizesModel экземпляр SizesModel
     * @return int
     */
    public static function batchInsert(ProductsModel $productsModel, SizesModel $sizesModel): int
    {
        try {
            $counter = 0;
            
            if (!empty($productsModel->id) && is_array($sizesModel->id) && !empty($sizesModel->id)) {
                $toRecord = [];
                foreach ($sizesModel->id as $sizeId) {
                    $toRecord[] = [$productsModel->id, $sizeId];
                    ++$counter;
                }
                if (!\Yii::$app->db->createCommand()->batchInsert('{{products_sizes}}', ['[[id_product]]', '[[id_size]]'], $toRecord)->execute()) {
                    throw new ErrorException(ExceptionsTrait::methodError('ProductsSizesModel::batchInsert'));
                }
            }
            
            return $counter;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
