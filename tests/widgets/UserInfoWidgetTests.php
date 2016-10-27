<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\UserInfoWidget;
use app\tests\DbManager;
use app\models\UsersModel;

class UserInfoWidgetTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'users'=>'app\tests\sources\fixtures\UsersFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод UserInfoWidget::widget()
     * при условии, что \Yii::$app->user->isGuest == true
     */
    public function testWidget()
    {
        $result = UserInfoWidget::widget();
        
        $expectedString = '<p>Привет, ' . \Yii::t('base', 'Guest') . '!</p><p><a href="../vendor/phpunit/phpunit/login">' . \Yii::t('base', 'Login') . '</a> <a href="../vendor/phpunit/phpunit/registration">' . \Yii::t('base', 'Registration') . '</a></p>';
        
        $this->assertEquals($expectedString, $result);
    }
    
    /**
     * Тестирует метод UserInfoWidget::widget()
     * при условии, что \Yii::$app->user->isGuest == false
     */
    public function testWidgetUserName()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $user = UsersModel::findOne($fixture['id']);
        \Yii::$app->user->login($user);
        
        $result = UserInfoWidget::widget();
        
        $expectedString = '<p>Привет, ' . $user->name . '!</p><form id="user-logout-form" action="../vendor/phpunit/phpunit/logout" method="POST"' . ">\n" . '<input type="hidden" name="_csrf" value="' . \Yii::$app->request->csrfToken . '"><input type="hidden" name="userId" value="' . $user->id . '"><button type="submit">' . \Yii::t('base', 'Logout') . '</button></form>';
        
        $this->assertEquals($expectedString, $result);
    }
    
    /**
     * Тестирует метод UserInfoWidget::widget()
     * при условии, что \Yii::$app->user->isGuest == false и UsersModel->name == false
     */
    public function testWidgetUserEmail()
    {
        $fixture = self::$_dbClass->users['user_1'];
        
        $user = UsersModel::findOne($fixture['id']);
        $user->name = '';
        \Yii::$app->user->login($user);
        
        $result = UserInfoWidget::widget();
        
        $expectedString = '<p>Привет, ' . $user->emails->email . '!</p><form id="user-logout-form" action="../vendor/phpunit/phpunit/logout" method="POST"' . ">\n" . '<input type="hidden" name="_csrf" value="' . \Yii::$app->request->csrfToken . '"><input type="hidden" name="userId" value="' . $user->id . '"><button type="submit">' . \Yii::t('base', 'Logout') . '</button></form>';
        
        $this->assertEquals($expectedString, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
