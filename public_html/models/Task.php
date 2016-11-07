<?php

class Task extends ActiveRecord\Model {
    // explicit table name since our table is not "tasks"
//    static $table_name = 'task';

    public static function createMany($items)
    {
        if (count($items) > 0) {
            $i = 0;
            foreach ($items as $item) {
                $task = new Task($item);
                $task->save();
                $i++;
            }

            return $i;
        }

        return 0;
    }
}