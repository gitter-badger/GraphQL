<?php
/*
* This file is a part of graphql-youshido project.
*
* @author Alexandr Viniychuk <a@viniychuk.com>
* created: 11/30/15 12:36 AM
*/

namespace Youshido\GraphQL\Type;


use Youshido\GraphQL\Type\ListType\AbstractListType;
use Youshido\GraphQL\Type\Object\AbstractEnumType;
use Youshido\GraphQL\Type\Object\AbstractInputObjectType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\AbstractScalarType;
use Youshido\GraphQL\Type\Scalar\StringType;

class TypeMap
{

    const KIND_SCALAR       = 'SCALAR';
    const KIND_OBJECT       = 'OBJECT';
    const KIND_INTERFACE    = 'INTERFACE';
    const KIND_UNION        = 'UNION';
    const KIND_ENUM         = 'ENUM';
    const KIND_INPUT_OBJECT = 'INPUT_OBJECT';
    const KIND_LIST         = 'LIST';
    const KIND_NON_NULL     = 'NON_NULL';

    const TYPE_INT        = 'int';
    const TYPE_FLOAT      = 'float';
    const TYPE_STRING     = 'string';
    const TYPE_BOOLEAN    = 'boolean';
    const TYPE_ID         = 'id';
    const TYPE_DATETIME   = 'datetime';
    const TYPE_DATETIMETZ = 'datetimetz';
    const TYPE_DATE       = 'date';
    const TYPE_TIMESTAMP  = 'timestamp';

    const TYPE_FUNCTION            = 'function';
    const TYPE_OBJECT_TYPE         = 'object_type';
    const TYPE_OBJECT_INPUT_TYPE   = 'object_input_type';
    const TYPE_LIST                = 'list';
    const TYPE_ARRAY               = 'array';
    const TYPE_ARRAY_OF_FIELDS     = 'array_of_fields';
    const TYPE_ARRAY_OF_INPUTS     = 'array_of_inputs';
    const TYPE_ARRAY_OF_VALUES     = 'array_of_values';
    const TYPE_ARRAY_OF_INTERFACES = 'array_of_interfaces';
    const TYPE_ANY                 = 'any';
    const TYPE_ANY_OBJECT          = 'any_object';
    const TYPE_ANY_INPUT           = 'any_input';

    private static $scalarObjectsCache = [];

    /**
     * @param mixed|AbstractType $type
     * @return bool
     */
    public static function isInputType($type)
    {
        if (is_object($type)) {
            $type = $type->getNullableType();
            return ($type instanceof AbstractScalarType)
                   || ($type instanceof AbstractInputObjectType)
                   || ($type instanceof AbstractEnumType)
                   || ($type instanceof AbstractListType);
        } else {
            return self::isScalarType($type);
        }
    }

    public static function getNamedType($object)
    {
        if (is_object($object)) {
            if ($object instanceof AbstractType) {
                return $object->getType();
            }
        } elseif (is_null($object)) {
            return null;
        } elseif (is_scalar($object)) {
            return new StringType();
        }
        throw new \Exception('Invalid type');
    }

    /**
     * @param string $type
     *
     * @return ObjectType
     */
    public static function getScalarTypeObject($type)
    {
        if (self::isScalarType($type)) {
            if (empty(self::$scalarObjectsCache[$type])) {
                $name = ucfirst($type);
                $name = $name == 'Datetime' ? 'DateTime' : $name;
                $name = $name == 'Datetimetz' ? 'DateTimeTz' : $name;

                $className                       = 'Youshido\GraphQL\Type\Scalar\\' . $name . 'Type';
                self::$scalarObjectsCache[$type] = new $className();
            }

            return self::$scalarObjectsCache[$type];
        } else {
            return null;
        }
    }

    public static function isInterface(TypeInterface $type)
    {
        return $type->getKind() == self::KIND_INTERFACE;
    }


    public static function isScalarType($typeName)
    {
        if(is_object($typeName)) {
            return false;
        }

        return in_array($typeName, self::getScalarTypes());
    }

    /**
     * @return AbstractType[]
     */
    public static function getScalarTypes()
    {
        return [
            self::TYPE_INT,
            self::TYPE_FLOAT,
            self::TYPE_STRING,
            self::TYPE_BOOLEAN,
            self::TYPE_ID,
            self::TYPE_DATETIME,
            self::TYPE_DATE,
            self::TYPE_TIMESTAMP,
            self::TYPE_DATETIMETZ,
        ];
    }

}