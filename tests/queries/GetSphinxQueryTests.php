<?php

namespace app\tests\queries;

use PHPUnit\Framework\TestCase;
use app\queries\GetSphinxQuery;

/**
 * Тестирует класс app\queries\GetSphinxQuery
 */
class GetSphinxQueryTests extends TestCase
{
    private static $_search = 'ботинки';
    
    /**
     * Тестирует метод GetSphinxQuery::getAll()
     * без категорий и фильтров
     */
    public function testGetAll()
    {
        $_GET = [\Yii::$app->params['searchKey']=>'ботинки'];
        \Yii::$app->filters->clean();
        
        $sphinxQuery = new GetSphinxQuery([
            'tableName'=>'shop',
            'fields'=>['id'],
        ]);
        
        $query = $sphinxQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = sprintf("SELECT `id` FROM `shop` WHERE MATCH('@* \\\"%s\\\"')", self::$_search);
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
    }
}
