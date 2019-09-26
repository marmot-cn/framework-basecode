<?php
//powered by chloroplast
namespace Marmot\Basecode\View;

use Marmot\Interfaces\IResponseFormatter;

/**
 * 默认的 响应输出格式化
 *
 * @author chloroplast
 */
class DefaultResponseFormatter implements IResponseFormatter
{
    public function format($response)
    {
        if ($response->data !== null) {
            $response->content = $response->data;
        }
    }
}
