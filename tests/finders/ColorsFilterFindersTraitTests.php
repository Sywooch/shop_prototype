<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ColorsFilterFindersTrait;
use app\models\ColorsModel;
use yii\db\Query;

/**
 * Тестирует класс ColorsFilterFindersTrait
 */
class ColorsFilterFindersTraitTests extends TestCase
{
    /**
     * Тестирует метод ColorsFilterFindersTrait::createQuery
     */
    public function testCreateQuery()
    {
        $finder = new class() {
            use ColorsFilterFindersTrait;
        };
        
        $result = $finder->createQuery();
        
        $this->assertInstanceOf(Query::class, $result);
        $this->assertSame(ColorsModel::class, $result->modelClass);
        
        $expected = "SELECT DISTINCT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_color` INNER JOIN `products` ON `products_colors`.`id_product`=`products`.`id` WHERE `products`.`active`=TRUE";
        
        $this->assertSame($expected, $result->createCommand()->getRawSql());
    }
}
