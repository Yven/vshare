<?php

namespace Src;

class Common
{
    private $_container;

    public function __construct(\Slim\Container $container)
    {
        $this->_container = $container;
    }

    public function csrf($request, $response, $args)
    {
        $nameKey = $this->_container->get('csrf')->getTokenNameKey();
        $valueKey = $this->_container->get('csrf')->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        return $response->withJson([
            $nameKey => $name,
            $valueKey => $value,
        ], 201);
    }
}
