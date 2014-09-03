<?php
namespace Icecave\Traitor;

use InvalidArgumentException;
use ReflectionClass;

/**
 * Build a class at run-time.
 */
class Traitor
{
    /**
     * Create a traitor instance.
     *
     * @return Traitor
     */
    public static function create()
    {
        return new Traitor;
    }

    /**
     * Have the generated class extend the given class.
     *
     * @param string $class The name of the class to extend.
     *
     * @return Traitor This instance.
     */
    public function extends_($class)
    {
        $reflector = new ReflectionClass($class);

        if ($reflector->isInterface() || $reflector->isTrait()) {
            throw new InvalidArgumentException($class . ' is not a class.');
        } elseif ($reflector->isFinal()) {
            throw new InvalidArgumentException($class . ' is marked final.');
        }

        $this->parent = $class;

        return $this;
    }

    /**
     * Have the generated class implement the given interface(s).
     *
     * @param array<string>|string $interfaces     The name of the interface to implement.
     * @param string               $additional,... Additional interface names.
     *
     * @return Traitor This instance.
     */
    public function implements_($interfaces)
    {
        if (!is_array($interfaces)) {
            $interfaces = func_get_args();
        }

        foreach ($interfaces as $interface) {
            $reflector = new ReflectionClass($interface);

            if (!$reflector->isInterface()) {
                throw new InvalidArgumentException($interface . ' is not an interface.');
            }

            $this->interfaces[$interface] = $interface;
        }

        return $this;
    }

    /**
     * Have the generated class use the given trait(s).
     *
     * @param array<string>|string $traits         The name of the trait to use.
     * @param string               $additional,... Additional trait names.
     *
     * @return Traitor This instance.
     */
    public function use_($traits)
    {
        if (!is_array($traits)) {
            $traits = func_get_args();
        }

        foreach ($traits as $trait) {
            $reflector = new ReflectionClass($trait);

            if (!$reflector->isTrait()) {
                throw new InvalidArgumentException($trait . ' is not a trait.');
            }

            $this->traits[$trait] = $trait;
        }

        return $this;
    }

    /**
     * Make the generated class abstract.
     *
     * @param boolean $abstract True to make the class abstract.
     *
     * @return Traitor This object.
     */
    public function abstract_($abstract = true)
    {
        $this->abstract = $abstract;

        return $this;
    }

    /**
     * Get the name of the generated class.
     *
     * @return string The name of the generated class.
     */
    public function name()
    {
        $className = $this->generateClassName();

        if (!class_exists($className)) {
            eval($this->code());
        }

        return $className;
    }

    /**
     * Get a reflector for the generated class.
     *
     * @return ReflectionClass The reflector for the generated class.
     */
    public function reflector()
    {
        return new ReflectionClass(
            $this->name()
        );
    }

    /**
     * Get an instance of the generated class.
     *
     * @param mixed $arguments,... Arguments to pass to the constructor.
     *
     * @return object
     */
    public function instance()
    {
        return $this->instanceArray(
            func_get_args()
        );
    }

    /**
     * Get an instance of the generated class.
     *
     * @param array $arguments,... Arguments to pass to the constructor.
     *
     * @return object
     */
    public function instanceArray(array $arguments)
    {
        return $this
            ->reflector()
            ->newInstanceArgs($arguments);
    }

    /**
     * Get the generated code.
     *
     * @return string The generated code.
     */
    public function code()
    {
        $code = '';
        if ($this->abstract) {
            $code .= 'abstract ';
        }
        $code .= 'class ' . $this->generateClassName();
        if ($this->parent) {
            $code .= ' extends ' . $this->parent;
        }
        if ($this->interfaces) {
            $code .= ' implements ' . implode(', ', $this->interfaces);
        }
        $code .= ' {';
        if ($this->traits) {
            $code .= ' use ' . implode(', ', $this->traits) . ';';
        }
        $code .= ' }';

        return $code;
    }

    private function generateClassName()
    {
        if ($this->abstract) {
            $className = 'AbstractTraitorImplementation_';
        } else {
            $className = 'TraitorImplementation_';
        }

        $names = array_merge(
            array_keys($this->interfaces),
            array_keys($this->traits)
        );

        sort($names);

        return $className . md5(
            $this->parent . implode('', $names)
        );
    }

    private $parent;
    private $abstract = false;
    private $traits = [];
    private $interfaces = [];
}
