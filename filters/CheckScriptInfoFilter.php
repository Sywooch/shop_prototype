<?php

namespace app\filters;

use yii\base\{ActionFilter, 
    ErrorException};
use app\traits\ExceptionsTrait;

/**
 * Фиксирует время генерации страницы, объем выделенной памяти и кол-во запросов к БД
 */
class CheckScriptInfoFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * Вычисляет время выполнения скрипта, выделенную память, кол-во отправленных в БД запросов,
     * вставляет рузультат перед закрывающим тегом </body>
     * @param $action выполняемое в данный момент действие
     * @param $result результирующая строка перед отправкой в браузер клиента
     * @return string модифицированная строка ответа
     */
    public function afterAction($action, $result)
    {
        try {
            if (is_string($result) && strpos($result, '</body>')) {
                $logger = \Yii::getLogger();
                
                $pageGenerated = $logger->getElapsedTime();
                $memoryUsage = memory_get_peak_usage(true);
                
                list($sentRequests, $timeRequests) = $logger->getDbProfiling();
                
                $yiiVersion = \Yii::getVersion();
                
                $string = '<p>Page generated: ' . round($pageGenerated, 3) . ' sec. / Memory usage: ' . ($memoryUsage / (1024 * 1024)) . ' Mb / Sent Requests: ' . $sentRequests . ' / Yii version: ' . $yiiVersion . '</p>';
                $result = str_replace('</body>', $string . '</body>', $result);
            }
            return $result;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
