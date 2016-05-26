<?php

namespace app\interfaces;

/**
 * Объявляет интерфейс паттерна Visitor, который должны реализовывать все классы-наследники
 */
interface VisitorInterface
{
    /**
     * Принимает объект, данные которого необходимо обработать
     * @param object
     */
    public function update($object);
}
