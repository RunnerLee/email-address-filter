<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 16-8-10 下午11:54
 */
namespace Runner\EmailAddressFilter\Tests;

use Runner\EmailAddressFilter\Builder;

class BuilderTest extends \PHPUnit_Framework_TestCase
{


    public function testBuilder()
    {
        Builder::build(__DIR__ . '/fixtures/domain.list', __DIR__ . '/fixtures/list.dict');
    }

}