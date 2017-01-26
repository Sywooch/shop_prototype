<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCommentFormWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\forms\CommentForm;

/**
 * Тестирует класс GetCommentFormWidgetConfigService
 */
class GetCommentFormWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetCommentFormWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCommentFormWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('commentFormWidgetArray'));
    }
    
    /**
     * Тестирует метод GetCommentFormWidgetConfigService::handle
     * если не найден товар
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleEmptyProduct()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return 'nothing';
            }
        };
        
        $service = new GetCommentFormWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод GetCommentFormWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new GetCommentFormWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        $this->assertInstanceOf(CommentForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
