<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\StripTagsValidator;

/**
 * Тестирует класс app\validators\StripTagsValidator
 */
class StripTagsValidatorTests extends TestCase
{
    private static $withHtmlTags = '<p>Some Name.</p> <ul><li>First punkt</li> </ul><strong>some.com</strong><?= echo "f"; ?>';
    private static $withoutHtmlTags = 'Some Name. First punkt some.com';
    
    private static $withHtmlHrefTags = '<a href="some.com">some.com</a><php echo "f"; ?>';
    private static $withoutHtmlHrefTags = 'some.com';
    
    private static $withSomeSpacesTags = 'some    text ';
    private static $withoutSomeSpacesTags = 'some text';
    
    private static $withJavascriptTags = 'some <script type="text/javascript">var a = 12;</script> text';
    private static $withoutJavascriptTags = 'some text';
    
    private static $withJavascriptRequireTags = 'some <script src="/my/script.js"></script> text';
    private static $withoutJavascriptRequireTags = 'some text';
    
    private static $number = 568.87;
    
    /**
     * Тестирует метод StripTagsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $validator = new StripTagsValidator();
        $result = $validator->validate(self::$withHtmlTags);
        $this->assertEquals(self::$withoutHtmlTags, $result);
        
        $validator = new StripTagsValidator();
        $result = $validator->validate(self::$withHtmlHrefTags);
        $this->assertEquals(self::$withoutHtmlHrefTags, $result);
        
        $validator = new StripTagsValidator();
        $result = $validator->validate(self::$withSomeSpacesTags);
        $this->assertEquals(self::$withoutSomeSpacesTags, $result);
        
        $validator = new StripTagsValidator();
        $result = $validator->validate(self::$withJavascriptTags);
        $this->assertEquals(self::$withoutJavascriptTags, $result);
        
        $validator = new StripTagsValidator();
        $result = $validator->validate(self::$withJavascriptRequireTags);
        $this->assertEquals(self::$withoutJavascriptRequireTags, $result);
        
        $model = new class() extends Model {
            public $description = '<p>Some Name.</p> <ul><li>First punkt</li> </ul><strong>some.com</strong>';
        };
        $validator = new StripTagsValidator();
        $validator->validateAttribute($model, 'description');
        
        $this->assertEquals(self::$withoutHtmlTags, $model->description);
        
        $model = new class() extends Model {
            public $description = [
                '<p>Some Name.</p> <ul><li>First punkt</li> </ul><strong>some.com</strong>',
                '<a href="some.com">some.com</a>'
            ];
        };
        $validator = new StripTagsValidator();
        $validator->validateAttribute($model, 'description');
        
        $this->assertContains('Some Name. First punkt some.com', $model->description);
        $this->assertContains('some.com', $model->description);
        
        $validator = new StripTagsValidator();
        $result = $validator->validate(self::$number);
        $this->assertSame(self::$number, $result);
    }
}
