<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\StripTagsValidator;

/**
 * Тестирует класс StripTagsValidator
 */
class StripTagsValidatorTests extends TestCase
{
    private $validator;
    
    public function setUp()
    {
        $this->validator = new StripTagsValidator();
    }
    
    private static $withHtmlTags = '<p>Some Name.</p> <ul><li>First punkt</li> </ul><strong>some.com</strong><?= echo "f"; ?>';
    private static $withoutHtmlTags = 'Some Name. First punkt some.com';
    private static $withoutHtmlTagsAllow = '<p>Some Name.</p> First punkt some.com';
    
    private static $withHtmlHrefTags = '<a href="some.com">some.com</a><php echo "f"; ?>';
    private static $withoutHtmlHrefTags = 'some.com';
    
    private static $withSomeSpacesTags = '   some    text ';
    private static $withSomeSpacesTagsParse = "   some    text \n\t";
    private static $withoutSomeSpacesTags = 'some text';
    
    private static $withJavascriptTags = 'some <script type="text/javascript">var a = 12;</script> text';
    private static $withoutJavascriptTags = 'some text';
    
    private static $withJavascriptRequireTags = 'some <script src="/my/script.js"></script> text';
    private static $withoutJavascriptRequireTags = 'some text';
    
    /**
     * Тестирует метод StripTagsValidator::strip
     */
    public function testStrip()
    {
        $reflection = new \ReflectionMethod($this->validator, 'strip');
        $reflection->setAccessible(true);
        
        $result = $reflection->invoke($this->validator, self::$withHtmlTags);
        $this->assertSame(self::$withoutHtmlTags, $result);
        $result = $reflection->invoke($this->validator, self::$withHtmlTags, 'p');
        $this->assertSame(self::$withoutHtmlTagsAllow, $result);
        $result = $reflection->invoke($this->validator, self::$withHtmlHrefTags);
        $this->assertSame(self::$withoutHtmlHrefTags, $result);
        $result = $reflection->invoke($this->validator, self::$withSomeSpacesTags);
        $this->assertSame(self::$withoutSomeSpacesTags, $result);
        $result = $reflection->invoke($this->validator, self::$withJavascriptTags);
        $this->assertSame(self::$withoutJavascriptTags, $result);
        $result = $reflection->invoke($this->validator, self::$withJavascriptRequireTags);
        $this->assertSame(self::$withoutJavascriptRequireTags, $result);
    }
    
    /**
     * Тестирует метод StripTagsValidator::lightStrip
     */
    public function testLightStrip()
    {
        $reflection = new \ReflectionMethod($this->validator, 'lightStrip');
        $reflection->setAccessible(true);
        
        $result = $reflection->invoke($this->validator, self::$withSomeSpacesTags);
        $this->assertSame(self::$withoutSomeSpacesTags, $result);
        $result = $reflection->invoke($this->validator, self::$withSomeSpacesTagsParse);
        $this->assertSame(self::$withoutSomeSpacesTags, $result);
    }
    
    /**
     * Тестирует метод StripTagsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $field;
        };
        $model->field = self::$withSomeSpacesTags;
        
        $this->validator->validateAttribute($model, 'field');
        $this->assertSame(self::$withoutSomeSpacesTags, $model->field);
        
        $model = new class() extends Model {
            public $field;
        };
        $model->field = self::$withSomeSpacesTagsParse;
        
        $this->validator->validateAttribute($model, 'field');
        $this->assertSame(self::$withoutSomeSpacesTags, $model->field);
    }
    
    /**
     * Тестирует метод StripTagsValidator::validate
     */
    public function testValidate()
    {
        $result = $this->validator->validate(self::$withSomeSpacesTags);
        $this->assertSame(self::$withoutSomeSpacesTags, $result);
        $result = $this->validator->validate(self::$withSomeSpacesTagsParse);
        $this->assertSame(self::$withoutSomeSpacesTags, $result);
    }
}
