<?php

require_once(dirname(__FILE__) . '/../config/Config.php');
require_once(dirname(__FILE__) . '/../php-activerecord/ActiveRecord.php');
require_once(dirname(__FILE__) . '/../config/active_record.php');
require_once(dirname(__FILE__) . '/../helper/Datetime_helper.php');
include_once(dirname(__FILE__) . '/../core/View.php');
include_once(dirname(__FILE__) . '/../libs/Carbon/src/Carbon/Carbon.php');


/**
 * Class Controller
 * don't remember add controller to index.php
 *
 * @todo divide this controller on 5 parts: main/management + daily + weekly + monthly + yearly
 */
class Controller
{
    public static function getStart()
    {
        return START;
    
//        return date('Y-m-d H:i:s');
    }

    
    public static function getActiveTasks()
    {
        $from   = self::getStart();
        $to     = addDays($from, INTERVAL);
        $string = "start_date < '$to' AND start_date > '$from'";
        $tasks  = Task::find('all', ['conditions' => $string]);
        
        return $tasks;
    }
    
    public static function calculate()
    {
        $tasksActive = self::getActiveTasks();
        if (count($tasksActive) == 0) {
            return TRUE;
        }
        
        foreach ($tasksActive as $task) {
            if ($task->is_repeatable == 0) {
                $res = self::processNonRepeatable($task);
            }
            
            if ($task->is_repeatable == 1) {
                $res = self::processRepeatable($task);
            }
        }
        
        return TRUE;
    }
    
    public static function processNonRepeatable($task)
    {
        $attributes = [
            'name'       => $task->name,
            'start_date' => new ActiveRecord\DateTime($task->start_date),
        ];
        
        $incomingTask = new Incoming_task($attributes);
        $incomingTask->save();
        
        return TRUE;
    }
    
    public static function processRepeatable($task)
    {
        $data = json_decode($task->repeating_data); // this is object
        switch ($data->REPEATING_PERIOD) {
            case 'daily':
                $res = self::processDailyRepeatable($task, $data);
                break;
            
            case 'weekly':
                $res = self::processWeeklyRepeatable($task, $data);
                break;
            
            case 'monthly':
                $res = self::processMonthlyRepeatable($task, $data);
                break;
            
            case 'yearly':
                $res = self::processYearlyRepeatable($task, $data);
                break;
        }
        
        return TRUE;
    }
    
    public static function processDailyRepeatable($task, $data)
    {
        $REPEATING_REPEAT_UNTIL = $data->REPEATING_REPEAT_UNTIL;       // date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
        
        switch ($REPEATING_REPEAT_UNTIL) {
            case 'date':
                $res = self::processDailyDate($task, $data);
                break;
            
            case 'endless':
                $res = self::processDailyEndless($task, $data);
                break;
            
            case 'times':
                $res = self::processDailyTimes($task, $data);
                break;
        }
        
        return TRUE;
    }
    
    public static function processWeeklyRepeatable($task, $data)
    {
        $REPEATING_REPEAT_UNTIL = $data->REPEATING_REPEAT_UNTIL;       // date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
        
        switch ($REPEATING_REPEAT_UNTIL) {
            case 'date':
                $res = self::processWeeklyDate($task, $data);
                break;
            
            case 'endless':
                $res = self::processWeeklyEndless($task, $data);
                break;
            
            case 'times':
                $res = self::processWeeklyTimes($task, $data);
                break;
        }
        
        return TRUE;
    }
    
    public static function processMonthlyRepeatable($task, $data)
    {
        $REPEATING_REPEAT_UNTIL = $data->REPEATING_REPEAT_UNTIL;       // date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
        
        switch ($REPEATING_REPEAT_UNTIL) {
            case 'date':
                $res = self::processMonthlyDate($task, $data);
                break;
            
            case 'endless':
                $res = self::processMonthlyEndless($task, $data);
                break;
            
            case 'times':
                $res = self::processMonthlyTimes($task, $data);
                break;
        }
        
        return TRUE;
    }
    
    public static function processYearlyRepeatable($task, $data)
    {
        $REPEATING_REPEAT_UNTIL = $data->REPEATING_REPEAT_UNTIL;       // date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
        
        switch ($REPEATING_REPEAT_UNTIL) {
            case 'date':
                $res = self::processYearlyDate($task, $data);
                break;
            
            case 'endless':
                $res = self::processYearlyEndless($task, $data);
                break;
            
            case 'times':
                $res = self::processYearlyTimes($task, $data);
                break;
        }
        
        return TRUE;
    }
    
    public static function processDailyDate($task, $data)
    {
        $name       = $task->name;
        $start_date = self::calculateStartDaily($task, $data);
        $end_date   = self::calculateEnd($task, $data);
        
        // recording
        while ($start_date < $end_date) {
            $attributes = [
                'name'       => $name,
                'start_date' => $start_date,
                'end_date'   => $end_date,
            ];
            if ($data->REPEATING_DAILY_WORKING_DAY == 'N') {
                $res = self::saveIncoming($attributes);
            } else {
                if ($start_date->format('N') < 6) {
                    $res = self::saveIncoming($attributes);
                }
            }
            
            $start_date->add(new DateInterval('P' . $data->REPEATING_DAILY_EVERY_DAY . 'D'));
        }
        
        return TRUE;
    }
    
    public static function processWeeklyDate($task, $data)
    {
        $name       = $task->name;
        $start_date = self::calculateStartWeekly($task, $data);
        $end_date   = self::calculateEnd($task, $data);
        
        // recording
        while ($start_date < $end_date) {
            $attributes = [
                'name'       => $name,
                'start_date' => $start_date,
                'end_date'   => $end_date,
            ];
            
            $res = self::saveIncoming($attributes);
            
            $start_date->add(new DateInterval('P' . 7 * $data->REPEATING_WEEKLY_EVERY_WEEK . 'D'));
        }
        
        return TRUE;
    }
    
    public static function processMonthlyDate($task, $data)
    {
        $name           = $task->name;
        $startCalculate = self::calculateStart($task, $data);
        $startDate      = self::calculateRealStart($task, $data, $startCalculate);
        $endDate        = self::calculateEnd($task, $data);
        
        if ($startDate < $endDate) {
            
            // recording
            while ($startDate < $endDate) {
                $attributes = [
                    'name'       => $name,
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                ];
                
                $res = self::saveIncoming($attributes);
                
                if ($data->REPEATING_MONTHLY_MONTHLY_TYPE == 1) {
                    $startDate = self::getPlannedMonthlyDate($data, $startDate, TRUE);
                } else {
                    $startDate = self::getPlannedMonthlyWeek($data, $startDate, TRUE);
                }
            }
        }
        
        return TRUE;
    }
    
    public static function processYearlyDate($task, $data)
    {
        $name           = $task->name;
        $startCalculate = self::calculateStart($task, $data);
        $startDate      = self::calculateRealStart($task, $data, $startCalculate);
        $endDate        = self::calculateEnd($task, $data);
        
        if ($startDate < $endDate) {
            
            // recording
            while ($startDate < $endDate) {
                $attributes = [
                    'name'       => $name,
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                ];
                
                $res = self::saveIncoming($attributes);
                
                if ($data->REPEATING_YEARLY_YEARLY_TYPE == 1) {
                    $startDate = self::getPlannedYearlyDate($data, $startDate, TRUE);
                } else {
                    $startDate = self::getPlannedYearlyWeek($data, $startDate, TRUE);
                }
            }
        }
        
        return TRUE;
    }
    
    public static function processDailyEndless($task, $data)
    {
        $name       = $task->name;
        $start_date = self::calculateStartDaily($task, $data);
        $end_date   = self::calculateEnd($task, $data);
        
        // recording
        while ($start_date < $end_date) {
            $attributes = [
                'name'       => $name,
                'start_date' => $start_date,
                'end_date'   => $end_date,
            ];
            if ($data->REPEATING_DAILY_WORKING_DAY == 'N') {
                $res = self::saveIncoming($attributes);
            } else {
                if ($start_date->format('N') < 6) {
                    $res = self::saveIncoming($attributes);
                }
            }
            
            $start_date->add(new DateInterval('P' . $data->REPEATING_DAILY_EVERY_DAY . 'D'));
        }
        
        return TRUE;
    }
    
    public static function processWeeklyEndless($task, $data)
    {
        $name       = $task->name;
        $start_date = self::calculateStartWeekly($task, $data);
        $end_date   = self::calculateEnd($task, $data);
        
        // recording
        while ($start_date < $end_date) {
            $attributes = [
                'name'       => $name,
                'start_date' => $start_date,
                'end_date'   => $end_date,
            ];
            
            $res = self::saveIncoming($attributes);
            
            $start_date->add(new DateInterval('P' . 7 * $data->REPEATING_DAILY_EVERY_DAY . 'D'));
        }
        
        return TRUE;
    }
    
    /**
     * @param $task
     * @param $data
     *
     * @return bool
     *
     * @todo    refactor: the same as processMonthlyDate
     */
    public static function processMonthlyEndless($task, $data)
    {
        $name           = $task->name;
        $startCalculate = self::calculateStart($task, $data);
        $startDate      = self::calculateRealStart($task, $data, $startCalculate);
        $endDate        = self::calculateEnd($task, $data);
        
        if ($startDate < $endDate) {
            
            // recording
            while ($startDate < $endDate) {
                $attributes = [
                    'name'       => $name,
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                ];
                
                $res = self::saveIncoming($attributes);
                
                if ($data->REPEATING_MONTHLY_MONTHLY_TYPE == 1) {
                    $startDate = self::getPlannedMonthlyDate($data, $startDate, TRUE);
                } else {
                    $startDate = self::getPlannedMonthlyWeek($data, $startDate, TRUE);
                }
            }
        }
        
        return TRUE;
    }
    
    public static function processYearlyEndless($task, $data)
    {
        $name           = $task->name;
        $startCalculate = self::calculateStart($task, $data);
        $startDate      = self::calculateRealStart($task, $data, $startCalculate);
        $endDate        = self::calculateEnd($task, $data);
        
        if ($startDate < $endDate) {
            
            // recording
            while ($startDate < $endDate) {
                $attributes = [
                    'name'       => $name,
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                ];
                
                $res = self::saveIncoming($attributes);
                
                if ($data->REPEATING_YEARLY_YEARLY_TYPE == 1) {
                    $startDate = self::getPlannedYearlyDate($data, $startDate, TRUE);
                } else {
                    $startDate = self::getPlannedYearlyWeek($data, $startDate, TRUE);
                }
            }
        }
        
        return TRUE;
    }
    
    
    public static function processDailyTimes($task, $data)
    {
        $name       = $task->name;
        $start_date = self::calculateStartDaily($task, $data);
        $end_date   = self::calculateEnd($task, $data);
        
        // recording
        $i = 0;
        while ($start_date < $end_date and $i < $data->REPEATING_TIMES) {
            $attributes = [
                'name'       => $name,
                'start_date' => $start_date,
                'end_date'   => $end_date,
            ];
            if ($data->REPEATING_DAILY_WORKING_DAY == 'N') {
                $res = self::saveIncoming($attributes);
            } else {
                if ($start_date->format('N') < 6) {
                    $res = self::saveIncoming($attributes);
                } else {
                    if ($i != 0) {
                        // empty cycle because weekend day
                        $i--;
                    }
                }
            }
            
            $start_date->add(new DateInterval('P' . $data->REPEATING_DAILY_EVERY_DAY . 'D'));
            $i++;
        }
        
        return TRUE;
    }
    
    public static function processWeeklyTimes($task, $data)
    {
        $name       = $task->name;
        $start_date = self::calculateStartWeekly($task, $data);
        $end_date   = self::calculateEnd($task, $data);
        
        // recording
        $i = 0;
        while ($start_date < $end_date and $i < $data->REPEATING_TIMES) {
            $attributes = [
                'name'       => $name,
                'start_date' => $start_date,
                'end_date'   => $end_date,
            ];
            
            $res = self::saveIncoming($attributes);
            
            $start_date->add(new DateInterval('P' . 7 * $data->REPEATING_DAILY_EVERY_DAY . 'D'));
            $i++;
        }
        
        return TRUE;
    }
    
    /**
     * @param object $task
     * @param object $data
     *
     * @return bool
     *
     */
    public static function processMonthlyTimes($task, $data)
    {
        $name           = $task->name;
        $startCalculate = self::calculateStart($task, $data);
        $startDate      = self::calculateRealStart($task, $data, $startCalculate);
        $endDate        = self::calculateEnd($task, $data);
        
        // counter of times
        $i     = 0;
        $limit = $data->REPEATING_TIMES;
        
        if ($startDate < $endDate) {
            
            // recording
            while ($startDate < $endDate and $i < $limit) {
                $attributes = [
                    'name'       => $name,
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                ];
                
                $res = self::saveIncoming($attributes);
                $i++;
                
                if ($data->REPEATING_MONTHLY_MONTHLY_TYPE == 1) {
                    $startDate = self::getPlannedMonthlyDate($data, $startDate, TRUE);
                } else {
                    $startDate = self::getPlannedMonthlyWeek($data, $startDate, TRUE);
                }
            }
        }
        
        return TRUE;
    }
    
    public static function processYearlyTimes($task, $data)
    {
        $name           = $task->name;
        $startCalculate = self::calculateStart($task, $data);
        $startDate      = self::calculateRealStart($task, $data, $startCalculate);
        $endDate        = self::calculateEnd($task, $data);
        
        // counter of times
        $i     = 0;
        $limit = $data->REPEATING_TIMES;
        
        if ($startDate < $endDate) {
            
            // recording
            while ($startDate < $endDate and $i < $limit) {
                $attributes = [
                    'name'       => $name,
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                ];
                
                $res = self::saveIncoming($attributes);
                $i++;
                
                if ($data->REPEATING_YEARLY_YEARLY_TYPE == 1) {
                    $startDate = self::getPlannedYearlyDate($data, $startDate, TRUE);
                } else {
                    $startDate = self::getPlannedYearlyWeek($data, $startDate, TRUE);
                }
            }
        }
        
        return TRUE;
    }
    
    public static function calculateStartDaily($task, $data)
    {
        $start_date_time = $task->start_date->format('H:i:s');
        $start_time      = $data->REPEATING_CREATED_ON_TIME . ':00';
        
        
        if ($start_date_time > $start_time) {
            $new_date_string = $task->start_date->add(new DateInterval('P' . 1 . 'D'))->format('Y-m-d') . ' ' . $start_time;
            $new_date        = new DateTime($new_date_string);
            
            return $new_date;
        } elseif ($start_date_time == $start_time) {
            return $task->start_date;
        } else {
            $new_date_string = $task->start_date->format('Y-m-d') . ' ' . $start_time;
            $new_date        = new DateTime($new_date_string);
            
            return $new_date;
        }
    }
    
    public static function calculateStartWeekly($task, $data)
    {
        // do start day has the same week day as must be?
        $weekDayStartDate = $task->start_date->format('N');
        
        $start_date_time = $task->start_date->format('H:i:s');
        $start_time      = $data->REPEATING_CREATED_ON_TIME . ':00';
        
        if ($weekDayStartDate == $data->REPEATING_WEEKLY_WEEKDAYS) {
            // the same week day
            // clarify time
            if ($start_date_time > $start_time) {
                $new_date_string = $task->start_date->add(new DateInterval('P' . 7 . 'D'))->format('Y-m-d') . ' ' . $start_time;
                $new_date        = new DateTime($new_date_string);
                
                return $new_date;
            } elseif ($start_date_time == $start_time) {
                return $task->start_date;
            } else {
                $new_date_string = $task->start_date->format('Y-m-d') . ' ' . $start_time;
                $new_date        = new DateTime($new_date_string);
                
                return $new_date;
            }
        }
        
        // other week day
        $daysArray = [
            '1' => 'monday',
            '2' => 'tuesday',
            '3' => 'wednesday',
            '4' => 'thursday',
            '5' => 'friday',
            '6' => 'saturday',
            '7' => 'sunday',
        ];
        
        $weekDayString   = 'next ' . $daysArray[$data->REPEATING_WEEKLY_WEEKDAYS];
        $new_date_string = $task->start_date->modify($weekDayString)->format('Y-m-d') . ' ' . $start_time;;
        $new_date = new DateTime($new_date_string);
        
        return $new_date;
    }
    
    public static function calculateRealStart($task, $data, $startCalculate)
    {
        switch ($data->REPEATING_PERIOD) {
            case 'monthly':
                // which type of start:
                // 1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
                switch ($data->REPEATING_MONTHLY_MONTHLY_TYPE) {
                    case 1:
                        $res = self::calculateStartMonthlyDay($task, $data, $startCalculate);
                        break;
                    case 2:
                        $res = self::calculateStartMonthlyWeek($task, $data, $startCalculate);
                        break;
                }
                break;
            
            case 'yearly':
                // which type of start:
                // 1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
                switch ($data->REPEATING_YEARLY_YEARLY_TYPE) {
                    case 1:
                        $res = self::calculateStartYearlyDay($task, $data, $startCalculate);
                        break;
                    case 2:
                        $res = self::calculateStartYearlyWeek($task, $data, $startCalculate);
                        break;
                }
                break;
        }
        
        return $res;
    }
    
    public static function calculateStartMonthlyDay($task, $data, $startCalculate)
    {
        // logic:
        // get planned date
        // compare planned date with $startCalculate
        
        $plannedDate = self::getPlannedMonthlyDate($data, $startCalculate, FALSE);
        
        if ($plannedDate >= $startCalculate) {
            return $plannedDate;
        }
        
        $plannedDate = self::getPlannedMonthlyDate($data, $startCalculate, TRUE);
        
        return $plannedDate;
    }
    
    public static function calculateStartYearlyDay($task, $data, $startCalculate)
    {
        // logic:
        // get planned date
        // compare planned date with $startCalculate
        
        $plannedDate = self::getPlannedYearlyDate($data, $startCalculate, FALSE);
        
        if ($plannedDate >= $startCalculate) {
            return $plannedDate;
        }
        
        $plannedDate = self::getPlannedYearlyDate($data, $startCalculate, TRUE);
        
        return $plannedDate;
    }
    
    public static function calculateStartMonthlyWeek($task, $data, $startCalculate)
    {
        // logic:
        // get planned date
        // compare planned date with $startCalculate
        
        $plannedDate = self::getPlannedMonthlyWeek($data, $startCalculate, FALSE);
        
        if ($plannedDate >= $startCalculate) {
            return $plannedDate;
        }
        
        $plannedDate = self::getPlannedMonthlyWeek($data, $startCalculate, TRUE);
        
        return $plannedDate;
    }
    
    public static function calculateStartYearlyWeek($task, $data, $startCalculate)
    {
        // logic:
        // get planned date
        // compare planned date with $startCalculate
        
        $plannedDate = self::getPlannedYearlyWeek($data, $startCalculate, FALSE);
        
        if ($plannedDate >= $startCalculate) {
            return $plannedDate;
        }
        
        // add 1 year
        $plannedDate = self::getPlannedYearlyWeek($data, $startCalculate, TRUE);
        
        return $plannedDate;
    }
    
    public function getPlannedMonthlyDate($data, $startCalculate, $next)
    {
        $startCalculateString = $startCalculate->format('Y-m-d');
        $startCalculateArray  = explode('-', $startCalculateString);
        $plannedDateArray     = $startCalculateArray;
        $plannedDateArray[2]  = $data->REPEATING_MONTHLY_MONTHLY_DAY_NUM;
        
        if ($next) {
            if ($plannedDateArray[1] == 12) {
                $plannedDateArray[1] = 1;
                $plannedDateArray[0] += 1;
            } else {
                $plannedDateArray[1] += 1;
            }
        }
        $plannedDateString = implode('-', $plannedDateArray) . ' ' . $data->REPEATING_CREATED_ON_TIME;
        
        return new DateTime($plannedDateString);
    }
    
    public function getPlannedYearlyDate($data, $startCalculate, $next)
    {
        $plannedYear      = $startCalculate->format('Y');
        $plannedMonth     = $data->REPEATING_YEARLY_MONTH_1 + 1;
        $plannedDay       = $data->REPEATING_YEARLY_DAY_NUM;
        $plannedString    = $plannedYear . '-' . $plannedMonth . '-' . $plannedDay . ' ' . $data->REPEATING_CREATED_ON_TIME;
        $plannedDayObject = new DateTime($plannedString);
        
        // 2 cases:
        // planned < start:
        //      +1 year
        // planned > start
        
        if ($plannedDayObject < $startCalculate) {
            while ($plannedDayObject < $startCalculate) {
                $plannedYear += 1;
                
                $plannedString    = $plannedYear . '-' . $plannedMonth . '-' . $plannedDay . ' ' . $data->REPEATING_CREATED_ON_TIME;
                $plannedDayObject = new DateTime($plannedString);
            }
        }
        
        if ($next) {
            $plannedYear += 1;
            
            $plannedString    = $plannedYear . '-' . $plannedMonth . '-' . $plannedDay . ' ' . $data->REPEATING_CREATED_ON_TIME;
            $plannedDayObject = new DateTime($plannedString);
        }
        
        return $plannedDayObject;
    }
    
    public function getPlannedMonthlyWeek($data, $startCalculate, $next)
    {
        $daysArray = [
            '0' => 'monday',
            '1' => 'tuesday',
            '2' => 'wednesday',
            '3' => 'thursday',
            '4' => 'friday',
            '5' => 'saturday',
            '6' => 'sunday',
        ];
        
        $countStringArray = [
            '0' => 'first',
            '1' => 'second',
            '2' => 'third',
            '3' => 'fourth',
            '4' => 'fifth',
        ];
        
        $monthsArray = [
            '1'  => 'January',
            '2'  => 'February',
            '3'  => 'March',
            '4'  => 'April',
            '5'  => 'May',
            '6'  => 'June',
            '7'  => 'July',
            '8'  => 'August',
            '9'  => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];
        
        $string_3 = $startCalculate->format('F Y');
        $string_1 = $countStringArray[$data->REPEATING_MONTHLY_WEEK_NUM];
        $string_2 = $daysArray[$data->REPEATING_MONTHLY_WEEKDAY_NUM];
        $string   = $string_1 . ' ' . $string_2 . ' ' . $string_3;
        
        $plannedDateString = date('Y-m-d', strtotime($string));
        
        if ($next) {
            $plannedDateArray = explode('-', $plannedDateString);
            if ($plannedDateArray[1] == 12) {
                $plannedDateArray[1] = 1;
                $plannedDateArray[0] += 1;
            } else {
                $plannedDateArray[1] += 1;
            }
            $string_4          = $plannedDateArray[0];
            $string_3          = $monthsArray[$plannedDateArray[1]];
            $string            = $string_1 . ' ' . $string_2 . ' ' . $string_3 . ' ' . $string_4;
            $plannedDateString = date('Y-m-d', strtotime($string));
        }
        
        $plannedDateString .= ' ' . $data->REPEATING_CREATED_ON_TIME;
        
        return new DateTime($plannedDateString);
    }
    
    public function getPlannedYearlyWeek($data, $startCalculate, $next)
    {
        $daysArray = [
            '0' => 'monday',
            '1' => 'tuesday',
            '2' => 'wednesday',
            '3' => 'thursday',
            '4' => 'friday',
            '5' => 'saturday',
            '6' => 'sunday',
        ];
        
        $countStringArray = [
            '0' => 'first',
            '1' => 'second',
            '2' => 'third',
            '3' => 'fourth',
            '4' => 'fifth',
        ];
        
        $monthsArray = [
            '0'  => 'January',
            '1'  => 'February',
            '2'  => 'March',
            '3'  => 'April',
            '4'  => 'May',
            '5'  => 'June',
            '6'  => 'July',
            '7'  => 'August',
            '8'  => 'September',
            '9' => 'October',
            '10' => 'November',
            '11' => 'December',
        ];
        
        $plannedYear             = $startCalculate->format('Y');
        $plannedMonth            = $monthsArray[$data->REPEATING_YEARLY_MONTH_2];
        $plannedWeekNumberString = $countStringArray[$data->REPEATING_YEARLY_WEEK_NUM];
        $plannedWeekdayString    = $daysArray[$data->REPEATING_YEARLY_WEEKDAY_NUM];
        $string                  = $plannedWeekNumberString . ' ' . $plannedWeekdayString . ' ' . $plannedMonth . ' ' . $plannedYear;
        $plannedString           = date('Y-m-d', strtotime($string));
        $plannedString .= ' ' . $data->REPEATING_CREATED_ON_TIME;
        $plannedDayObject = new DateTime($plannedString);
        
        // 2 cases:
        // planned < start:
        //      +1 year
        // planned > start
        
        if ($plannedDayObject < $startCalculate) {
            while ($plannedDayObject < $startCalculate) {
                $plannedYear += 1;
                $string        = $plannedWeekNumberString . ' ' . $plannedWeekdayString . ' ' . $plannedMonth . ' ' . $plannedYear;
                $plannedString = date('Y-m-d', strtotime($string));
                $plannedString .= ' ' . $data->REPEATING_CREATED_ON_TIME;
                $plannedDayObject = new DateTime($plannedString);
            }
        }
        
        if ($next) {
            $plannedYear += 1;
            $string        = $plannedWeekNumberString . ' ' . $plannedWeekdayString . ' ' . $plannedMonth . ' ' . $plannedYear;
            $plannedString = date('Y-m-d', strtotime($string));
            $plannedString .= ' ' . $data->REPEATING_CREATED_ON_TIME;
            $plannedDayObject = new DateTime($plannedString);
        }
        
        return $plannedDayObject;
    }
    
    /**
     * delete
     */
    public static function _calculateStartMonthlyWeek($task, $data)
    {
        $start_date_time = $task->start_date->format('H:i:s');
        $start_time      = $data->REPEATING_CREATED_ON_TIME . ':00';
        
        // logic:
        // get date of required start (year, month, number order of weekday)
        // compare task start date with date of required start
        $start = self::getStart();
        $now      = new DateTime($start);
        $string_1 = $now->format('F Y');
        var_dump($string_1);
        
        
        echo "inn";
        echo date('Y-m-d', strtotime('+1 month'));
        echo date('Y-m-d', strtotime('Monday next week 2016-11-01'));
        echo date('Y-m-d', strtotime('second saturday of november 2016'));
        echo date('Y-m-d', strtotime('second saturday of ' . $string_1));
        exit();
        // does start week number has the same week number as must be?
        $dtString            = $task->start_date->format('Y-m-d');
        $dt                  = \Carbon\Carbon::parse($dtString);
        $weekNumberStartDate = $dt->weekOfMonth;
        
        if ($weekNumberStartDate == $data->REPEATING_MONTHLY_WEEK_NUM) {
            // does start day has the same week day as must be?
            $weekDayStartDate = $task->start_date->format('N');
            
            if ($weekDayStartDate == $data->REPEATING_MONTHLY_WEEKDAY_NUM) {
                // the same week day
                // clarify time
                if ($start_date_time > $start_time) {
                    // get next month required week number required day of week
                    $daysArray = [
                        '1' => 'monday',
                        '2' => 'tuesday',
                        '3' => 'wednesday',
                        '4' => 'thursday',
                        '5' => 'friday',
                        '6' => 'saturday',
                        '7' => 'sunday',
                    ];
                    
                    echo date('Y-m-d', strtotime('Monday second week 2016-11-01'));
                    exit();
                    $new_date_string = addMonths($task->start_date, 1)->format('Y-m-d') . ' ' . $start_time;
                    $new_date        = new DateTime($new_date_string);
                    
                    return $new_date;
                } elseif ($start_date_time == $start_time) {
                    return $task->start_date;
                } else {
                    $new_date_string = $task->start_date->format('Y-m-d') . ' ' . $start_time;
                    $new_date        = new DateTime($new_date_string);
                    
                    return $new_date;
                }
            }
        } elseif ($weekNumberStartDate > $data->REPEATING_MONTHLY_WEEK_NUM) {
            
        } elseif ($weekNumberStartDate > $data->REPEATING_MONTHLY_WEEK_NUM) {
            
        }
        
        var_dump($weekNumberStartDate);
        
        exit();
        
        
        $start_date_time = $task->start_date->format('H:i:s');
        $start_time      = $data->REPEATING_CREATED_ON_TIME . ':00';
        
        
        // other week day
        $daysArray = [
            '1' => 'monday',
            '2' => 'tuesday',
            '3' => 'wednesday',
            '4' => 'thursday',
            '5' => 'friday',
            '6' => 'saturday',
            '7' => 'sunday',
        ];
        
        $weekDayString   = 'next ' . $daysArray[$data->REPEATING_WEEKLY_WEEKDAYS];
        $new_date_string = $task->start_date->modify($weekDayString)->format('Y-m-d') . ' ' . $start_time;;
        $new_date = new DateTime($new_date_string);
        
        return $new_date;
    }
    
    /**
     * for next refactoring
     *
     * @param DateTime $task
     * @param array    $data
     *
     * @return DateTime
     */
    public function calculateStart($task, $data)
    {
        // logic:
        // interval ---------------------|start-------- interval---------
        // task     ------|start--------------------------task-----------
        // return   ---------------------|max of starts------------------
    
        $start = self::getStart();
        $startInterval = new DateTime($start);
        $startTask     = $task->start_date;
        
        return $startTask > $startInterval ? $startTask : $startInterval;
    }
    
    public static function calculateEnd($task, $data)
    {
        switch ($data->REPEATING_REPEAT_UNTIL) {
            case 'date':
                $end_date = convert($data->REPEATING_END_DATE);
                $end_date = new ActiveRecord\DateTime($end_date);
                $end_date = $end_date > $task->end_date ? $task->end_date : $end_date;
    
                $start = self::getStart();
                $interval_end = addDays($start, INTERVAL);
                $interval_end = new ActiveRecord\DateTime($interval_end);
                $end_date     = $end_date > $interval_end ? $interval_end : $end_date;
                
                break;
            
            case 'endless':
                $start = self::getStart();
                $interval_end = addDays($start, INTERVAL);
                $interval_end = new ActiveRecord\DateTime($interval_end);
                $end_date     = $task->end_date > $interval_end ? $interval_end : $task->end_date;
                
                break;
            
            case 'times':
                $start = self::getStart();
                $interval_end = addDays($start, INTERVAL);
                $interval_end = new ActiveRecord\DateTime($interval_end);
                $end_date     = $task->end_date > $interval_end ? $interval_end : $task->end_date;
                
                break;
        }
        
        return $end_date;
    }
    
    public static function saveIncoming($attributes)
    {
        $incomingTask = new Incoming_task($attributes);
        $incomingTask->save();
        
        return TRUE;
    }
    
}


