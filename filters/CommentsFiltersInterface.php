<?php

namespace app\filters;

/**
 * Интерфейс фильтров комментариев
 */
interface CommentsFiltersInterface
{
    public function getSortingField();
    public function getSortingType();
    public function getActiveStatus();
}
