<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SizesFilterFindersTrait;
use app\models\SizesModel;
use yii\db\Query;

/**
 * Тестирует класс SizesFilterFindersTrait
 */
class SizesFilterFindersTraitTests extends TestCase
{
    /**
     * Тестирует метод SizesFilterFindersTrait::createQuery
     */
    public function testCreateQuery()
    {
        $finder = new class() {
            use SizesFilterFindersTrait;
        };
        
        $result = $finder->createQuery();
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(SizesModel::class, $result->modelClass);
        
        $expected = "SELECT DISTINCT `sizes`.`id`, `sizes`.`size` FROM `sizes` INNER JOIN `products_sizes` ON `sizes`.`id`=`products_sizes`.`id_size` INNER JOIN `products` ON `products_sizes`.`id_product`=`products`.`id` WHERE `products`.`active`=TRUE";
        
        $this->assertSame($expected, $result->createCommand()->getRawSql());
    }
}
