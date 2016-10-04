<?php
namespace AKlump\Data;

/**
 * Class DataTest
 * @package AKlump\Data
 */
class DataTest extends \PHPUnit_Framework_TestCase
{

    public function testCallbackMultidimensional()
    {
        $value = array('do' => array('re' => array('mi' => 'fa')));
        $this->assertSame('Value is fa', $this->data->get($value, 'do.re.mi', null, function ($value) {
            return 'Value is ' . $value;
        }));
    }

    public function testCallback()
    {
        $value = array(9, 6);
        $callback = function ($value, $defaultValue) {
            return $value === $defaultValue ? $value : 2 * $value;
        };
        $this->assertSame(18, $this->data->get($value, 0, 5, $callback));
        $this->assertSame(12, $this->data->get($value, 1, 5, $callback));
        $this->assertSame(5, $this->data->get($value, 2, 5, $callback));
    }

    public function testEmptySubjectReturnsDefault()
    {
        $this->assertSame('pepperoni', $this->data->get(array(), null, 'pepperoni'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPathAsNullThrows()
    {
        $this->data->get(array('do'), (object) array('1', '2'));
    }

    public function testPathAsIntWorks()
    {
        $subject = array(1 => array(1 => 'pizza'));
        $this->assertSame('pizza', $this->data->get($subject, 1.1));
    }

    /**
     * Provides data for testGet.
     */
    function DataForTestGetProvider()
    {
        // $subject, $path, $default, $control
        $tests = array();
        $tests[] = array(
            array('alpha' => array('bravo' => array('charlie' => array('delta' => array('echo' => array('foxtrot' => 'golf')))))),
            'alpha.bravo.charlie.delta.echo.foxtrot',
            null,
            'golf',
        );
        $tests[] = array(
            array('do' => array('alpha' => 'bravo')),
            'do.delta',
            null,
            null,
        );
        $tests[] = array(
            array('do' => array('alpha' => 'bravo')),
            'do.alpha',
            'charlie',
            'bravo',
        );
        $tests[] = array(
            array('do' => array('alpha' => 'bravo')),
            'do.delta',
            'charlie',
            'charlie',
        );
        $tests[] = array(
            array('do' => 're'),
            'do',
            'mi',
            're',
        );
        $tests[] = array(
            array('do' => 're'),
            'fa',
            'mi',
            'mi',
        );

        return $tests;
    }

    /**
     * @dataProvider DataForTestGetProvider
     */
    public function testGetArraysSimpleObjects($subject, $path, $default, $control)
    {
        $this->assertSame($this->data->get($subject, $path, $default), $control);
        $this->assertSame($this->data->get($subject, explode('.', $path), $default), $control);

        // Convert the top array to a \stdClass
        $object = (object) $subject;
        $this->assertSame($this->data->get($object, $path, $default), $control);

        // Now let's make an object of objects using json.
        $object2 = json_decode(json_encode($subject));
        $this->assertSame($this->data->get($object2, $path, $default), $control);
    }

    public function testObjectWithGetMethod()
    {
        // https://phpunit.de/manual/current/en/test-doubles.html
        $object3 = $this->getMockBuilder('ClassWithGet')
                        ->setMethods(array('get'))
                        ->getMock();
        $object3->expects($this->any())
                ->method('get')
                ->will($this->returnValue('do'));
        $this->assertSame($this->data->get($object3, 'do'), 'do');
    }

    public function setUp()
    {
        $this->data = new Data();
    }
}
