<?php
namespace Icecave\Traitor;

/**
 * Build a class at run-time.
 */
abstract class AbstractTraitor implements TraitorInterface
{
    /**
     * Create a traitor instance.
     *
     * @return Traitor
     */
    public static function create()
    {
        return new static();
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
        $indent = '    ';
        $code = '';
        if ($this->abstract) {
            $code .= 'abstract ';
        }
        $code .= 'class ' . $this->generateClassName();
        if ($this->parent) {
            $code .= ' extends ' . $this->parent;
        }
        if ($this->interfaces) {
            $code .= ' implements' . PHP_EOL;
            $code .= '    ' . implode(',' . PHP_EOL . $indent, $this->interfaces);
            $code .= PHP_EOL;
        }
        $code .= '{' . PHP_EOL;
        if ($this->traits) {
            $code .= '    use ';
            $code .= implode(',' . PHP_EOL . $indent . $indent, $this->traits) . ';';
            $code .= PHP_EOL;
        }
        $code .= '}' . PHP_EOL;

        return $code;
    }

    protected function generateClassName()
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

    protected $parent;
    protected $abstract = false;
    protected $traits = [];
    protected $interfaces = [];
}
