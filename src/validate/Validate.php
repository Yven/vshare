<?php

namespace Src\Validate;

class Validate
{
    /** @var \Src\Model\Model */
    private $_model;
    /** @var array */
    private $_rules;

    public function __construct(\Src\Model\Model $model, $rules = array())
    {
        // Model instance
        $this->_model = $model;
        // default rule
        $this->_rules = $rules;
    }

    /**
     * add rule to validate.
     *
     * @param array $rules
     *
     * @return Validate
     */
    public function add($rules)
    {
        // merge default value
        foreach ($rules as $key => $value) {
            if (array_key_exists($key, $this->_rules)) {
                array_merge($this->_rules[$key], $value);
            } else {
                $this->_rules = array_merge($this->_rules, $rules);
            }
        }

        return $this;
    }

    /**
     * reset default rules
     *
     * @param array $rules
     * @return Validate
     */
    public function reset($rules)
    {
        $this->_rules = $rules;

        return $this;
    }

    /**
     * check data by rule
     *
     * @param array $data
     * @return void
     */
    public function check($data)
    {
        // var_dump($this->_rules);
        // merge the default value and data
        if (!empty($data)) {
            $data = array_merge($this->_model->getDefault(), $data);
        }

        // search rule's function
        foreach ($this->_rules as $key => $value) {
            if (!$this->{$key}($value, $data)) {
                throw new \Exception(implode(' or ', array_keys($value)).' '.$key.': field error!', 422);
            }
        }
    }

    /**
     * require check.
     *
     * @param array $rule
     * @param array $data
     *
     * @return bool
     */
    private function require($rule, $data)
    {
        foreach ($rule as $value) {
            if (!array_key_exists($value, $data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * password check.
     *
     * @param array $rule
     * @param array $data
     *
     * @return bool
     */
    private function passcheck($rule, $data)
    {
        if (array_key_exists($rule[0], $data) && array_key_exists($rule[1], $data)) {
            if ($data[$rule[0]] !== $data[$rule[1]]) {
                return false;
            }
        }

        return true;
    }

    /**
     * length check.
     *
     * @param array $rule
     * @param array $data
     *
     * @return bool
     */
    private function length($rule, $data)
    {
        foreach ($rule as $key => $value) {
            if (array_key_exists($key, $data)) {
                // the number that value between with
                $bwt = explode(',', $value);
                // data's value
                $len = $data[$key];
                // string's length
                if (is_string($data[$key])) {
                    $len = strlen($data[$key]);
                }
                if ($len < (int) $bwt[0] || $len > (int) $bwt[1]) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * email check
     *
     * @param array $rule
     * @param array $data
     * @return boolean
     */
    private function email($rule, $data)
    {
        foreach ($rule as $value) {
            if (array_key_exists($value, $data)) {
                if (false === filter_var($data[$value], FILTER_VALIDATE_EMAIL)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * call undefine function
     *
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call($name, $arguments)
    {
        throw new \Exception('call undefine function : '.$name, 500);
    }
}
