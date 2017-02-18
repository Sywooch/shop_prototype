<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminAddProductBreadcrumbsWidget;
use yii\base\Model;
use app\models\ProductsModel;

/**
 * Тестирует класс AdminAddProductBreadcrumbsWidget
 */
class AdminAddProductBreadcrumbsWidgetTests extends TestCase
{
    /**
     * Тестирует метод AdminAddProductBreadcrumbsWidget::run
     */
    public function testRun()
    {
        $widget = new AdminAddProductBreadcrumbsWidget();
        $widget->run();
    }
}

