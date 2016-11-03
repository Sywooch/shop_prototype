<?php

namespace app\queries;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

/**
 * Обеспечивает доступ к данным, полученным из БД в виде массива 
 *  как к обычным свойствам ActiveRecord
 */
class ARWrapper implements \Iterator
{
    use ExceptionsTrait;
    
    /**
     * @var array массив данных, полученных из БД
     */
    private $_object;
    
    /**
     * @var int текущая позиция итерации
     */
    private $_position = 0;
    
    /**
     * Устанавливает начальную позицию итерации
     */
    public function __construct()
    {
        $this->_position = 0;
    }
    
    /**
     * Возвращает итератор на первый элемент
     */
    public function rewind()
    {
        $this->_position = 0;
    }
    
    /**
     * Возвращает текущий элемент
     */
    public function current()
    {
        return $this->_object[$this->_position];
    }
    
    /**
     * Возвращает ключ текущего элемента
     */
    public function key()
    {
        return $this->_position;
    }
    
    /**
     * Переходит к следующему элементу
     */
    public function next()
    {
        ++$this->_position;
    }
    
    /**
     * Проверка корректности позиции
     */
    public function valid()
    {
        return isset($this->_object[$this->_position]);
    }
    
    /**
     * Инициирует создание обертки вокруг массивов, представляющих группу строк БД
     * @param array $rawArray массив данных, полученных из БД
     * @return array
     */
    public static function set(array $rawArray): array
    {
        try {
            $resultArray = [];
            if (!empty($rawArray)) {
                foreach ($rawArray as $array) {
                    $resultArray[] = self::pack($array);
                }
            }
            
            return $resultArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Инициирует создание обертки вокруг данных 1 строки
     * @param array $rawArray массив данных, полученных из БД
     * @return mixed
     */
    public static function setOne(array $rawArray)
    {
        try {
            if (!empty($rawArray)) {
                $result = self::pack($rawArray);
            }
            
            return $result ?? null;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Рекурсивно обходит данные, оборачивая каждый массив в ARWrapper
     * @param array $rawArray массив данных
     * @return array
     */
    private static function pack(array $rawArray): self
    {
        try {
            $wrapper = new self();
            $resultArray = [];
            foreach ($rawArray as $key=>$val) {
                if (is_array($val)) {
                    $val = self::pack($val);
                }
                $resultArray[$key] = $val;
            }
            $wrapper->setData($resultArray);
            
            return $wrapper;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству $_object, представляющему данные из БД
     */
    private function setData($object)
    {
        try {
            $this->_object = $object;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Перехватывает обращение к недоступному свойству, отображая его 
     * на обращение к элементу массива $_object
     * @param string имя свойства
     * @return mixed
     */
    public function __get($value) {
        try {
            return !empty($this->_object[$value]) ? $this->_object[$value] : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Перехватывает попытку выполнить isset() или empty() на недоступных свойствах
     * @param string имя свойства
     * @return bool
     */
    public function __isset($value): bool
    {
        try {
            return !empty($this->_object[$value]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function toArray()
    {
        try {
            return $this->unpack($this->_object);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Рекурсивно обходит данные, распаковывая ARWrapper в массивы 
     * @param array $rawArray массив данных
     * @return array
     */
    private function unpack(array $rawArray)
    {
        try {
            $resultArray = [];
            foreach ($rawArray as $key=>$val) {
                if (is_array($val)) {
                    $val = self::unpack($val);
                }
                $resultArray[$key] = $val;
            }
            
            return $resultArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
