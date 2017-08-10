<?php

namespace Src\Action;

class BaseAction
{
    /**
     * 默认错误响应体信息.
     *
     * @var array
     */
    protected $_ERR_MSG = [
        400 => 'Problems parsing JSON',
        401 => 'Bad credentials',
        403 => "Counldn't access this resource",
        404 => 'There is no this resource',
        422 => 'Validation Failed',
    ];

    /**
     * 帮助地址
     *
     * @var string
     */
    private $_documentationURL;

    /**
     * 响应类.
     *
     * @var ResponseInterface
     */
    private $_response;

    /**
     * init Response and help URL
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(\Psr\Http\Message\ResponseInterface $response) {
        // 对操作请求进行，并返回响应
        $this->_response = $response;
        // 初始化帮助地址
        $this->_documentationURL = dirname('http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['PHP_SELF']);
    }

    /**
     * get Response
     *
     * @return Response
     */
    public function getResponse(){
        return $this->_response;
    }

    /**
     * setting set-cookie header
     *
     * @param \Slim\Http\Cookies $cookie
     * @param array|string $value
     * @param string $expires
     * @param boolean $httponly
     * @return void
     */
    protected function cookie($cookie, $value, $expires = null, $httponly = false){
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $cookie->set($k, [
                    "value" => $v,
                    "path" => "/vshare2",
                    "expires" => $expires,
                    "httponly" => $httponly
                ]);
            }
            foreach ($cookie->toHeaders() as $c) {
                $this->_response = $this->_response->withAddedHeader("Set-Cookie", $c);
            }
        }
    }

    /**
     * 正确响应设置.
     *
     * @param array $data   正确数据
     *
     * @return ResponseInterface|null
     */
    protected function success($data)
    {
        // 设置响应体
        $this->_response = $this->_response->withJson($data, 200);
        return $this->_response;
    }

    /**
     * 错误响应设置.
     *
     * @param int    $code      错误码
     * @param string $info=null 错误信息
     *
     * @return ResponseInterface
     */
    protected function error($code, $info = null)
    {
        return $this->_response->withJson([
            'message' => is_null($info) ? (array_key_exists($code, $this->_ERR_MSG) ? $this->_ERR_MSG[$code] : 'Unknow Operation') : $info,
            'documentation' => $this->_documentationURL,
        ], $code);
    }
}
