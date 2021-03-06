<?php
/*
 * This file is a part of GraphQL project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 11:54 AM 5/5/16
 */

namespace Youshido\GraphQL\Type\Object;


use Youshido\GraphQL\Type\Traits\FinalTypesConfigTrait;
use Youshido\GraphQL\Type\TypeMap;

final class UnionType extends AbstractUnionType
{
    use FinalTypesConfigTrait;

    public function resolveType($object)
    {
        return TypeMap::getNamedType($object);
    }

    public function getTypes()
    {
        return $this->getConfig()->get('types', []);
    }

}