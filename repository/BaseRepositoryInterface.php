<?php

namespace app\repository;

interface BaseRepositoryInterface
{
    public function save($item);
    public function update($item);
    public function delete($item);
    
    public function getById($id);
    public function getByIds(array $id);
}
