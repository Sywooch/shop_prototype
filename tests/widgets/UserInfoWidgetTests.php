<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UserInfoWidget;
use app\models\UserInterface;

class UserInfoWidgetTests extends TestCase
{
    /**
     * Тестирует метод UserInfoWidget::widget()
     * вызываю с пустым UserInfoWidget::view
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetErrorView()
    {
        $result = UserInfoWidget::widget();
    }
    
    /**
     * Тестирует метод UserInfoWidget::widget()
     * вызываю с пустым UserInfoWidget::user
     * @expectedException yii\base\ErrorException
     */
    public function testWidgetErrorUser()
    {
        $result = UserInfoWidget::widget([
            'view'=>'user-info.twig'
        ]);
    }
    
    /**
     * Тестирует метод UserInfoWidget::widget()
     * при условии, что \Yii::$app->user->isGuest === true
     */
    public function testWidgetIsGuest()
    {
        $result = UserInfoWidget::widget([
            'view'=>'user-info.twig',
            'user'=>new class () implements UserInterface {
                public function isGuest()
                {
                    return true;
                }
                public function getIdentity()
                {
                    
                }
            },
        ]);
        
        $this->assertEquals(1, preg_match('/<div class="user-info">/', $result));
        $this->assertEquals(1, preg_match('/<p>' . \Yii::t('base', 'Hello, {placeholder}!', ['placeholder'=>\Yii::t('base', 'Guest')]) . '<\/p>/', $result));
        $this->assertEquals(1, preg_match('/<a href=".*">' . \Yii::t('base', 'Login') . '<\/a>/', $result));
        $this->assertEquals(1, preg_match('/<a href=".*">' . \Yii::t('base', 'Registration') . '<\/a>/', $result));
    }
    
    /**
     * Тестирует метод UserInfoWidget::widget()
     * при условии, что \Yii::$app->user->isGuest === false
     */
    public function testWidget()
    {
        $result = UserInfoWidget::widget([
            'view'=>'user-info.twig',
            'user'=>new class () implements UserInterface {
                public function isGuest()
                {
                    return false;
                }
                public function getIdentity()
                {
                    return new class () {
                        public $email;
                        public function __construct()
                        {
                            $this->email = new class() {
                                public $email = 'test@test.com';
                            };
                        }
                    };
                }
            },
        ]);
        
        $this->assertEquals(1, preg_match('/<div class="user-info">/', $result));
        $this->assertEquals(1, preg_match('/<p>' . \Yii::t('base', 'Hello, {placeholder}!', ['placeholder'=>'test@test.com']) . '<\/p>/', $result));
        $this->assertEquals(1, preg_match('/<input type="submit" value="' . \Yii::t('base', 'Logout') . '">/', $result));
    }
}
