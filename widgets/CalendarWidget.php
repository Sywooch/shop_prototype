<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с календарем
 */
class CalendarWidget extends AbstractBaseWidget
{
    /**
     * @var int номер года, 4 цифры
     */
    private $year;
    /**
     * @var int номер месяца без ведущего нуля
     */
    private $month;
    /**
     * @var DateTime, созданный из переданных данных
     */
    private $period;
    /**
     * @var string имя HTML шаблона
     */
    private $template;
    
    public function run()
    {
        try {
            if (empty($this->year)) {
                throw new ErrorException($this->invalidError('year'));
            }
            
            if (empty($this->month)) {
                throw new ErrorException($this->invalidError('month'));
            }
            
            $this->period = new \DateTime(sprintf('%s-%s-1', $this->year, $this->month));
            
            $renderArray = [];
            
            $renderArray['dayNames'] = $this->getDayNames();
            
            $days = $this->getDaysInMonth();
            $week = [];
            
            for ($day=1; $day <= 7; $day++) {
                if ($this->period->format('N') == $day) {
                    $week[] = ['number'=>$this->period->format('d'), 'timestamp'=>$this->period->getTimestamp()];
                    $this->period->modify('+1 day');
                    $days--;
                } else {
                    $week[] = '';
                }
                
                if ($day === 7 || $days == 0) {
                    if (count($week) < 7) {
                        $filler = array_fill(0, 7 - count($week), '');
                        $week = array_merge($week, $filler);
                    }
                    $renderArray['month'][] = $week;
                    $week = [];
                    $day = 0;
                }
                
                if ($days == 0) {
                    break;
                }
            }
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив с заголовками дней недели
     * @return array
     */
    public function getDayNames()
    {
        try {
            return [
                \Yii::t('base', 'Mon'),
                \Yii::t('base', 'Tue'),
                \Yii::t('base', 'Wed'),
                \Yii::t('base', 'Thu'),
                \Yii::t('base', 'Fri'),
                \Yii::t('base', 'Sat'),
                \Yii::t('base', 'Sun'),
            ];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает номер года
     * @return int
     */
    public function getYear(): int
    {
        try {
            return $this->period->format('Y');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает номер месяца
     * @return int
     */
    public function getMonth(): int
    {
        try {
            return $this->period->format('m');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает название месяца
     * @return string
     */
    public function getMonthVerb(): string
    {
        try {
            return $this->period->format('F');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает количество дней в месяце
     * @return int
     */
    public function getDaysInMonth(): int
    {
        try {
            return $this->period->format('t');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает порядковый номер дня недели первого числа месяца
     * @return int
     */
    public function getRunningDay(): int
    {
        try {
            return $this->period->format('N');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает номер года свойству CalendarWidget::year
     * @param int $year
     */
    public function setYear(int $year)
    {
        try {
            if (preg_match('/^[0-9]{4}$/', $year) !== 1) {
                throw new ErrorException($this->invalidError('year'));
            }
            
            $this->year = $year;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает номер месяца свойству CalendarWidget::month
     * @param int $month
     */
    public function setMonth(int $month)
    {
        try {
            if (preg_match('/^[0-9]{1,2}$/', $month) !== 1) {
                throw new ErrorException($this->invalidError('month'));
            }
            
            $this->month = $month;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству CalendarWidget::template
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        try {
            $this->template = $template;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
