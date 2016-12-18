<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\BrandsFilterFindersTrait;
use app\models\BrandsModel;
use yii\db\Query;

/**
 * Тестирует класс BrandsFilterFindersTrait
 */
class BrandsFilterFindersTraitTests extends TestCase
{
    /**
     * Тестирует метод BrandsFilterFindersTrait::createQuery
     */
    public function testCreateQuery()
    {
        $finder = new class() {
            use BrandsFilterFindersTrait;
        };
        
        $result = $finder->createQuery();
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(BrandsModel::class, $result->modelClass);
        
        $expected = "SELECT DISTINCT `brands`.`id`, `brands`.`brand` FROM `brands` INNER JOIN `products` ON `products`.`id_brand`=`brands`.`id` WHERE `products`.`active`=TRUE";
        
        $this->assertSame($expected, $result->createCommand()->getRawSql());
    }
}
