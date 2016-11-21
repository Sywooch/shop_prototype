<?php

namespace app\models;

/**
 * Интерфейс объектов, предоставляющих доступ к 
 * текущему пользователю приложения
 */
interface UserInterface
{
    public function isGuest();
    public function getIdentity();
}
