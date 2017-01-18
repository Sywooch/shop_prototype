<?php

namespace app\tests\helpers;

use PHPUnit\Framework\TestCase;
use app\helpers\MailHelper;

/**
 * Тестирует класс MailHelper
 */
class MailHelperTests extends TestCase
{
    private static $saveDir;
    
    public static function setUpBeforeClass()
    {
        self::$saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
    }
    
    /**
     * Тестирует свойства MailHelper
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailHelper::class);
        
        $this->assertTrue($reflection->hasProperty('messagesArray'));
        $this->assertTrue($reflection->hasProperty('rawMessagesArray'));
    }
    
    /**
     * Тестирует метод MailHelper::send
     */
    public function testSend()
    {
        $messageArray = [
            'html'=>'<h1>Header</h1><p>Text text</p><ul><li>One</li><li>Two</li></ul>',
            'from'=>['john@somedomain.com'=>'John Dow'],
            'to'=>'starling@somedomain.info',
            'subject'=>'Hello, how are you?',
        ];
        
        $helper = new MailHelper([$messageArray]);
        $result = $helper->send();
        
        $this->assertEquals(1, $result);
        
        $emlFiles = glob(self::$saveDir . '/*.eml');
        
        $this->assertNotEmpty($emlFiles);
        
        $file = file_get_contents($emlFiles[0]);
        
        $this->assertRegExp('#Subject: Hello, how are you?#', $file);
        $this->assertRegExp('#From: John Dow <john@somedomain.com>#', $file);
        $this->assertRegExp('#To: starling@somedomain.info#', $file);
        $this->assertRegExp('#Content-Type: text/html; charset=utf-8#', $file);
        $this->assertRegExp('#<h1>Header</h1><p>Text text</p><ul><li>One</li><li>Two</li></ul>#', $file);
    }
    
    public static function tearDownAfterClass()
    {
        if (file_exists(self::$saveDir) && is_dir(self::$saveDir)) {
            $files = glob(self::$saveDir . '/*.eml');
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
}
