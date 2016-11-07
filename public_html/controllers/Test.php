<?php
require_once(dirname(__FILE__) . '/../php-activerecord/ActiveRecord.php');
require_once(dirname(__FILE__) . '/../config/active_record.php');
require_once(dirname(__FILE__) . '/../helper/Datetime_helper.php');
include_once(dirname(__FILE__) . '/../core/View.php');
include_once(dirname(__FILE__) . 'Controller.php');

//include_once(dirname(__FILE__) . '../demo_data/last_tasks.php');

class Test
{
    public function start()
    {
        $tasksAll      = Task::find('all');
        $tasksIncoming = Incoming_task::find('all');
//        var_dump($tasksAll);
        $tasksActive   = Controller::getActiveTasks();
//        var_dump($tasksActive);
//        exit();
    
        $data                  = [];
        $data['tasksAll']      = $tasksAll;
        $data['tasksIncoming'] = $tasksIncoming;
        $data['tasksActive']   = $tasksActive;
        
        $view = new View();
        $view->generate('test.php', $data);
    }
    
    public function clearTasks()
    {
        $res = Task::delete_all();
        
        $this->start();
    }
    
    public function clearIncomingTasks()
    {
        $res = Incoming_task::delete_all();
        
        $this->start();
    }
    
    public function clearAllTasks()
    {
        $res1 = Task::delete_all();
        $res2 = Incoming_task::delete_all();
        
        $this->start();
    }
    
    public function calculate()
    {
        $res = Controller::calculate();
        
        $this->start();
        
    }
    
    public function addLastTasks()
    {
        $tasks =
            [
                [
                    'name'           => "Task Start Before Interval Start, Task End Before Interval Start",
                    'start_date'     => new ActiveRecord\DateTime('2016-09-03 03:04:05'),
                    'end_date'       => new ActiveRecord\DateTime('2016-09-05 03:04:05'),
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
        Task::createMany($tasks);
        
        $this->start();
    }
    
    public function addFutureTasks()
    {
        $tasks =
            [
                [
                    'name'           => "Task Start After Interval End",
                    'start_date'     => new ActiveRecord\DateTime('2017-09-03 03:04:05'),
                    'end_date'       => new ActiveRecord\DateTime('2017-09-05 03:04:05'),
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
        Task::createMany($tasks);
        
        $this->start();
    }
    
    public function addDailyRepeatableTasks()
    {
        $tasks =
            [
                [
                    'name'           => "Daily Task 1",
                    'start_date'     => new ActiveRecord\DateTime('2016-10-03 03:04:05'),
                    'end_date'       => new ActiveRecord\DateTime('2017-09-05 03:04:05'),
                    'is_repeatable'  => 1,
                    'repeating_data' => json_encode(
                        [
                            'IS_REPEATABLE'                         => TRUE, //Fixed, always true
                            'REPEATING_ACTIVATE'                    => TRUE, //Fixed, always true
                            'REPEATING_PERIOD'                      => 'daily', //daily|weekly|monthly|yearly
                            'REPEATING_DAILY_EVERY_DAY'             => 2, //1-31 (i.e. 2 means it repeats every second day from now())
                            'REPEATING_DAILY_WORKING_DAY'           => 'Y', //'Y'|'N'	('Y' - means it can repeat only on working days - Mon-Fri, 'N' - means every day, including Sat and Sun)
                            'REPEATING_CREATED_ON_TIME'             => '02:30', //HH:mm (hour and minute when task need to be created)
                            'REPEATING_REPEAT_UNTIL'                => 'times', //date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
                            'REPEATING_END_DATE'                    => '15.11.2016 16:00', //dd.MM.yyyy HH:mm (this is final end date of task, after this date this current task will not created automatically - used if REPEATING_REPEAT_UNTIL = 'date')
                            'REPEATING_TIMES'                       => 5, //>0 (defines number of occurences - means that this task will be created maximum 5 times regarding the entered parameters - used if REPEATING_REPEAT_UNTIL = 'times')
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
                        ]
                    ),
                
                ],
//                [
//                    'name'           => "Daily Task 2",
//                    'start_date'     => new ActiveRecord\DateTime('2016-10-04 03:04:05'),
//                    'end_date'       => new ActiveRecord\DateTime('2017-09-05 03:04:05'),
//                    'is_repeatable'  => 1,
//                    'repeating_data' => json_encode(
//                        [
//                            'IS_REPEATABLE'                         => TRUE, //Fixed, always true
//                            'REPEATING_ACTIVATE'                    => TRUE, //Fixed, always true
//                            'REPEATING_PERIOD'                      => 'daily', //daily|weekly|monthly|yearly
//                            'REPEATING_DAILY_EVERY_DAY'             => 2, //1-31 (i.e. 2 means it repeats every second day from now())
//                            'REPEATING_DAILY_WORKING_DAY'           => 'N', //'Y'|'N'	('Y' - means it can repeat only on working days - Mon-Fri, 'N' - means every day, including Sat and Sun)
//                            'REPEATING_CREATED_ON_TIME'             => '11:30', //HH:mm (hour and minute when task need to be created)
//                            'REPEATING_REPEAT_UNTIL'                => 'endless', //date|endless (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
//                            'REPEATING_END_DATE'                    => '15.11.2016 16:00', //dd.MM.yyyy HH:mm (this is final end date of task, after this date this current task will not created automatically - used if REPEATING_REPEAT_UNTIL = 'date')
//                            'REPEATING_TIMES'                       => 0, //>0 (defines number of occurences - means that this task will be created maximum 5 times regarding the entered parameters - used if REPEATING_REPEAT_UNTIL = 'times')
//                            'REPEATING_WEEKLY_EVERY_WEEK'           => 2, //1-52 (number of week in year)
//                            'REPEATING_WEEKLY_WEEKDAYS'             => 2, //1-7 (weekdays - Mon=1, Tue=2,... 	Sun=7)
//                            'REPEATING_MONTHLY_MONTHLY_TYPE'        => 1, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
//                            'REPEATING_MONTHLY_MONTHLY_DAY_NUM'     => 4, //1-31 (day in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
//                            'REPEATING_MONTHLY_MONTHLY_MONTH_NUM_1' => 9, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
//                            'REPEATING_MONTHLY_WEEK_NUM'            => 2, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
//                            'REPEATING_MONTHLY_WEEKDAY_NUM'         => 2, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
//                            'REPEATING_YEARLY_YEARLY_TYPE'          => 1, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
//                            'REPEATING_YEARLY_DAY_NUM'              => 6, //1-31 (day in month - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
//                            'REPEATING_YEARLY_MONTH_1'              => 4, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
//                            'REPEATING_YEARLY_WEEK_NUM'             => 1, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
//                            'REPEATING_YEARLY_WEEKDAY_NUM'          => 3, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
//                            'REPEATING_YEARLY_MONTH_2'              => 4, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
//                        ]
//                    ),
//
//                ],
            ];
        Task::createMany($tasks);
        
        $this->start();
    }

    public function addWeeklyRepeatableTasks()
    {
        $tasks =
            [
                [
                    'name'           => "Weekly Task 1",
                    'start_date'     => new ActiveRecord\DateTime('2016-10-03 03:04:05'),
                    'end_date'       => new ActiveRecord\DateTime('2017-09-05 03:04:05'),
                    'is_repeatable'  => 1,
                    'repeating_data' => json_encode(
                        [
                            'IS_REPEATABLE'                         => TRUE, //Fixed, always true
                            'REPEATING_ACTIVATE'                    => TRUE, //Fixed, always true
                            'REPEATING_PERIOD'                      => 'weekly', //daily|weekly|monthly|yearly
                            'REPEATING_WEEKLY_EVERY_WEEK'           => 2, //1-52 (number of week in year)
                            'REPEATING_WEEKLY_WEEKDAYS'             => 2, //1-7 (weekdays - Mon=1, Tue=2,... 	Sun=7)
                            'REPEATING_TIMES'                       => 6, //>0 (defines number of occurences - means that this task will be created maximum 5 times regarding the entered parameters - used if REPEATING_REPEAT_UNTIL = 'times')
                            'REPEATING_DAILY_WORKING_DAY'           => 'Y', //'Y'|'N'	('Y' - means it can repeat only on working days - Mon-Fri, 'N' - means every day, including Sat and Sun)
                            'REPEATING_CREATED_ON_TIME'             => '04:30', //HH:mm (hour and minute when task need to be created)
                            'REPEATING_REPEAT_UNTIL'                => 'times', //date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
                            'REPEATING_END_DATE'                    => '15.11.2016 16:00', //dd.MM.yyyy HH:mm (this is final end date of task, after this date this current task will not created automatically - used if REPEATING_REPEAT_UNTIL = 'date')
                            'REPEATING_DAILY_EVERY_DAY'             => 2, //1-31 (i.e. 2 means it repeats every second day from now())
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
                        ]
                    ),
                
                ],
                [
                    'name'           => "Weekly Task 2",
                    'start_date'     => new ActiveRecord\DateTime('2016-10-07 13:04:05'),
                    'end_date'       => new ActiveRecord\DateTime('2017-09-05 03:04:05'),
                    'is_repeatable'  => 1,
                    'repeating_data' => json_encode(
                        [
                            'IS_REPEATABLE'                         => TRUE, //Fixed, always true
                            'REPEATING_ACTIVATE'                    => TRUE, //Fixed, always true
                            'REPEATING_PERIOD'                      => 'weekly', //daily|weekly|monthly|yearly
                            'REPEATING_WEEKLY_EVERY_WEEK'           => 2, //1-52 (number of week in year)
                            'REPEATING_WEEKLY_WEEKDAYS'             => 2, //1-7 (weekdays - Mon=1, Tue=2,... 	Sun=7)
                            'REPEATING_TIMES'                       => 5, //>0 (defines number of occurences - means that this task will be created maximum 5 times regarding the entered parameters - used if REPEATING_REPEAT_UNTIL = 'times')
                            'REPEATING_DAILY_WORKING_DAY'           => 'Y', //'Y'|'N'	('Y' - means it can repeat only on working days - Mon-Fri, 'N' - means every day, including Sat and Sun)
                            'REPEATING_CREATED_ON_TIME'             => '02:30', //HH:mm (hour and minute when task need to be created)
                            'REPEATING_REPEAT_UNTIL'                => 'endless', //date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
                            'REPEATING_END_DATE'                    => '15.11.2017 16:00', //dd.MM.yyyy HH:mm (this is final end date of task, after this date this current task will not created automatically - used if REPEATING_REPEAT_UNTIL = 'date')
                            'REPEATING_DAILY_EVERY_DAY'             => 2, //1-31 (i.e. 2 means it repeats every second day from now())
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
                        ]
                    ),

                ],
                [
                    'name'           => "Weekly Task 3",
                    'start_date'     => new ActiveRecord\DateTime('2016-10-04 23:04:05'),
                    'end_date'       => new ActiveRecord\DateTime('2017-09-05 03:04:05'),
                    'is_repeatable'  => 1,
                    'repeating_data' => json_encode(
                        [
                            'IS_REPEATABLE'                         => TRUE, //Fixed, always true
                            'REPEATING_ACTIVATE'                    => TRUE, //Fixed, always true
                            'REPEATING_PERIOD'                      => 'weekly', //daily|weekly|monthly|yearly
                            'REPEATING_WEEKLY_EVERY_WEEK'           => 1, //1-52 (number of week in year)
                            'REPEATING_WEEKLY_WEEKDAYS'             => 1, //1-7 (weekdays - Mon=1, Tue=2,... 	Sun=7)
                            'REPEATING_TIMES'                       => 5, //>0 (defines number of occurences - means that this task will be created maximum 5 times regarding the entered parameters - used if REPEATING_REPEAT_UNTIL = 'times')
                            'REPEATING_DAILY_WORKING_DAY'           => 'Y', //'Y'|'N'	('Y' - means it can repeat only on working days - Mon-Fri, 'N' - means every day, including Sat and Sun)
                            'REPEATING_CREATED_ON_TIME'             => '02:30', //HH:mm (hour and minute when task need to be created)
                            'REPEATING_REPEAT_UNTIL'                => 'times', //date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
                            'REPEATING_END_DATE'                    => '15.11.2017 16:00', //dd.MM.yyyy HH:mm (this is final end date of task, after this date this current task will not created automatically - used if REPEATING_REPEAT_UNTIL = 'date')
                            'REPEATING_DAILY_EVERY_DAY'             => 2, //1-31 (i.e. 2 means it repeats every second day from now())
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
                        ]
                    ),

                ],
            ];
        Task::createMany($tasks);
        
        $this->start();
    }

    public function addMonthlyRepeatableTasks()
    {
        $tasks =
            [
                [
                    'name'           => "Monthly Task 1",
                    'start_date'     => new ActiveRecord\DateTime('2016-10-03 03:04:05'),
                    'end_date'       => new ActiveRecord\DateTime('2017-09-05 03:04:05'),
                    'is_repeatable'  => 1,
                    'repeating_data' => json_encode(
                        [
                            'IS_REPEATABLE'                         => TRUE, //Fixed, always true
                            'REPEATING_ACTIVATE'                    => TRUE, //Fixed, always true
                            'REPEATING_PERIOD'                      => 'monthly', //daily|weekly|monthly|yearly
                            'REPEATING_MONTHLY_MONTHLY_TYPE'        => 2, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
                            'REPEATING_MONTHLY_MONTHLY_DAY_NUM'     => 3, //1-31 (day in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
                            'REPEATING_MONTHLY_MONTHLY_MONTH_NUM_1' => 9, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
                            'REPEATING_MONTHLY_WEEK_NUM'            => 2, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
                            'REPEATING_MONTHLY_WEEKDAY_NUM'         => 1, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
                            'REPEATING_TIMES'                       => 2, //>0 (defines number of occurences - means that this task will be created maximum 5 times regarding the entered parameters - used if REPEATING_REPEAT_UNTIL = 'times')
                            'REPEATING_CREATED_ON_TIME'             => '05:30', //HH:mm (hour and minute when task need to be created)
                            'REPEATING_REPEAT_UNTIL'                => 'date', //date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
                            'REPEATING_END_DATE'                    => '15.11.2017 16:00', //dd.MM.yyyy HH:mm (this is final end date of task, after this date this current task will not created automatically - used if REPEATING_REPEAT_UNTIL = 'date')
                            'REPEATING_WEEKLY_WEEKDAYS'             => 2, //1-7 (weekdays - Mon=1, Tue=2,... 	Sun=7)
                            'REPEATING_WEEKLY_EVERY_WEEK'           => 2, //1-52 (number of week in year)
                            'REPEATING_DAILY_WORKING_DAY'           => 'Y', //'Y'|'N'	('Y' - means it can repeat only on working days - Mon-Fri, 'N' - means every day, including Sat and Sun)
                            'REPEATING_DAILY_EVERY_DAY'             => 2, //1-31 (i.e. 2 means it repeats every second day from now())
                            'REPEATING_YEARLY_YEARLY_TYPE'          => 1, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
                            'REPEATING_YEARLY_DAY_NUM'              => 6, //1-31 (day in month - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
                            'REPEATING_YEARLY_MONTH_1'              => 4, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
                            'REPEATING_YEARLY_WEEK_NUM'             => 1, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
                            'REPEATING_YEARLY_WEEKDAY_NUM'          => 3, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
                            'REPEATING_YEARLY_MONTH_2'              => 4, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
                        ]
                    ),
                
                ],
//                [
//                    'name'           => "Monthly Task 2",
//                    'start_date'     => new ActiveRecord\DateTime('2016-10-04 03:04:05'),
//                    'end_date'       => new ActiveRecord\DateTime('2017-09-05 03:04:05'),
//                    'is_repeatable'  => 1,
//                    'repeating_data' => json_encode(
//                        [
//                            'IS_REPEATABLE'                         => TRUE, //Fixed, always true
//                            'REPEATING_ACTIVATE'                    => TRUE, //Fixed, always true
//                            'REPEATING_PERIOD'                      => 'monthly', //daily|weekly|monthly|yearly
//                            'REPEATING_MONTHLY_MONTHLY_TYPE'        => 1, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
//                            'REPEATING_MONTHLY_MONTHLY_DAY_NUM'     => 15, //1-31 (day in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
//                            'REPEATING_MONTHLY_MONTHLY_MONTH_NUM_1' => 9, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
//                            'REPEATING_MONTHLY_WEEK_NUM'            => 2, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
//                            'REPEATING_MONTHLY_WEEKDAY_NUM'         => 1, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
//                            'REPEATING_TIMES'                       => 6, //>0 (defines number of occurences - means that this task will be created maximum 5 times regarding the entered parameters - used if REPEATING_REPEAT_UNTIL = 'times')
//                            'REPEATING_CREATED_ON_TIME'             => '02:30', //HH:mm (hour and minute when task need to be created)
//                            'REPEATING_REPEAT_UNTIL'                => 'date', //date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
//                            'REPEATING_END_DATE'                    => '15.11.2017 16:00', //dd.MM.yyyy HH:mm (this is final end date of task, after this date this current task will not created automatically - used if REPEATING_REPEAT_UNTIL = 'date')
//                            'REPEATING_WEEKLY_WEEKDAYS'             => 2, //1-7 (weekdays - Mon=1, Tue=2,... 	Sun=7)
//                            'REPEATING_WEEKLY_EVERY_WEEK'           => 2, //1-52 (number of week in year)
//                            'REPEATING_DAILY_WORKING_DAY'           => 'Y', //'Y'|'N'	('Y' - means it can repeat only on working days - Mon-Fri, 'N' - means every day, including Sat and Sun)
//                            'REPEATING_DAILY_EVERY_DAY'             => 2, //1-31 (i.e. 2 means it repeats every second day from now())
//                            'REPEATING_YEARLY_YEARLY_TYPE'          => 1, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
//                            'REPEATING_YEARLY_DAY_NUM'              => 6, //1-31 (day in month - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
//                            'REPEATING_YEARLY_MONTH_1'              => 4, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
//                            'REPEATING_YEARLY_WEEK_NUM'             => 1, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
//                            'REPEATING_YEARLY_WEEKDAY_NUM'          => 3, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
//                            'REPEATING_YEARLY_MONTH_2'              => 4, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
//                        ]
//                    ),
//
//                ],
            ];
        Task::createMany($tasks);
        
        $this->start();
    }

    public function addYearlyRepeatableTasks()
    {
        $tasks =
            [
                [
                    'name'           => "Yearly Task 1",
                    'start_date'     => new ActiveRecord\DateTime('2016-10-03 03:04:05'),
                    'end_date'       => new ActiveRecord\DateTime('2017-12-05 03:04:05'),
                    'is_repeatable'  => 1,
                    'repeating_data' => json_encode(
                        [
                            'IS_REPEATABLE'                         => TRUE, //Fixed, always true
                            'REPEATING_ACTIVATE'                    => TRUE, //Fixed, always true
                            'REPEATING_PERIOD'                      => 'yearly', //daily|weekly|monthly|yearly
                            'REPEATING_YEARLY_YEARLY_TYPE'          => 2, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
                            'REPEATING_YEARLY_DAY_NUM'              => 6, //1-31 (day in month - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
                            'REPEATING_YEARLY_MONTH_1'              => 10, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
                            'REPEATING_YEARLY_WEEK_NUM'             => 1, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
                            'REPEATING_YEARLY_WEEKDAY_NUM'          => 3, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
                            'REPEATING_YEARLY_MONTH_2'              => 10, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
                            'REPEATING_TIMES'                       => 2, //>0 (defines number of occurences - means that this task will be created maximum 5 times regarding the entered parameters - used if REPEATING_REPEAT_UNTIL = 'times')
                            'REPEATING_CREATED_ON_TIME'             => '05:30', //HH:mm (hour and minute when task need to be created)
                            'REPEATING_REPEAT_UNTIL'                => 'times', //date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
                            'REPEATING_END_DATE'                    => '15.12.2017 16:00', //dd.MM.yyyy HH:mm (this is final end date of task, after this date this current task will not created automatically - used if REPEATING_REPEAT_UNTIL = 'date')
                            'REPEATING_MONTHLY_MONTHLY_TYPE'        => 2, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
                            'REPEATING_MONTHLY_MONTHLY_DAY_NUM'     => 3, //1-31 (day in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
                            'REPEATING_MONTHLY_MONTHLY_MONTH_NUM_1' => 9, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
                            'REPEATING_MONTHLY_WEEK_NUM'            => 2, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
                            'REPEATING_MONTHLY_WEEKDAY_NUM'         => 1, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
                            'REPEATING_WEEKLY_WEEKDAYS'             => 2, //1-7 (weekdays - Mon=1, Tue=2,... 	Sun=7)
                            'REPEATING_WEEKLY_EVERY_WEEK'           => 2, //1-52 (number of week in year)
                            'REPEATING_DAILY_WORKING_DAY'           => 'Y', //'Y'|'N'	('Y' - means it can repeat only on working days - Mon-Fri, 'N' - means every day, including Sat and Sun)
                            'REPEATING_DAILY_EVERY_DAY'             => 2, //1-31 (i.e. 2 means it repeats every second day from now())
                        ]
                    ),
                
                ],
//                [
//                    'name'           => "Yearly Task 2",
//                    'start_date'     => new ActiveRecord\DateTime('2016-10-07 03:04:05'),
//                    'end_date'       => new ActiveRecord\DateTime('2017-09-05 03:04:05'),
//                    'is_repeatable'  => 1,
//                    'repeating_data' => json_encode(
//                        [
//                            'IS_REPEATABLE'                         => TRUE, //Fixed, always true
//                            'REPEATING_ACTIVATE'                    => TRUE, //Fixed, always true
//                            'REPEATING_PERIOD'                      => 'yearly', //daily|weekly|monthly|yearly
//                            'REPEATING_YEARLY_YEARLY_TYPE'          => 1, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
//                            'REPEATING_YEARLY_DAY_NUM'              => 6, //1-31 (day in month - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
//                            'REPEATING_YEARLY_MONTH_1'              => 11, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 1)
//                            'REPEATING_YEARLY_WEEK_NUM'             => 1, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
//                            'REPEATING_YEARLY_WEEKDAY_NUM'          => 3, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
//                            'REPEATING_YEARLY_MONTH_2'              => 10, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_YEARLY_YEARLY_TYPE = 2)
//                            'REPEATING_TIMES'                       => 2, //>0 (defines number of occurences - means that this task will be created maximum 5 times regarding the entered parameters - used if REPEATING_REPEAT_UNTIL = 'times')
//                            'REPEATING_CREATED_ON_TIME'             => '05:30', //HH:mm (hour and minute when task need to be created)
//                            'REPEATING_REPEAT_UNTIL'                => 'date', //date|endless|times (this defines chosen type of end reqirement - radio button). If 'endless' means it repeats indefinitely
//                            'REPEATING_END_DATE'                    => '15.11.2017 16:00', //dd.MM.yyyy HH:mm (this is final end date of task, after this date this current task will not created automatically - used if REPEATING_REPEAT_UNTIL = 'date')
//                            'REPEATING_MONTHLY_MONTHLY_TYPE'        => 2, //1|2 (1 - specific day in month is specified, 2 - specific week and specific weekday in month is specified)
//                            'REPEATING_MONTHLY_MONTHLY_DAY_NUM'     => 3, //1-31 (day in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
//                            'REPEATING_MONTHLY_MONTHLY_MONTH_NUM_1' => 9, //0-11 (month number, 0 - January,... 11 - December - used if REPEATING_MONTHLY_MONTHLY_TYPE = 1)
//                            'REPEATING_MONTHLY_WEEK_NUM'            => 2, //0-4 (week number in month - 0 - first weeek,... 4 - fifth week in month - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
//                            'REPEATING_MONTHLY_WEEKDAY_NUM'         => 1, //0-6 (weekday number: 0 - Monday, 1 - Tuesday,... 6 - Sunday - used if REPEATING_MONTHLY_MONTHLY_TYPE = 2)
//                            'REPEATING_WEEKLY_WEEKDAYS'             => 2, //1-7 (weekdays - Mon=1, Tue=2,... 	Sun=7)
//                            'REPEATING_WEEKLY_EVERY_WEEK'           => 2, //1-52 (number of week in year)
//                            'REPEATING_DAILY_WORKING_DAY'           => 'Y', //'Y'|'N'	('Y' - means it can repeat only on working days - Mon-Fri, 'N' - means every day, including Sat and Sun)
//                            'REPEATING_DAILY_EVERY_DAY'             => 2, //1-31 (i.e. 2 means it repeats every second day from now())
//                        ]
//                    ),
//
//                ],
            ];
        Task::createMany($tasks);
        
        $this->start();
    }
    
    public function addNonRepeatableTasks()
    {
        $tasks =
            [
                [
                    'name'           => "Task Start After Interval Start amd Before Interval End",
                    'start_date'     => new ActiveRecord\DateTime('2016-10-03 03:04:05'),
                    'end_date'       => '',
                        'is_repeatable'  => 0,
                    'repeating_data' => NULL,
                
                ],
                [
                    'name'           => "Task Start After Interval Start amd Before Interval End",
                    'start_date'     => new ActiveRecord\DateTime('2016-11-12 03:04:05'),
                    'end_date'       => '',
                    'is_repeatable'  => 0,
                    'repeating_data' => NULL,
                
                ],
            ];
        Task::createMany($tasks);
        
        $this->start();
    }
    
//    public function test()
//    {
//        $tasks =
//            [
//                [
//                    'name'           => "Task Start After Interval Start amd Before Interval End",
//                    'start_date'     => new ActiveRecord\DateTime('2016-10-03 03:04:05'),
//                    'end_date'       => '',
//                        'is_repeatable'  => 0,
//                    'repeating_data' => NULL,
//
//                ],
//                [
//                    'name'           => "Task Start After Interval Start amd Before Interval End",
//                    'start_date'     => new ActiveRecord\DateTime('2016-11-12 03:04:05'),
//                    'end_date'       => '',
//                    'is_repeatable'  => 0,
//                    'repeating_data' => NULL,
//
//                ],
//            ];
//        Task::createMany($tasks);
//
//        $this->start();
//
//    }
}