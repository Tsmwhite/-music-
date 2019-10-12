<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/10/25
 * Time: 18:09
 *
 * 继承自 tp5 自带的 bootstrap 分页驱动, 重写封装方法
 * 用于支持 bootstrap 4.x 版本
 * 修改 URL 构建方法, 将包含当前页面的 GET 参数
 */

namespace paginator;


use think\Request;

class Bootstrap extends \think\paginator\driver\Bootstrap
{
    /**
     * 渲染分页html
     * @return mixed
     */
    public function render()
    {
        if ($this->hasPages()) {
            if ($this->simple) {
                return sprintf(
                    '<ul class="pagination">%s %s</ul>',
                    $this->getPreviousButton(),
                    $this->getNextButton()
                );
            } else {
                return sprintf(
                    '<nav><ul class="pagination justify-content-end">%s %s %s</ul></nav>',
                    $this->getPreviousButton(),
                    $this->getLinks(),
                    $this->getNextButton()
                );
            }
        }
    }


    /**
     * 生成一个可点击的按钮
     *
     * @param  string $url
     * @param  int    $page
     * @return string
     */
    protected function getAvailablePageWrapper($url, $page)
    {
        return '<li class="page-item"><a class="page-link" href="' . htmlentities($url) . '">' . $page . '</a></li>';
    }


    /**
     * 生成一个禁用的按钮
     *
     * @param  string $text
     * @return string
     */
    protected function getDisabledTextWrapper($text)
    {
        return '<li class="page-item disabled"><a class="page-link" href="javascript:void(0);">' . $text . '</a></li>';
    }


    /**
     * 生成一个激活的按钮
     *
     * @param  string $text
     * @return string
     */
    protected function getActivePageWrapper($text)
    {
        return '<li class="page-item active"><a class="page-link">' . $text . '</a></li>';
    }


    /**
     * 重写了 url 构建方法
     * @param $page
     * @param bool $filter      当该参数为 false 时,将包含当前页面的 get 参数
     * @return string
     */
    protected function url($page, $filter = false)
    {
        if ($page <= 0) {
            $page = 1;
        }

        if (strpos($this->options['path'], '[PAGE]') === false) {
            $parameters = [$this->options['var_page'] => $page];
            $path       = $this->options['path'];
        } else {
            $parameters = [];
            $path       = str_replace('[PAGE]', $page, $this->options['path']);
        }
        if (count($this->options['query']) > 0) {
            $parameters = array_merge($this->options['query'], $parameters);
        }

        // 将当前GET参数写入到分页连接中
        if (!$filter) {
            $parameters = array_merge(Request::instance()->get(), $parameters);
        }
        $url = $path;
        if (!empty($parameters)) {
            $url .= '?' . http_build_query($parameters, null, '&');
        }
        return $url . $this->buildFragment();
    }
}