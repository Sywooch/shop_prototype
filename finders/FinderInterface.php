<?php

namespace app\finders;

/**
 * Интерфейс классов finders
 */
interface FinderInterface
{
    public function find();
    public function load($data, $formName);
}
