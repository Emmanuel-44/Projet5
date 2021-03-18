<?php
namespace core;

/**
 * Entity class
 */
class Entity
{
        /**
         * Construct
         *
         * @param array $values values array
         */
    public function __construct($values = [])
    {
        if (!empty($values)) {
            $this->hydrate($values);
        }
    }
    
    /**
     * Hydrate
     *
     * @param [array] $datas datas array
     * 
     * @return void
     */
    public function hydrate($datas)
    {
        foreach ($datas as $attribut => $value) {
            $method = 'set'.ucfirst($attribut);
        
            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }
}
