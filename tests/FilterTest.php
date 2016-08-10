<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 16-8-10 下午11:57
 */
namespace Runner\EmailAddressFilter\Tests;

use Runner\EmailAddressFilter\EmailAddressFilter;

class FilterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var EmailAddressFilter
     */
    protected $filter;

    public function setUp()
    {
        $this->filter = new EmailAddressFilter(__DIR__ . '/fixtures/list.dict');
    }


    public function testFilter()
    {
        $this->assertSame('runnerleer@gmail.com', $this->filter->filter('runnerleer@gmail.com'));
        $this->assertSame(false, $this->filter->filter('runnerleer@delaynomore.com'));
    }


    public function testFilterWithQueryDns()
    {
        $this->assertEquals('runnerleer@linghit.com', $this->filter->filterWithQueryDns('runnerleer@linghit.com'));
    }

}