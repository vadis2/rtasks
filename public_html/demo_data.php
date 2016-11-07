<?php
require_once 'php-activerecord/ActiveRecord.php';
require_once 'config/active_record.php';


$tasks =
    [
        [
            'name'           => "Before Start, Before End 2",
            'start_date'     => new ActiveRecord\DateTime('2016-10-03 03:04:05'),
            'end_date'       => new ActiveRecord\DateTime('2017-10-03 03:04:05'),
            'is_repeatable'  => 1,
            'repeating_data' => json_encode(
                [
                    'IS_REPEATABLE'                         => TRUE, //Fixed, always true
                    'REPEATING_ACTIVATE'                    => TRUE, //Fixed, always true
                    'REPEATING_PERIOD'                      => 'daily', //daily|weekly|monthly|yearly
                    'REPEATING_DAILY_EVERY_DAY'             => 2, //1-31 (i.e. 2 means it repeats every second day from now())
                    'REPEATING_DAILY_WORKING_DAY'           => 'N', //'Y'|'N'	('Y' - means it can repeat only on working days - Mon-Fri, 'N' - means every day, including Sat and Sun)
                    'REPEATING_WEEKLY_EVERY_WEEK'           => 2, //1-52 (number of week in year)
                    'REPEATING_WEEKLY_WEEKDAYS'             => 2, //1-7 (weekdays - Mon=1, Tue=2,... 	Sun=7)
                    'REPEATING_MONTHLY_MONTHLY_TYPE'        => 1, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
                    'REPEATING_MONTHLY_MONTHLY_DAY_NUM'     => 4, //1-31 (day in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
                    'REPEATING_MONTHLY_MONTHLY_MONTH_NUM_1' => 9, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
                    'REPEATING_MONTHLY_WEEK_NUM'            => 2, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
                    'REPEATING_MONTHLY_WEEKDAY_NUM'         => 2, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
                    'REPEATING_YEARLY_YEARLY_TYPE'          => 1, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
                    'REPEATING_YEARLY_DAY_NUM'              => 6, //1-31 (day in month - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
                    'REPEATING_YEARLY_MONTH_1'              => 4, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
                    'REPEATING_YEARLY_WEEK_NUM'             => 1, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
                    'REPEATING_YEARLY_WEEKDAY_NUM'          => 3, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
                    'REPEATING_YEARLY_MONTH_2'              => 4, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
                    'REPEATING_CREATED_ON_TIME'             => '11:30', //HH:mm (hour and minute when task need to be created)
                    'REPEATING_REPEAT_UNTIL'                => 'date', //date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
                    'REPEATING_END_DATE'                    => '15.11.2016 16:00', //dd.MM.yyyy HH:mm (this is final end date of task, after this date this current task will not created automatically - used if REPEATING_REPEAT_UNTIL = 'date')
                    'REPEATING_TIMES'                       => 5 //>0 (defines number of occurences - means that this task will be created maximum 5 times regarding the entered parameters - used if REPEATING_REPEAT_UNTIL = 'times')
                ]
            ),
        
        ],
        [
            'name'           => "Before Start, Before End 2",
            'start_date'     => new ActiveRecord\DateTime('2016-10-04 03:04:05'),
            'end_date'       => new ActiveRecord\DateTime('2017-10-04 03:04:05'),
            'is_repeatable'  => 1,
            'repeating_data' => json_encode(
                [
                    'IS_REPEATABLE'                         => TRUE, //Fixed, always true
                    'REPEATING_ACTIVATE'                    => TRUE, //Fixed, always true
                    'REPEATING_PERIOD'                      => 'daily', //daily|weekly|monthly|yearly
                    'REPEATING_DAILY_EVERY_DAY'             => 2, //1-31 (i.e. 2 means it repeats every second day from now())
                    'REPEATING_DAILY_WORKING_DAY'           => 'N', //'Y'|'N'	('Y' - means it can repeat only on working days - Mon-Fri, 'N' - means every day, including Sat and Sun)
                    'REPEATING_WEEKLY_EVERY_WEEK'           => 2, //1-52 (number of week in year)
                    'REPEATING_WEEKLY_WEEKDAYS'             => 2, //1-7 (weekdays - Mon=1, Tue=2,... 	Sun=7)
                    'REPEATING_MONTHLY_MONTHLY_TYPE'        => 1, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
                    'REPEATING_MONTHLY_MONTHLY_DAY_NUM'     => 4, //1-31 (day in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
                    'REPEATING_MONTHLY_MONTHLY_MONTH_NUM_1' => 9, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
                    'REPEATING_MONTHLY_WEEK_NUM'            => 2, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
                    'REPEATING_MONTHLY_WEEKDAY_NUM'         => 2, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
                    'REPEATING_YEARLY_YEARLY_TYPE'          => 1, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
                    'REPEATING_YEARLY_DAY_NUM'              => 6, //1-31 (day in month - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
                    'REPEATING_YEARLY_MONTH_1'              => 4, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
                    'REPEATING_YEARLY_WEEK_NUM'             => 1, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
                    'REPEATING_YEARLY_WEEKDAY_NUM'          => 3, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
                    'REPEATING_YEARLY_MONTH_2'              => 4, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
                    'REPEATING_CREATED_ON_TIME'             => '11:30', //HH:mm (hour and minute when task need to be created)
                    'REPEATING_REPEAT_UNTIL'                => 'date', //date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
                    'REPEATING_END_DATE'                    => '15.11.2016 16:00', //dd.MM.yyyy HH:mm (this is final end date of task, after this date this current task will not created automatically - used if REPEATING_REPEAT_UNTIL = 'date')
                    'REPEATING_TIMES'                       => 5 //>0 (defines number of occurences - means that this task will be created maximum 5 times regarding the entered parameters - used if REPEATING_REPEAT_UNTIL = 'times')
                ]
            ),
        ],
    ];

$task = Task::createMany($tasks);
//$res = Task::delete_all();
//var_dump($res);