<?php
/**
 * Date: 16.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\Tests\Type\Union\Schema;


use Youshido\GraphQL\Type\Config\TypeConfigInterface;
use Youshido\GraphQL\Type\Object\AbstractObjectType;

class FirstType extends AbstractObjectType
{

    public function resolve($value = null, $args = [], $type = null)
    {

    }

    /**
     * @return String type name
     */
    public function getName()
    {
        return 'FirstType';
    }

    public function build($config)
    {
        $config
            ->addField('name', 'string')
            ->addField('description', 'string')
            ->addField('secondName', 'string');
    }
}