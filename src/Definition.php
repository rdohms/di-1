<?php
/**
 * Strike Di - Yet Another Dependency Injection Library
 */

namespace Strike\Di;

/**
 * Definition
 *
 * Yet Another Dependency Injection Service Definition
 */
class Definition
{
    /**
     * Class name
     *
     * @var string
     */
    protected $class;

    /**
     * Class constructor arguments
     *
     * @var array
     */
    protected $arguments;

    /**
     * Class properties
     *
     * @var array
     */
    protected $properties;

    /**
     * Class method calls
     *
     * @var array
     */
    protected $calls;

    /**
     * Define service is public or not
     *
     * @var boolean
     */
    protected $public;

    /**
     * Define service is a shared instance or not
     *
     * @var boolean
     */
    protected $share;

    /**
     * Constructor.
     *
     * @param string $class     Service class name
     * @param array  $arguments Class constructor arguments
     */
    public function __construct($class, array $arguments = array())
    {
        $this->class = $class;
        $this->arguments = $arguments;
        $this->calls = array();
        $this->properties = array();
        $this->public = true;
        $this->share = true;
    }

    /**
     * Set class name
     *
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * Get class name
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set class constructor arguments
     *
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Get class constructor arguments
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Set class properties
     *
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * Get class properties
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Set class method calls
     *
     * @param array $calls
     */
    public function setCalls(array $calls)
    {
        $this->calls = $calls;
    }

    /**
     * Get class method calls
     *
     * @return array
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * Set service to be public or not
     *
     * @param boolean $public
     */
    public function setPublic($public)
    {
        $this->public = (boolean) $public;
    }

    /**
     * Check service is public or not
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set service to be shared or not
     *
     * @param boolean $share
     */
    public function setShare($share)
    {
        $this->share = (boolean) $share;
    }

    /**
     * Check service is a shared instance or not
     *
     * @return bool
     */
    public function getShare()
    {
        return $this->share;
    }
}
