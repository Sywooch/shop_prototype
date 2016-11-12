<?php

namespace app\interfaces;

interface PurchasesInterface
{
    public function getProductId();
    public function getPrice();
    public function setQuantity(int $quantity);
    public function getQuantity();
}
