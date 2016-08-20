<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\SizesBySizeMapper;
use app\models\SizesModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\SizesBySizeMapper
 */
class SizesBySizeMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_size = '45';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод SizesBySizeMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $sizesBySizeMapper = new SizesBySizeMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'model'=>new SizesModel([
                'size'=>self::$_size,
            ]),
        ]);
        $sizesModel = $sizesBySizeMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($sizesModel));
        $this->assertTrue($sizesModel instanceof SizesModel);
        
        $this->assertEquals(self::$_id, $sizesModel->id);
        $this->assertEquals(self::$_size, $sizesModel->size);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
