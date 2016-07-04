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
    private static $_setTo = ['manager@somedomain.info'=>'Clarice Starling'];
    private static $_setSubject = 'Hello, how are you?';
    private static $_dataForTemplate = ['data'=>'<p>Some data about tomorrow meeting</p>', 'header'=>'Hello, friends!'];
    
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
        
        $this->assertTrue((boolean)strpos($file, 'Subject: ' . self::$_setSubject));
        $this->assertTrue((boolean)strpos($file, 'From: ' . array_values(self::$_setFrom)[0] . ' <' . array_keys(self::$_setFrom)[0] . '>'));
        $this->assertTrue((boolean)strpos($file, 'To: ' . array_values(self::$_setTo)[0]. ' <' . array_keys(self::$_setTo)[0] . '>'));
        $this->assertTrue((boolean)strpos($file, self::$_dataForTemplate['data']));
        $this->assertTrue((boolean)strpos($file, '<h1>' . self::$_dataForTemplate['header'] . '</h1>'));
    }
    
    public static function tearDownAfterClass()
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
}
