<?php

namespace app\repositories;

/**
 * Интерфейс классов-репозиториев
 */
interface RepositoryInterface
{
    public function getGroup($request);
    public function getOne($request);
    public function getCriteria();
    public function addCriteria($query);
    public function saveOne($key, $data);
}
