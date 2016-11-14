<?php

namespace app\repository;

interface ProductsRepositoryInterface
{
    public function getOneBySeocode(string $seocode);
}
