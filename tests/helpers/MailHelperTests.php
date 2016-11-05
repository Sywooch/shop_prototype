<?php

namespace app\tests\helpers;

use app\helpers\MailHelper;

/**
 * Тестирует класс app\helpers\MailHelper
 */
class MailHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_saveDir;
    private static $_template;
    private static $_from = ['john@somedomain.com'=>'John Dow'];
    private static $_to = ['starling@somedomain.info'=>'Clarice Starling'];
    private static $_subject = 'Hello, how are you?';
    private static $_templateData = ['data'=>'Some data about tomorrow meeting! Please, confirm receipt!', 'header'=>'Hello, friends!'];
    private static $_contentType = 'text/html; charset=utf-8';
    
    public static function setUpBeforeClass()
    {
        self::$_saveDir = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath);
        self::$_template = \Yii::getAlias(\Yii::$app->mailer->fileTransportPath . '/../test.twig');
    }
    
    /**
     * Тестирует метод MailHelper::send
     */
    public function testSend()
    {
        $messageArray = [
            'template'=>self::$_template,
            'from'=>self::$_from,
            'to'=>self::$_to,
            'subject'=>self::$_subject,
            'templateData'=>self::$_templateData,
        ];
        
        $result = MailHelper::send([$messageArray]);
        
        $this->assertEquals(1, $result);
        
        $emlFiles = glob(self::$_saveDir . '/*.eml');
        
        $this->assertFalse(empty($emlFiles));
        
        $file = file_get_contents($emlFiles[0]);
        
        $this->assertEquals(1, preg_match('#Subject:\s' . self::$_subject . '#', $file));
        $this->assertEquals(1, preg_match('#From:\s' . array_values(self::$_from)[0] . '\s<' . array_keys(self::$_from)[0] . '>#', $file));
        $this->assertEquals(1, preg_match('#To:\s' . array_values(self::$_to)[0] . '\s<' . array_keys(self::$_to)[0] . '>#', $file));
        $this->assertEquals(1, preg_match('#Content-Type:\s' . self::$_contentType . '#', $file));
        $this->assertEquals(1, preg_match('#<h1>' . substr(self::$_templateData['header'], 0, 10) . '#', $file));
        $this->assertEquals(1, preg_match('#<p>' . substr(self::$_templateData['data'], 0, 10) . '#', $file));
    }
    
    public static function tearDownAfterClass()
    {
        if (file_exists(self::$_saveDir) && is_dir(self::$_saveDir)) {
            $files = glob(self::$_saveDir . '/*.eml');
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
}
