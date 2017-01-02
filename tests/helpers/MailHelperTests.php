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
    private static $template;
    private static $from = ['john@somedomain.com'=>'John Dow'];
    private static $to = ['starling@somedomain.info'=>'Clarice Starling'];
    private static $subject = 'Hello, how are you?';
    private static $templateData = ['data'=>'Some data about tomorrow meeting! Please, confirm receipt!', 'header'=>'Hello, friends!'];
    private static $contentType = 'text/html; charset=utf-8';
    
    public static function setUpBeforeClass()
    {
        self::$saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        self::$template = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath . '/../test.twig');
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
            'template'=>self::$template,
            'from'=>self::$from,
            'to'=>self::$to,
            'subject'=>self::$subject,
            'templateData'=>self::$templateData,
        ];
        
        $helper = new MailHelper([$messageArray]);
        $result = $helper->send();
        
        $this->assertEquals(1, $result);
        
        $emlFiles = glob(self::$saveDir . '/*.eml');
        
        $this->assertNotEmpty($emlFiles);
        
        $file = file_get_contents($emlFiles[0]);
        
        $this->assertRegExp('#Subject:\s' . self::$subject . '#', $file);
        $this->assertRegExp('#From:\s' . array_values(self::$from)[0] . '\s<' . array_keys(self::$from)[0] . '>#', $file);
        $this->assertRegExp('#To:\s' . array_values(self::$to)[0] . '\s<' . array_keys(self::$to)[0] . '>#', $file);
        $this->assertRegExp('#Content-Type:\s' . self::$contentType . '#', $file);
        $this->assertRegExp('#<h1>' . substr(self::$templateData['header'], 0, 10) . '#', $file);
        $this->assertRegExp('#<p>' . substr(self::$templateData['data'], 0, 10) . '#', $file);
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
