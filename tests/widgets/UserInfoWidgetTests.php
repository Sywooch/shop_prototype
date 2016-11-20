<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use yii\web\User;
use app\widgets\UserInfoWidget;
use app\tests\DbManager;
use app\models\UsersModel;
use app\helpers\SessionHelper;

class UserInfoWidgetTests extends TestCase
{
    private static $dbClass;
    private static $guestUser;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>'app\tests\sources\fixtures\UsersFixture',
                'emails'=>'app\tests\sources\fixtures\EmailsFixture',
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        /*self::$guestUser = new class () extends User {
            public $identityClass;
            public $isGuest = true;
            public function __construct()
            {
                $this->identityClass = new class() {};
            }
        };*/
    }
    
    /**
     * Тестирует метод UserInfoWidget::setUser
     * передаю не поддерживающий User объект
     * @expectedException TypeError
     */
    public function testSetItemsError()
    {
        $result = UserInfoWidget::widget([
            'user'=>new class () {}
        ]);
    }
    
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
    public function testWidget()
    {
        $result = UserInfoWidget::widget([
            'view'=>'user-info.twig'
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
    public function testWidgetEmail()
    {
        $userFixture = self::$dbClass->users['user_1'];
        $emailFixture = self::$dbClass->emails['email_1'];
        
        $user = UsersModel::findOne($userFixture['id']);
        //\Yii::$app->user->login($user);
        
        $result = UserInfoWidget::widget([
            'view'=>'user-info.twig'
        ]);
        
        //$expectedString = '<p>Привет, ' . $user->email->email . '!</p><form id="user-logout-form" action="../vendor/phpunit/phpunit/logout" method="POST">' . PHP_EOL . '<input type="hidden" name="_csrf" value="' . \Yii::$app->request->csrfToken . '"><input type="hidden" name="userId" value="' . $user->id . '"><button type="submit">' . \Yii::t('base', 'Logout') . '</button></form>';
        
        //$expectedString = '<form id="user-logout-form" action="../vendor/phpunit/phpunit/logout" method="POST">' . PHP_EOL . '<input type="hidden" name="_csrf" value="' . \Yii::$app->request->csrfToken . '"><input type="hidden" name="userId" value="' . $user->id . '"><button type="submit">' . \Yii::t('base', 'Logout') . '</button></form>';
        
        //$this->assertEquals($expectedString, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
