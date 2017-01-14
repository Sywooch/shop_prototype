<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\ReceivedOrderEmailService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\helpers\HashHelper;

/**
 * Тестирует класс ReceivedOrderEmailService
 */
class ReceivedOrderEmailServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод ReceivedOrderEmailService::handle
     */
    public function testHandle()
    {
        $session = \Yii::$app->session;
        $session->open();
        
        $session->set(HashHelper::createCartKey(), [['quantity'=>2, 'id_color'=>2, 'id_size'=>2, 'id_product'=>1, 'price'=>268.78]]);
        $session->set(HashHelper::createCartCustomerKey(), [
            'name'=>'John',
            'surname'=>'Doe',
            'email'=>'jahn@com.com',
            'phone'=>'+387968965',
            'address'=>'ул. Черноозерная, 1',
            'city'=>'Каркоза',
            'country'=>'Гиады',
            'postcode'=>'08789',
            'id_delivery'=>1,
            'id_payment'=>1,
        ]);
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertEmpty($files);
        
        $service = new ReceivedOrderEmailService();
        $service->handle();
        
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        $files = glob($saveDir . '/*.eml');
        
        $this->assertNotEmpty($files);
        
        $session->remove(HashHelper::createCartKey());
        $session->remove(HashHelper::createCartCustomerKey());
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        $saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        
        if (file_exists($saveDir) && is_dir($saveDir)) {
            $files = glob($saveDir . '/*.eml');
            foreach ($files as $file) {
                unlink($file);
            }
        }
        
        self::$dbClass->unloadFixtures();
    }
}
