<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\widgets\AdminUserDetailBreadcrumbsWidget;

/**
 * Тестирует класс AdminUserDetailBreadcrumbsWidget
 */
class AdminUserDetailBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminUserDetailBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $usersModel = new class() extends Model {
            public $id = 1;
            public $email;
            public function __construct()
            {
                $this->email = new class() {
                    public $email = 'mail@mail.com';
                };
            }
        };
        
        $widget = new AdminUserDetailBreadcrumbsWidget(['usersModel'=>$usersModel]);
        
        $widget->run();
        
        $this->assertTrue(true);
    }
}

