<?php
/**
 * Strike Di - Yet Another Dependency Injection Library
 */

namespace Strike\Di;

/**
 * Container
 *
 * Yet Another Dependency Injection Service Container
 */
class Container
{
    /**
     * Service Definitions
     *
     * @var array
     */
    protected $services = array();

    /**
     * Service
     *
     * @var array
     */
    protected $aliases = array();

    /**
     * Service Definitions
     *
     * @var Definition[]
     */
    protected $serviceDefinitions = array();

    /**
     * Parameters
     *
     * @var array
     */
    protected $parameters = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->set('service_container', $this);
    }

    /**
     * Get service instance with service id
     *
     * @param string $id Service id
     *
     * @return mixed Service instance
     *
     * @throws \InvalidArgumentException
     */
    public function get($id)
    {
        $id = strtolower($id);
        $isAlias = false;

        if (array_key_exists($id, $this->aliases)) {
            $id = $this->aliases[$id];
            $isAlias = true;
        }

        if (!array_key_exists($id, $this->serviceDefinitions)) {
            if (array_key_exists($id, $this->services)) {
                return $this->services[$id];
            }

            throw new \InvalidArgumentException('service is not defined');
        }

        $definition = $this->serviceDefinitions[$id];

        if (false === $definition->getPublic() && false === $isAlias) {
            throw new \InvalidArgumentException('cannot access service directly');
        }

        if (array_key_exists($id, $this->services)) {
            return $this->services[$id];
        }

        $service = $this->generateService($definition);

        if ($definition->getShare()) {
            $this->services[$id] = $service;
        }

        return $service;
    }

    /**
     * Generate service instance
     *
     * @param Definition $definition Service definition
     *
     * @return mixed
     */
    protected function generateService($definition)
    {
        $reflection = new \ReflectionClass($definition->getClass());
        $arguments = $definition->getArguments();
        $properties = $definition->getProperties();
        $calls = $definition->getCalls();

        if (count($arguments) > 0) {
            foreach ($arguments as &$argument) {
                if (is_string($argument)) {
                    if ('@' === $argument[0]) {
                        $serviceId = substr($argument, 1);
                        $subDefinition = $this->getDefinition($serviceId);
                        $argument = $this->generateService($subDefinition);
                    } else if (strlen($argument) > 2 && '%' === $argument[0] && '%' === substr($argument, -1)) {
                        $parameterId = substr($argument, 1, -1);
                        $argument = $this->getParameter($parameterId);
                    }
                }
            }

            $service = $reflection->newInstanceArgs($arguments);
        } else {
            $service = $reflection->newInstance();
        }

        if (count($properties) > 0) {
            foreach ($properties as $key => $value) {
                $service->{$key} = $value;
            }
        }

        if (count($calls) > 0) {
            foreach ($calls as $call) {
                call_user_func_array(array($service, $call[0]), $call[1]);
            }
        }

        return $service;
    }

    /**
     * Set a service instance
     *
     * @param string $id      Service id
     * @param mixed  $service Service instance
     *
     * @return Container
     */
    public function set($id, $service)
    {
        $id = strtolower($id);

        $this->services[$id] = $service;

        return $this;
    }

    /**
     * Set alias service id
     *
     * @param string $aliasId The alias service id
     * @param string $id      The original service id
     *
     * @return Container
     */
    public function setAlias($aliasId, $id)
    {
        $this->aliases[strtolower($aliasId)] = strtolower($id);

        return $this;
    }

    /**
     * Get parameter with a parameter id
     *
     * @param string $id Parameter id
     *
     * @return mixed
     */
    public function getParameter($id)
    {
        $id = strtolower($id);

        if (array_key_exists($id, $this->parameters)) {
            return $this->parameters[$id];
        }

        return null;
    }

    /**
     * Set a parameter value with a parameter id
     *
     * @param string $id    Parameter id
     * @param mixed  $value parameter value
     *
     * @return Container
     */
    public function setParameter($id, $value)
    {
        $id = strtolower($id);

        $this->parameters[$id] = $value;

        return $this;
    }

    /**
     * Get service definition
     *
     * @param string $id Service id
     *
     * @return Definition
     *
     * @throws \InvalidArgumentException
     */
    public function getDefinition($id)
    {
        $id = strtolower($id);

        if (array_key_exists($id, $this->aliases)) {
            $id = $this->aliases[$id];
        }

        if (array_key_exists($id, $this->serviceDefinitions)) {
            return $this->serviceDefinitions[$id];
        }

        throw new \InvalidArgumentException('service is not defined');
    }

    /**
     * Set service definition
     *
     * @param string     $id         Service id
     * @param Definition $definition Service definition
     *
     * @return Container
     */
    public function setDefinition($id, Definition $definition)
    {
        $id = strtolower($id);

        $this->serviceDefinitions[$id] = $definition;

        return $this;
    }
}
