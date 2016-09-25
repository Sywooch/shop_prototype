<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use app\tests\MockObject;
use app\validators\StripTagsValidator;

/**
 * Тестирует класс app\validators\StripTagsValidator
 */
class StripTagsValidatorTests extends TestCase
{
    private static $_withHtmlTags = '<p>Some Name.</p> <ul><li>First punkt</li> </ul><strong>some.com</strong>';
    private static $_withoutHtmlTags = 'Some Name. First punkt some.com';
    
    private static $_withHtmlHrefTags = '<a href="some.com">some.com</a>';
    private static $_withoutHtmlHrefTags = 'some.com';
    
    private static $_withSomeSpacesTags = 'some    text ';
    private static $_withoutSomeSpacesTags = 'some text';
    
    private static $_withJavascriptTags = 'some <script type="text/javascript">var a = 12;</script> text';
    private static $_withoutJavascriptTags = 'some text';
    
    private static $_withJavascriptRequireTags = 'some <script src="/my/script.js"></script> text';
    private static $_withoutJavascriptRequireTags = 'some text';
    
    /**
     * Тестирует метод StripTagsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $validator = new StripTagsValidator();
        $result = $validator->validate(self::$_withHtmlTags);
        $this->assertEquals(self::$_withoutHtmlTags, $result);
        
        $validator = new StripTagsValidator();
        $result = $validator->validate(self::$_withHtmlHrefTags);
        $this->assertEquals(self::$_withoutHtmlHrefTags, $result);
        
        $validator = new StripTagsValidator();
        $result = $validator->validate(self::$_withSomeSpacesTags);
        $this->assertEquals(self::$_withoutSomeSpacesTags, $result);
        
        $validator = new StripTagsValidator();
        $result = $validator->validate(self::$_withJavascriptTags);
        $this->assertEquals(self::$_withoutJavascriptTags, $result);
        
        $validator = new StripTagsValidator();
        $result = $validator->validate(self::$_withJavascriptRequireTags);
        $this->assertEquals(self::$_withoutJavascriptRequireTags, $result);
        
        $model = new MockObject(['description'=>self::$_withHtmlTags]);
        $validator = new StripTagsValidator();
        $validator->allowable_tags = '<p></p><ul></ul><li></li><strong></strong>';
        $validator->exceptProreties = ['app\tests\MockObject::description'];
        $validator->validateAttribute($model, 'description');
        $this->assertEquals(self::$_withHtmlTags, $model->description);
    }
}
