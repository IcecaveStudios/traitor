<?php
namespace Icecave\Traitor;

/**
 * Build a class at run-time.
 */
class TraitorNoChecks extends AbstractTraitor
{
    /**
     * Have the generated class extend the given class.
     *
     * @param string $class The name of the class to extend.
     *
     * @return TraitorNoChecks This instance.
     */
    public function extends_($class)
    {
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
        if (is_array($interfaces)) {
            $interfaces = array_values($interfaces);
        } else {
            $interfaces = func_get_args();
        }

        $this->interfaces = array_merge($this->interfaces, array_combine($interfaces, $interfaces));

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
        if (is_array($traits)) {
            $traits = array_values($traits);
        } else {
            $traits = func_get_args();
        }

        $this->traits = array_merge($this->traits, array_combine($traits, $traits));

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
}
