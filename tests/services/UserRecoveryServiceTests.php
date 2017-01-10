<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\UserRecoveryService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{EmailsFixture,
    UsersFixture};
use app\controllers\UserController;

/**
 * Тестирует класс UserRecoveryService
 */
class UserRecoveryServiceTests extends TestCase
{
    private static $dbClass;
    
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
    
    /**
     * Тестирует метод UserRecoveryService::handle
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
                    'RecoveryPasswordForm'=>[
                        'email'=>'some@gmail',
                    ],
                ];
            }
        };
        
        $service = new UserRecoveryService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод UserRecoveryService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new UserController('user', \Yii::$app);
        
        $request = new class() {
            public $isAjax = true;
            public $email;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'RecoveryPasswordForm'=>[
                        'email'=>$this->email,
                    ],
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'email');
        $reflection->setValue($request, self::$dbClass->emails['email_1']['email']);
        
        $service = new UserRecoveryService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
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
