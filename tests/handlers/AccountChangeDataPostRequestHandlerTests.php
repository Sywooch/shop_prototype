<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AccountChangeDataPostRequestHandler;
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
 * Тестирует класс AccountChangeDataPostRequestHandler
 */
class AccountChangeDataPostServiceTests extends TestCase
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
        
        $this->handler = new AccountChangeDataPostRequestHandler();
    }
    
    /**
     * Тестирует метод AccountChangeDataPostRequestHandler::handle
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
                        'name'=>null,
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
     * Тестирует метод AccountChangeDataPostRequestHandler::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{users}} INNER JOIN {{names}} ON [[users.id_name]]=[[names.id]] WHERE [[names.name]]=:name')->bindValue(':name', 'New Name')->queryOne();
        $this->assertEmpty($result);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserUpdateForm'=>[
                        'name'=>'New Name',
                        'surname'=>'New Surname',
                        'phone'=>'+897 897-01-55',
                        'address'=>'New Address',
                        'city'=>'New City',
                        'country'=>'New Country',
                        'postcode'=>'New7865',
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{users}} INNER JOIN {{names}} ON [[users.id_name]]=[[names.id]] WHERE [[names.name]]=:name')->bindValue(':name', 'New Name')->queryOne();
        $this->assertNotEmpty($result);
        
        $user = UsersModel::findOne(1);
        $this->assertEquals('New Name', $user->name->name);
        $this->assertEquals('New Surname', $user->surname->surname);
        $this->assertEquals('+897 897-01-55', $user->phone->phone);
        $this->assertEquals('New Address', $user->address->address);
        $this->assertEquals('New City', $user->city->city);
        $this->assertEquals('New Country', $user->country->country);
        $this->assertEquals('New7865', $user->postcode->postcode);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
