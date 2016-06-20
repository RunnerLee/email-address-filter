<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 16-6-18 下午11:57
 */
require __DIR__ . '/../vendor/autoload.php';

$filter = new \Runner\EmailAddressFilter\EmailAddressFilter(__DIR__ . '/data/dict.list', __DIR__ . '/data/tempTable.list');

var_dump($filter->filterWithQueryDns('lirunfeng@linghit.com'));