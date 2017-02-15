<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\UserRegistrationPostRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};
use app\controllers\UserController;

/**
 * Тестирует класс UserRegistrationPostRequestHandler
 */
class UserRegistrationPostRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>EmailsFixture::class,
                'users'=>UsersFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->handler = new UserRegistrationPostRequestHandler();
    }
    
    /**
     * Тестирует метод UserRegistrationPostRequestHandler::userRegistrationSuccessWidgetConfig
     */
    public function testUserRegistrationSuccessWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'userRegistrationSuccessWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод UserRegistrationPostRequestHandler::handle
     * если ошибки валидации
     */
    public function testHandleErrors()
    {
        \Yii::$app->controller = new UserController('user', \Yii::$app);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserRegistrationForm'=>[
                        'email'=>'some@gmail',
                        'password'=>'password',
                        'password2'=>'password2'
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод UserRegistrationPostRequestHandler::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new UserController('user', \Yii::$app);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{users}} INNER JOIN {{emails}} ON [[users.id_email]]=[[emails.id]] WHERE [[emails.email]]=:email')->bindValue(':email', 'new@email.com')->queryOne();
        $this->assertFalse($result);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserRegistrationForm'=>[
                        'email'=>'new@email.com',
                        'password'=>'password',
                        'password2'=>'password'
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{users}} INNER JOIN {{emails}} ON [[users.id_email]]=[[emails.id]] WHERE [[emails.email]]=:email')->bindValue(':email', 'new@email.com')->queryOne();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertNotEmpty($files);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        if (file_exists($saveDir) && is_dir($saveDir)) {
            $files = glob($saveDir . '/*.eml');
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
}
