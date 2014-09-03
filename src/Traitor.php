<?php
namespace Icecave\Traitor;

use InvalidArgumentException;
use ReflectionClass;

class Traitor
{
    public static function create()
    {
        return new Traitor;
    }

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

    public function name()
    {
        $className = 'TraitorImplementation_' . $this->hash();

        if (!class_exists($className)) {
            $this->declareClass($className);
        }

        return $className;
    }

    public function reflector()
    {
        return new ReflectionClass(
            $this->name()
        );
    }

    public function instance()
    {
        return $this->
            reflector()
            ->newInstanceArgs(
                func_get_args()
            );
    }

    private function hash()
    {
        $names = array_merge(
            array_keys($this->interfaces),
            array_keys($this->traits)
        );

        sort($names);

        return md5(
            $this->parent . implode('', $names)
        );
    }

    private function declareClass($className)
    {
        $code = 'class ' . $className;
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

        eval($code);
    }

    private $parent;
    private $traits = [];
    private $interfaces = [];
}
