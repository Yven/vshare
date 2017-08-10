<?php

namespace Src\Model;

use Src\Config;

class Admin extends \FluentPDO
{
    public function __construct()
    {
        // 初始化FPDO
        parent::__construct(Config::get('db'));
    }
}
