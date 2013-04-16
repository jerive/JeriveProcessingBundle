<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Tests;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

use Jerive\Bundle\FileProcessingBundle\Processing\BaseIterator;
use Jerive\Bundle\FileProcessingBundle\Processing\Iterator\CsvReader;
use Jerive\Bundle\FileProcessingBundle\Processing\Filter\CallbackFilter;
use Jerive\Bundle\FileProcessingBundle\Processing\FilterResolver;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;

class BaseIteratorTest extends \PHPUnit_Framework_TestCase
    implements EventSubscriberInterface
{
    protected $errors = 0;

    public function testSimple()
    {
        $csv = <<<csv
toto|toto
lolo|lolo
csv;

        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('imports'));
        file_put_contents($file = vfsStream::url('imports/test.csv'), $csv);

        $processing = new BaseIterator(new EventDispatcher, new FilterResolver);
        $processing->setIterator(
            $reader = CsvReader::factory($file)
        );

        $processing->addFilter('callback', array('callback' => 'array_merge'));
        $processing->process();
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSimpleWillFailIfNoIteratorIsSet()
    {
        $processing = new BaseIterator(new EventDispatcher, new FilterResolver);
        $processing->process();
    }

    public function testWillDispatchErrorOnFailure()
    {
        $processing = new BaseIterator(new EventDispatcher, new FilterResolver);
        $processing->addFilter(new CallbackFilter(array(
            'callback' => array($this, 'willThrowException'),
        )));
        $processing->setIterator(new \ArrayIterator(array(1)));
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
