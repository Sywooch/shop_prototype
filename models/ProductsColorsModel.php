<?php

namespace app\models;

use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;
use app\models\{ColorsModel,
    ProductsModel};

/**
 * Представляет данные таблицы products_colors
 */
class ProductsColorsModel extends AbstractBaseModel
{
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'products_colors';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Выполняет пакетное сохранение
     * @param object $productsModel экземпляр ProductsModel
     * @param object $colorsModel экземпляр ColorsModel
     * @return int
     */
    public static function batchInsert(ProductsModel $productsModel, ColorsModel $colorsModel): int
    {
        try {
            $counter = 0;
            
            if (!empty($productsModel->id) && is_array($colorsModel->id) && !empty($colorsModel->id)) {
                $toRecord = [];
                foreach ($colorsModel->id as $colorId) {
                    $toRecord[] = [$productsModel->id, $colorId];
                    ++$counter;
                }
                if (!\Yii::$app->db->createCommand()->batchInsert('{{products_colors}}', ['[[id_product]]', '[[id_color]]'], $toRecord)->execute()) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'ProductsColorsModel::batchInsert']));
                }
            }
            
            return $counter;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
