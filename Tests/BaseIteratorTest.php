<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Tests;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;

use Jerive\Bundle\FileProcessingBundle\Processing\BaseIterator;
use Jerive\Bundle\FileProcessingBundle\Processing\Iterator\CsvReader;
use Jerive\Bundle\FileProcessingBundle\Processing\Filter\CallbackFilter;

class BaseIteratorTest extends PHPUnit_Framework_TestCase
    implements EventSubscriberInterface
{
    protected $errors = 0;

    public function testSimple()
    {
        $processing = new BaseIterator;
        $processing->setIterator(
            $reader = CsvReader::factory('')
        );

        $processing->addFilter('callback', array('callback' => 'array_merge'));
        $processing->process();
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSimpleWillFailIfNoIteratorIsSet()
    {
        $processing = new BaseIterator;
        $processing->process();
    }

    public function testWillDispatchErrorOnFailure()
    {
        $processing = new BaseIterator;
        $processing->addFilter(new CallbackFilter(array(
            'callback' => array($this, 'willThrowException'),
        )));
        $processing->setIterator(new ArrayIterator(array(1)));
        $processing->getDispatcher()->addSubscriber($this);

        $processing->process();

        $this->assertGreaterThan(0, $this->errors);
    }

    public function willThrowException($row)
    {
        throw new \Exception('message');
    }

    public function registerError(Event $e)
    {
        $this->errors++;
    }

    public static function getSubscribedEvents()
    {
        return array(
            BaseIterator::EVENT_LINE_ERROR => 'registerError',
        );
    }
}