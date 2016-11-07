<?php

ActiveRecord\Config::initialize(function ($cfg) {
    $cfg->set_model_directory('models');
    $cfg->set_connections(array(
        'development' => 'mysql://root:456v123@localhost/rtasks'));
});

ActiveRecord\Connection::$datetime_format = 'Y-m-d H:i:s';