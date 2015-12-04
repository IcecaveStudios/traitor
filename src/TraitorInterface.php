<?php
namespace Icecave\Traitor;

interface TraitorInterface
{
    /**
     * Have the generated class extend the given class.
     *
     * @param string $class The name of the class to extend.
     *
     * @return TraitorInterface This instance.
     */
    public function extends_($class);

    /**
     * Have the generated class implement the given interface(s).
     *
     * @param array<string>|string $interfaces     The name of the interface to implement.
     * @param string               $additional,... Additional interface names.
     *
     * @return TraitorInterface This instance.
     */
    public function implements_($interfaces);

    /**
     * Have the generated class use the given trait(s).
     *
     * @param array<string>|string $traits         The name of the trait to use.
     * @param string               $additional,... Additional trait names.
     *
     * @return TraitorInterface This instance.
     */
    public function use_($traits);

    /**
     * Make the generated class abstract.
     *
     * @param boolean $abstract True to make the class abstract.
     *
     * @return TraitorInterface This object.
     */
    public function abstract_($abstract = true);

    /**
     * Get the name of the generated class.
     *
     * @return string The name of the generated class.
     */
    public function name();

    /**
     * Get a reflector for the generated class.
     *
     * @return ReflectionClass The reflector for the generated class.
     */
    public function reflector();

    /**
     * Get an instance of the generated class.
     *
     * @param mixed $arguments,... Arguments to pass to the constructor.
     *
     * @return object
     */
    public function instance();

    /**
     * Get an instance of the generated class.
     *
     * @param array $arguments,... Arguments to pass to the constructor.
     *
     * @return object
     */
    public function instanceArray(array $arguments);

    /**
     * Get the generated code.
     *
     * @return string The generated code.
     */
    public function code();
}
