<?php
namespace Icecave\Traitor;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

class TraitorNoChecksTest extends PHPUnit_Framework_TestCase
{
    public function testExtends()
    {
        $instance = TraitorNoChecks::create()
            ->extends_('Icecave\Traitor\ParentClass')
            ->instance();

        $this->assertInstanceOf(
            'Icecave\Traitor\ParentClass',
            $instance
        );
    }

    public function testImplements()
    {
        $instance = TraitorNoChecks::create()
            ->implements_('Icecave\Traitor\Interface1')
            ->implements_('Icecave\Traitor\Interface2')
            ->instance();

        $this->assertInstanceOf(
            'Icecave\Traitor\Interface1',
            $instance
        );

        $this->assertInstanceOf(
            'Icecave\Traitor\Interface2',
            $instance
        );
    }

    public function testImplementsWithMultipleParameters()
    {
        $instance = TraitorNoChecks::create()
            ->implements_(
                'Icecave\Traitor\Interface1',
                'Icecave\Traitor\Interface2'
            )
            ->instance();

        $this->assertInstanceOf(
            'Icecave\Traitor\Interface1',
            $instance
        );

        $this->assertInstanceOf(
            'Icecave\Traitor\Interface2',
            $instance
        );
    }

    public function testImplementsWithArray()
    {
        $instance = TraitorNoChecks::create()
            ->implements_(
                [
                    'Icecave\Traitor\Interface1',
                    'Icecave\Traitor\Interface2',
                ]
            )
            ->instance();

        $this->assertInstanceOf(
            'Icecave\Traitor\Interface1',
            $instance
        );

        $this->assertInstanceOf(
            'Icecave\Traitor\Interface2',
            $instance
        );
    }

    public function testUse()
    {
        $reflector = TraitorNoChecks::create()
            ->use_('Icecave\Traitor\Trait1')
            ->use_('Icecave\Traitor\Trait2')
            ->reflector();

        $this->assertSame(
            ['Icecave\Traitor\Trait1', 'Icecave\Traitor\Trait2'],
            array_keys($reflector->getTraits())
        );
    }

    public function testUseWithMultipleParameters()
    {
        $reflector = TraitorNoChecks::create()
            ->use_(
                'Icecave\Traitor\Trait1',
                'Icecave\Traitor\Trait2'
            )
            ->reflector();

        $this->assertSame(
            ['Icecave\Traitor\Trait1', 'Icecave\Traitor\Trait2'],
            array_keys($reflector->getTraits())
        );
    }

    public function testUseWithArray()
    {
        $reflector = TraitorNoChecks::create()
            ->use_(
                [
                    'Icecave\Traitor\Trait1',
                    'Icecave\Traitor\Trait2',
                ]
            )
            ->reflector();

        $this->assertSame(
            ['Icecave\Traitor\Trait1', 'Icecave\Traitor\Trait2'],
            array_keys($reflector->getTraits())
        );
    }

    public function testEverything()
    {
        $instance = TraitorNoChecks::create()
            ->extends_('Icecave\Traitor\ParentClass')
            ->implements_('Icecave\Traitor\Interface1')
            ->use_('Icecave\Traitor\Trait1')
            ->instance();

        $this->assertInstanceOf(
            'Icecave\Traitor\ParentClass',
            $instance
        );

        $this->assertInstanceOf(
            'Icecave\Traitor\Interface1',
            $instance
        );

        $this->assertSame(
            ['Icecave\Traitor\Trait1'],
            array_keys(
                (new ReflectionClass($instance))->getTraits()
            )
        );
    }

    public function testEmpty()
    {
        $expectedName = 'TraitorImplementation_d41d8cd98f00b204e9800998ecf8427e';
        $traitor = TraitorNoChecks::create();

        $name = $traitor
            ->name();

        $this->assertSame(
            $expectedName,
            $traitor->name()
        );

        $reflector = $traitor->reflector();

        $this->assertInstanceOf(
            'ReflectionClass',
            $reflector
        );

        $this->assertSame(
            $expectedName,
            $reflector->getName()
        );

        $this->assertInstanceOf(
            $expectedName,
            $traitor->instance()
        );
    }

    public function testInstance()
    {
        $instance = TraitorNoChecks::create()
            ->extends_('Icecave\Traitor\ParentClass')
            ->instance(1, 2, 3);

        $this->assertSame(
            [1, 2, 3],
            $instance->arguments
        );
    }

    public function testInstanceArray()
    {
        $instance = TraitorNoChecks::create()
            ->extends_('Icecave\Traitor\ParentClass')
            ->instanceArray([1, 2, 3]);

        $this->assertSame(
            [1, 2, 3],
            $instance->arguments
        );
    }

    public function testAbstract()
    {
        $reflector = TraitorNoChecks::create()
            ->abstract_()
            ->reflector();

        $this->assertTrue(
            $reflector->isAbstract()
        );
    }

    public function testCode()
    {
        $code = TraitorNoChecks::create()
            ->extends_('Icecave\Traitor\ParentClass')
            ->implements_('Icecave\Traitor\Interface1')
            ->implements_('Icecave\Traitor\Interface2')
            ->use_('Icecave\Traitor\Trait1')
            ->use_('Icecave\Traitor\Trait2')
            ->code();

        $expectedCode = <<<CODE
class TraitorImplementation_444573daa8f8d04d0e1a9014e4bd4759 extends Icecave\Traitor\ParentClass implements
    Icecave\Traitor\Interface1,
    Icecave\Traitor\Interface2
{
    use Icecave\Traitor\Trait1,
        Icecave\Traitor\Trait2;
}

CODE;

        $this->assertEquals(
            $expectedCode,
            $code
        );
    }
}
