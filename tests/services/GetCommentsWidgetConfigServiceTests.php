<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCommentsWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CommentsFixture,
    ProductsFixture};

/**
 * Тестирует класс GetCommentsWidgetConfigService
 */
class GetCommentsWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'comments'=>CommentsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства GetCommentsWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCommentsWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('commentsWidgetArray'));
    }
    
    /**
     * Тестирует метод GetCommentsWidgetConfigService::handle
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
        
        $service = new GetCommentsWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод GetCommentsWidgetConfigService::handle
     * если комментарии не найдены
     */
    public function testHandleEmptyComments()
    {
        $request = new class(){
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $result = $reflection->setValue($request, self::$dbClass->products['product_3']['seocode']);
        
        $service = new GetCommentsWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('template', $result);
        $this->assertArrayNotHasKey('comments', $result);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод GetCommentsWidgetConfigService::handle
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
        
        $service = new GetCommentsWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('comments', $result);
        $this->assertArrayHasKey('template', $result);
        $this->assertInternalType('array', $result['comments']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
