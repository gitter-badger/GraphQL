<?php
/**
 * Date: 01.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQL\Parser\Value;


interface ValueInterface
{

    public function getValue();

    public function setValue($value);
}