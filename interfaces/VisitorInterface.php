<?php

namespace app\interfaces;

use app\mappers\AbstractBaseMapper;

/**
 * Объявляет интерфейс паттерна Visitor, который должны реализовывать все классы-наследники
 */
interface VisitorInterface
{
    /**
     * Принимает объект, данные которого необходимо обработать
     * @param object
     */
    public function update(AbstractBaseMapper $object);
}
