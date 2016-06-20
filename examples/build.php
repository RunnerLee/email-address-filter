<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 16-6-18 下午11:07
 */
require __DIR__ . '/../vendor/autoload.php';

\Runner\EmailAddressFilter\Builder::build(__DIR__ . '/data/domain.list', __DIR__ . '/data/dict.list');