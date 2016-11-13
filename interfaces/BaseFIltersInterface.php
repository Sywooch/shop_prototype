<?php

namespace app\interfaces;

interface BaseFIltersInterface
{
    public function search(string $scenario, $data);
    public function save(string $scenario, $data);
}
