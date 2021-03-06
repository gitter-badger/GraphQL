<?php
/*
* This file is a part of graphql-youshido project.
*
* @author Alexandr Viniychuk <a@viniychuk.com>
* created: 11/27/15 2:31 AM
*/

namespace Youshido\GraphQL\Type\Config;


use Youshido\GraphQL\Validator\ConfigValidator\ConfigValidator;
use Youshido\GraphQL\Validator\ConfigValidator\ConfigValidatorInterface;
use Youshido\GraphQL\Validator\Exception\ConfigurationException;
use Youshido\GraphQL\Validator\Exception\ValidationException;

/**
 * Class Config
 * @package Youshido\GraphQL\Type\Config
 *
 * @method string getName()
 */
class Config
{

    /**
     * @var array
     */
    protected $data = [];

    protected $contextObject;

    /** @var ConfigValidatorInterface */
    protected $validator;

    /**
     * TypeConfig constructor.
     * @param array $configData
     * @param mixed $contextObject
     *
     * @throws ConfigurationException
     * @throws ValidationException
     */
    public function __construct($configData, $contextObject = null)
    {
        if (!is_array($configData)) {
            throw new ConfigurationException('Config for Type should be an array');
        }

        $this->contextObject = $contextObject;
        $this->data          = $configData;
        $this->validator     = new ConfigValidator($contextObject);
        if (!$this->validator->validate($this->data, $this->getRules())) {
            throw new ConfigurationException('Config is not valid for ' . get_class($contextObject) . "\n" . implode("\n", $this->validator->getErrorsArray(false)));
        }
        $this->build();
    }

    public function getRules()
    {
        return [];
    }

    public function getType()
    {
        return $this->get('type');
    }

    public function getNamedType()
    {
        return $this->getType();
    }

    /**
     * @return null|callable
     */
    public function getResolveFunction()
    {
        return $this->get('resolve', null);
    }

    protected function build()
    {
    }

    /**
     * @param      $key
     * @param null $defaultValue
     * @return mixed|null|callable
     */
    public function get($key, $defaultValue = null)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : $defaultValue;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        return $this->validator->isValid();
    }

    public function __call($method, $arguments)
    {
        $propertyName = false;
        $setter       = false;

        if (substr($method, 0, 3) == 'get') {
            $propertyName = lcfirst(substr($method, 3));
        } elseif (substr($method, 0, 3) == 'set') {
            $propertyName = lcfirst(substr($method, 3));
            $setter       = true;
        } elseif (substr($method, 0, 2) == 'is') {
            $propertyName = lcfirst(substr($method, 2));
        }
        if ($propertyName !== false) {
            if ($setter) {
                $this->set($propertyName, $arguments[0]);

                return $this;
            } else {
                return $this->get($propertyName);
            }
        }

        throw new \Exception('Call to undefined method ' . $method);
    }


}
