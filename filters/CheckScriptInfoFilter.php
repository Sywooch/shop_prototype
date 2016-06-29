<?php

namespace app\filters;

use yii\base\ActionFilter;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;

/**
 * Фиксирует время генерации страницы, объем выделенной памяти и кол-во запросов к БД
 */
class CheckScriptInfoFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * Время начала выполнения скрипта
     */
    private $_startTime;
    
    /**
     * Сохраняет время начала выполнения скрипта
     * @param $action выполняемое в данный момент действие
     * @return parent result
     */
    public function beforeAction($action)
    {
        try {
            $this->_startTime = microtime(true);
            return parent::beforeAction($action);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
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
                $pageGenerated = microtime(true) - $this->_startTime;
                $memoryUsage = memory_get_usage(true);
                
                if (empty(\Yii::$app->params['fixSentRequests'])) {
                    throw new ErrorException('Не установлена переменная fixSentRequests!');
                }
                $sentRequests = \Yii::$app->params['fixSentRequests'];
                
                $string = '<p>Page generated: ' . round($pageGenerated, 3) . ' sec. / Memory usage: ' . ($memoryUsage / (1024 * 1024)) . ' Mb / Sent Requests: ' . $sentRequests . '</p>';
                $result = str_replace('</body>', $string . '</body>', $result);
            }
            return $result;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
