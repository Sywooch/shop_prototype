<?php

namespace app\queries;

interface QueryInterface
{
    /**
     * Конфигурирует объект запроса yii\db\ActiveQuery для выборки массива строк
     * @return object ActiveQuery
     */
    public function getAll();
    /**
     * Конфигурирует объект запроса yii\db\ActiveQuery для выборки одной строки
     * @return object ActiveQuery
     */
    public function getOne();
}
