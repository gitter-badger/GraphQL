<?php
/*
* This file is a part of graphql-youshido project.
*
* @author Alexandr Viniychuk <a@viniychuk.com>
* created: 11/27/15 1:11 AM
*/

namespace Youshido\Tests;

use Youshido\GraphQL\Type\TypeInterface;
use Youshido\GraphQL\Type\TypeMap;
use Youshido\Tests\DataProvider\TestScalarDataProvider;

class ScalarTypeTest extends \PHPUnit_Framework_TestCase
{

    public function testScalarPrimitives()
    {
        foreach (TypeMap::getScalarTypes() as $typeName) {
            $typeName = ucfirst($typeName);
            if ($typeName == 'Datetime') {
                $className = 'Youshido\GraphQL\Type\Scalar\DateTimeType';//  : ;
            } elseif ($typeName == 'Datetimetz') {
                $className = 'Youshido\GraphQL\Type\Scalar\DateTimeTzType';
            } else {
                $className = 'Youshido\GraphQL\Type\Scalar\\' . ucfirst($typeName) . 'Type';
            }

            /** @var TypeInterface $object */
            $object         = new $className();
            $testDataMethod = 'get' . $typeName . 'TestData';
            $this->checkDescription($object);

            foreach (call_user_func(['Youshido\Tests\DataProvider\TestScalarDataProvider', $testDataMethod]) as list($data, $serialized, $isValid)) {

                $this->checkSerialization($object, $data, $serialized);

                if ($isValid) {
                    $this->assertTrue($object->isValidValue($data), $typeName . ' validation for :' . serialize($data));
                } else {
                    $this->assertFalse($object->isValidValue($data), $typeName . ' validation for :' . serialize($data));
                }
            }
        }
    }

    private function checkDescription(TypeInterface $object)
    {
        $this->assertNotEmpty($object->getDescription());
    }

    private function checkSerialization(TypeInterface $object, $input, $expected)
    {
        $this->assertEquals($expected, $object->serialize($input), $object->getName() . ' serialize for: ' . serialize($input));
    }

}
