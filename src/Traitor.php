<?php
namespace Icecave\Traitor;

use InvalidArgumentException;
use ReflectionClass;

/**
 * Build a class at run-time.
 */
class Traitor extends AbstractTraitor
{
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
}
