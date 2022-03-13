<?php
namespace ApiModule\GraphQL;

use GraphQL\Type\Definition\Type;
use Nextras\Dbal\Result\Result;

class BlackMagicCollection implements \ArrayAccess
{
    /** @var Type */
    protected $type;

    /** @var array  */
    protected $fields = [];

    public function __construct(Type $whatShouldIReturn, array $fields)
    {
        $this->type = $whatShouldIReturn;
        $this->fields = $fields;
    }

    public function offsetExists($field)
    {
        return isset($this->fields[$field]);
    }

    public function offsetGet($field)
    {
        $value = $this->fields[$field];
        if(is_callable($value)){
            $value = $value();
            if($value instanceof Result){
                return json_encode($value->fetch());
            }
        }
        return $value;
    }

    public function offsetSet($field, $value)
    {
        $this->fields[$field] = $value;
    }

    public function offsetUnset($field)
    {
        unset($this->fields[$field]);
    }
    
    protected function getFieldReturnType($field){
        return $this->type->config[$field];
    }
}
