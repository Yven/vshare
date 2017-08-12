<?php

namespace Src\Model;

use Src\Config;

class Model extends \FluentPDO
{
    public function __construct()
    {
        // 初始化FPDO
        parent::__construct(Config::get('db'));
        $this->setDefault();
    }

    /**
     * get the fields
     *
     * @return void
     */
    private function setDefault()
    {
        if (isset($this->_default) || empty($this->_default)) {
            foreach ($this->getTableField() as $value) {
                // if is pair key or the value has been defined
                if ($value == "id" || array_key_exists($value, $this->_default)) {
                    continue;
                } else {
                    $this->_default[$value] = '';
                }
            }
        }
    }
}
