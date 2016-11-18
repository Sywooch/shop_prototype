<?php

namespace app\models;

use app\models\PurchasesModel;

/**
 * Интерфейс доступа к данным о покупках в корзине
 */
interface PurchasesInterface
{
    public function add($data);
    /*public function getId_user();
    public function getId_name();
    public function getId_surname();
    public function getId_email();
    public function getId_phone();
    public function getId_address();
    public function getId_city();
    public function getId_country();
    public function getId_postcode();
    public function getId_product();*/
    public function getQuantity();
    public function totalQuantity();
    /*public function getId_color();
    public function getId_size();*/
    public function getPrice();
    public function totalPrice();
    /*public function getId_delivery();
    public function getId_payment();
    public function getReceived();
    public function getReceived_date();
    public function getProcessed();
    public function getCanceled();
    public function getShipped();*/
}
