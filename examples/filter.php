<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 16-6-18 下午11:57
 */
require __DIR__ . '/../vendor/autoload.php';

$filter = new \Runner\EmailAddressFilter\EmailAddressFilter(__DIR__ . '/dict.txt');

var_dump($filter->filter('lirunfeng@lnghit.com'));