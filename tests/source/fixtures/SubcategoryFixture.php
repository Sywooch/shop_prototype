<?php

namespace app\tests\source\fixtures;

use app\tests\source\fixtures\AbstractFixture;

class SubcategoryFixture extends AbstractFixture
{
    public $modelClass = 'app\models\SubcategoryModel';
    public $depends = [
        'app\tests\source\fixtures\CategoriesFixture',
    ];
}
