<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminUserDataChangePostRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{AddressFixture,
    CitiesFixture,
    CountriesFixture,
    NamesFixture,
    PhonesFixture,
    PostcodesFixture,
    SurnamesFixture,
    UsersFixture};
use app\helpers\HashHelper;
use app\models\UsersModel;

/**
 * Тестирует класс AdminUserDataChangePostRequestHandler
 */
class AdminUserDataChangePostRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'names'=>NamesFixture::class,
                'surnames'=>SurnamesFixture::class,
                'phones'=>PhonesFixture::class,
                'address'=>AddressFixture::class,
                'cities'=>CitiesFixture::class,
                'countries'=>CountriesFixture::class,
                'postcodes'=>PostcodesFixture::class,
                'users'=>UsersFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminUserDataChangePostRequestHandler();
    }
    
    /**
     * Тестирует метод AdminUserDataChangePostRequestHandler::handle
     * если запрос с ошибками
     */
    public function testHandleErrors()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserUpdateForm'=>[
                        'id'=>null,
                        'name'=>'John',
                        'surname'=>'Doe',
                        'phone'=>'+387968965',
                        'address'=>'ул. Черноозерная, 1',
                        'city'=>'Каркоза',
                        'country'=>'Гиады',
                        'postcode'=>'087869',
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminUserDataChangePostRequestHandler::handle
     */
    public function testHandle()
    {
        $oldUser = \Yii::$app->db->createCommand('SELECT * FROM {{users}} WHERE [[users.id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($oldUser);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserUpdateForm'=>[
                        'id'=>1,
                        'name'=>'New Name',
                        'surname'=>'New Surname',
                        'phone'=>'+897 897-01-55',
                        'address'=>'New Address',
                        'city'=>'New City',
                        'country'=>'New Country',
                        'postcode'=>89745,
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $newUser = \Yii::$app->db->createCommand('SELECT * FROM {{users}} WHERE [[users.id]]=:id')->bindValue(':id', 1)->queryOne();
        $this->assertNotEmpty($newUser);
        
        $this->assertEquals($oldUser['id'], $newUser['id']);
        $this->assertNotEquals($oldUser['id_name'], $newUser['id_name']);
        $this->assertNotEquals($oldUser['id_surname'], $newUser['id_surname']);
        $this->assertNotEquals($oldUser['id_phone'], $newUser['id_phone']);
        $this->assertNotEquals($oldUser['id_address'], $newUser['id_address']);
        $this->assertNotEquals($oldUser['id_city'], $newUser['id_city']);
        $this->assertNotEquals($oldUser['id_country'], $newUser['id_country']);
        $this->assertNotEquals($oldUser['id_postcode'], $newUser['id_postcode']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
