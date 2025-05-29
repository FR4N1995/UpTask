<?php
require  '../vendor/autoload.php';
require 'funciones.php';
require 'database.php';


use Model\ActiveRecord;
ActiveRecord::setDB($db);