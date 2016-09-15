<?php

namespace app\tests\source\fixtures;

use yii\test\ActiveFixture;
use app\traits\ExceptionsTrait;

/**
 * Абстрактный класс, коллекция свойств и методов, 
 * общих для всех классов-фикстур
 */
abstract class AbstractFixture extends ActiveFixture
{
    use ExceptionsTrait;
    
    /**
     * Очищает БД от данных, представляемых текущей фикстурой
     */
    public function unload()
    {
        parent::unload();
        
        $this->resetTable();
    }
}
