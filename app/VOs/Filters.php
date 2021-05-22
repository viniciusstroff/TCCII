<?php
namespace App\VOs;

use Carbon\Carbon;
use ArrayAccess;
use Countable;
use IteratorAggregate;

class Filters implements ArrayAccess, IteratorAggregate, Countable
{
    private $filters;

    public function __construct(?Array $filters)
    {
        $this->setFilters($filters);
    }

    public function getDate($fieldName)
    {
        $field = $this->has($fieldName) ? $this->$fieldName : null;
        if ($field) {
            return Carbon::createFromFormat('d/m/Y', $field)->startOfDay();
        }
        return null;
    }

    public function has($key)
    {
        $keys = is_array($key) ? $key : func_get_args();

        foreach ($keys as $value) {
            if ($this->isEmptyString($value)) {
                return false;
            }
        }

        return true;
    }
    private function isEmptyString($key)
    {
        $value = $this->filters->get($key);
        $boolOrArray = is_bool($value) || is_array($value);
        return ! $boolOrArray && trim((string) $value) === '';
    }

    //Allow call array index as object attribute
    public function getFilter($attribute)
    {
        $exists = $this->filters->has($attribute);
        return ($exists) ? $this->filters[$attribute] : null;
    }

    public function count()
    {
 //Countable interface methd
        return $this->filters->count();
    }
    public function getIterator()
    {
 //IteratorAggregate interface method
        return $this->filters->getIterator();
    }
    public function offsetExists($offset)
    {
        return $this->filters->offsetExists($offset);
    }
    public function offsetGet($offset)
    {
        return $this->filters->offsetGet($offset);
    }
    public function offsetSet($offset, $value)
    {
        return $this->filters->offsetSet($offset, $value);
    }
    public function offsetUnset($offset)
    {
        return $this->filters->offsetUnset($offset);
    }

    public function setFilters(Array $filters)
    {
        $this->filters = collect($filters);
    }
}
