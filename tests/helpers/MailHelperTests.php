<?php

namespace app\tests\helpers;

use app\helpers\MailHelper;

/**
 * Тестирует класс app\helpers\MailHelper
 */
class MailHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_saveDir = '@app/tests/source/mail/letters';
    
    private static $_template = '@app/tests/source/mail/test.twig';
    private static $_setFrom = ['john@somedomain.com'=>'John Dow'];
    private static $_setTo = ['starling@somedomain.info'=>'Clarice Starling'];
    private static $_setSubject = 'Hello, how are you?';
    private static $_dataForTemplate = ['data'=>'Some data about tomorrow meeting! Please, confirm receipt!', 'header'=>'Hello, friends!'];
    private static $_contentType = 'text/html; charset=utf-8';
    
    public static function setUpBeforeClass()
    {
        if (file_exists(\Yii::getAlias(self::$_saveDir))) {
            $files = scandir(\Yii::getAlias(self::$_saveDir));
            foreach ($files as $file) {
                if (strpos($file, '.eml')) {
                    unlink(\Yii::getAlias(self::$_saveDir . '/' . $file));
                }
            }
        }
    }
    
    /**
     * Тестирует метод MailHelper::send
     */
    public function testSend()
    {
        $messageArray = [
            'template'=>self::$_template,
            'setFrom'=>self::$_setFrom,
            'setTo'=>self::$_setTo,
            'setSubject'=>self::$_setSubject,
            'dataForTemplate'=>self::$_dataForTemplate,
        ];
        
        $result = MailHelper::send([$messageArray]);
        
        $this->assertTrue($result);
        
        $emlFiles = [];
        $files = scandir(\Yii::getAlias(self::$_saveDir));
        foreach ($files as $file) {
            if (strpos($file, '.eml')) {
                $emlFiles[] = \Yii::getAlias(self::$_saveDir . '/' . $file);
            }
        }
        
        $this->assertFalse(empty($emlFiles));
        
        $file = file_get_contents($emlFiles[0]);
        
        $this->assertEquals(1, preg_match('#' . 'Subject:\s' . self::$_setSubject . '#', $file));
        $this->assertEquals(1, preg_match('#' . 'From:\s' . array_values(self::$_setFrom)[0] . '\s<' . array_keys(self::$_setFrom)[0] . '>#', $file));
        $this->assertEquals(1, preg_match('#' . 'To:\s' . array_values(self::$_setTo)[0] . '\s<' . array_keys(self::$_setTo)[0] . '>#', $file));
        $this->assertEquals(1, preg_match('#Content-Type:\s' . self::$_contentType . '#', $file));
        $this->assertEquals(1, preg_match('#<h1>' . substr(self::$_dataForTemplate['header'], 0, 10) . '#', $file));
        $this->assertEquals(1, preg_match('#<p>' . substr(self::$_dataForTemplate['data'], 0, 10) . '#', $file));
    }
    
    public static function tearDownAfterClass()
    {
        $dir = \Yii::getAlias(self::$_saveDir);
        if (file_exists($dir) && is_dir($dir)) {
            $files = glob($dir);
            foreach ($files as $file) {
                if (strpos($file, '.eml')) {
                    unlink($dir . '/' . $file);
                }
            }
        }
    }
}
