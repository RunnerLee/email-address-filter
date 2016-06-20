<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 16-6-20 上午11:18
 */
namespace Runner\EmailAddressFilter\Tests;

use Runner\EmailAddressFilter\EmailAddressFilter;

class FilterTests extends \PHPUnit_Framework_TestCase
{

    public function testFilter()
    {

        $filter = new EmailAddressFilter(__DIR__ . '/../examples/data/dict.list');

        $this->assertEquals('runnerleer@gmail.com', $filter->filter('runnerleer@gmail.com'));
    }

}
