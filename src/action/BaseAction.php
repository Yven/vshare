<?php

namespace Src\Action;

class BaseAction
{
    private $_ERR_MSG= [
        400 => "Problems parsing JSON",
        401 => "Bad credentials",
        403 => "Counldn't access this resource",
        404 => "There is no this resource",
        422 => "Validation Failed"
    ];

    protected $_documentationURL = "http://";

    protected $_request;
    protected $_response;

    public function __construct(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response
    ){
        $this->_request = $request;
        $this->_response = $response;
    }

    /**
     * 正确响应设置
     * @param [array] $header 响应头数组
     * @param [array] $info 正确信息
     * @return ResponseInterface|null
     */
    protected function successResponse($info, $header = array()){
        // 设置响应体
        $this->_response = $this->_response->withJson($info, 200);
        // 自定义响应头设置
        if (!$header) {
            return $this->_response;
        } elseif (is_array($header)) {
            foreach ($header as $key => $value) {
                $this->_response = $this->_response->withHeader($key, $value);
            }
            return $this->_response;
        } else {
            // 响应头格式错误
            return null;
        }
    }

    /**
     * 错误响应设置
     * @param [int] $code 状态码
     * @param [string] $info=null 错误信息
     * @return ResponseInterface
     */
    protected function errorResponse($code, $info=null){
        return $this->_response->withJson([
            "message" => is_null($info) ? (array_key_exists($code, $this->_ERR_MSG) ? $this->_ERR_MSG[$code] : "Unknow Operation") : $info,
            "documentation" => $_documentationURL
        ], $code);
    }
}
