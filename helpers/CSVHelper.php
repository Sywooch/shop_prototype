<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;

/**
 * Предоставляет функциональность для создания csv из данных БД
 */
class CSVHelper
{
    /**
     * @var string путь по которому доступен готовый файл
     */
    private static $_filename;
    
    /**
     * Формирует csv файл
     * @param array $config массив конфигурации, включает ключи:
     * - path путь к директории файла
     * - filename имя файла
     * - objectsArray массив обектов
     * - fields имена полей, которые будут включены в файл
     * @return string результирующий путь по которому доступен готовый файл
     */
    public static function getCSV(Array $config)
    {
        try {
            if (!$handle = fopen($config['path'] . $config['filename'] . '.csv', 'w')) {
                throw new ErrorException('Ошибка при создании файла!');
            }
            $fieldsNamesArray = array();
            foreach ($config['fields'] as $field) {
                $fieldsNamesArray[] = $field;
            }
            if (!fputcsv($handle, $fieldsNamesArray, ';', '"')) {
                throw new ErrorException('Ошибка при записи в файл!');
            }
            foreach ($config['objectsArray'] as $object) {
                $objectFieldsArray = array();
                foreach ($config['fields'] as $field) {
                    $objectFieldsArray[] = $object->$field;
                }
                if (!fputcsv($handle, $objectFieldsArray, ';', '"')) {
                    throw new ErrorException('Ошибка при записи в файл!');
                }
            }
            if (!fclose($handle)) {
                throw new ErrorException('Ошибка при закрытии файла!');
            }
            return $config['filename'] . '.csv';
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
