<?php
namespace Icecave\Traitor;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

class TraitorTest extends PHPUnit_Framework_TestCase
{
    public function testExtends()
    {
        $instance = Traitor::create()
            ->extends_('Icecave\Traitor\ParentClass')
            ->instance();

        $this->assertInstanceOf(
            'Icecave\Traitor\ParentClass',
            $instance
        );
    }

    public function testExtendsFailureWithInterface()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Icecave\Traitor\Interface1 is not a class.'
        );

        $instance = Traitor::create()
            ->extends_('Icecave\Traitor\Interface1');
    }

    public function testExtendsFailureWithTrait()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Icecave\Traitor\Trait1 is not a class.'
        );

        $instance = Traitor::create()
            ->extends_('Icecave\Traitor\Trait1');
    }

    public function testExtendsFailureFinalClass()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Icecave\Traitor\FinalParentClass is marked final.'
        );

        $instance = Traitor::create()
            ->extends_('Icecave\Traitor\FinalParentClass');
    }

    public function testImplements()
    {
        $instance = Traitor::create()
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
        $instance = Traitor::create()
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
        $instance = Traitor::create()
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

    public function testImplementsFailure()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Icecave\Traitor\ParentClass is not an interface.'
        );

        Traitor::create()
            ->implements_('Icecave\Traitor\ParentClass');
    }

    public function testUse()
    {
        $reflector = Traitor::create()
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
        $reflector = Traitor::create()
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
        $reflector = Traitor::create()
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

    public function testUseFailure()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Icecave\Traitor\ParentClass is not a trait.'
        );

        Traitor::create()
            ->use_('Icecave\Traitor\ParentClass');
    }

    public function testEverything()
    {
        $instance = Traitor::create()
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
        $traitor = Traitor::create();

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
        $instance = Traitor::create()
            ->extends_('Icecave\Traitor\ParentClass')
            ->instance(1, 2, 3);

        $this->assertSame(
            [1, 2, 3],
            $instance->arguments
        );
    }

    public function testInstanceArray()
    {
        $instance = Traitor::create()
            ->extends_('Icecave\Traitor\ParentClass')
            ->instanceArray([1, 2, 3]);

        $this->assertSame(
            [1, 2, 3],
            $instance->arguments
        );
    }

    public function testAbstract()
    {
        $reflector = Traitor::create()
            ->abstract_()
            ->reflector();

        $this->assertTrue(
            $reflector->isAbstract()
        );
    }

    public function testCode()
    {
        $code = Traitor::create()
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
