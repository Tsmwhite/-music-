<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/12/21
 * Time: 15:35
 */

namespace app;

use think\Controller;
use think\Request;
use validate\Validate;
use app\common\exception\FormValidateException;


/**
 * Class FormController
 * @method array fetchFields($fields)
 * @package app
 */
class FormController extends Controller
{
//    public static $fields  = [
//        'account'       =>  '账号',
//        'nickname'      =>  '昵称',
//        'realname'      =>  ['真实姓名', 'input', 'require'],
//        'avatar'        =>  ['头像', 'image', 'require'],
//        'year'          =>  ['年份', 'datetime', 'require', null],
//        'sex'           =>  ['性别', 'selector', 'require', null, [
//            'list'      =>  [
//                ['id' => 1, 'name' => '男'],
//                ['id' => 2, 'name' => '女']
//            ],
//            'field'     =>  'name',
//            'value'     =>  'id'
//        ]]
//    ];
    public static $fields = [];

    public $method = 'post';


    /**
     * 返回表单页面
     * @return mixed
     * @throws \Exception
     */
    public function index ()
    {
        $class = explode('\\', static::class);
        $form = end($class);
        return $this->assign([
            'formView'  =>  $this->fetchForm(),
            'method'    =>  $this->method ? $this->method : 'post',
            'action'    =>  url('api/form/submit', ['form' => static::class])
        ])->fetch('./template/form_wrap.html');
    }


    /**
     * 验证表单提交结果的验证和预处理
     * @return array
     * @throws FormValidateException
     */
    public static function check ()
    {
        $params = Request::instance()->param();
        // 数据预处理
        $data = [];
        foreach (static::$fields as $f => $i) {
            foreach ($params as $pk => $pv) {
                if ($f === $pk) {
                    $form = getViewComponent('form_' . $i[1]);

                    if ($form !== null && method_exists($form, 'getParam')) {
                        $form::getParam($pv, $pk, $data);
                    } else {
                        getViewComponent('form_input')::getParam($pv, $pk, $data);
                    }
                }
            }
        }


        // 生成验证规则
        $rules = [];
        foreach (static::$fields as $field => $info) {
            if (is_array($info) && !empty($info[2])) {
                $rules[$field . '|' . static::$fields[$field][0]] = $info[2];
            }
        }

        $validate = new Validate($rules);
        if (!$validate->check($data)) {
            throw new FormValidateException($validate->getError());
        }


        return $data;
    }


    /**
     * 处理表单提交
     */
    public static function submit () {}


    /**
     * 获取表单的视图
     * @return string
     * @throws \Exception
     */
    protected function fetchForm ()
    {
        $v = '';
        $fields = static::$fields;

        if (method_exists($this, 'fetchFields')) {
            $fields = $this->fetchFields($fields);
        }

        if ($fields && is_array($fields)) {
            foreach ($fields as $field => $val) {

                if (is_array($val)) {
                    $v .= getViewContent('form_' . $val[1], [
                        'fieldName' => $val[0],
                        'field' => $field,
                        'msg'  => $val[3] ?? null,
                        'value' => $val['def'] ?? null,
                        'data' => $val[4] ?? null
                    ]);
                } elseif (is_string($val)) {
                    $v .= getViewContent('form_input', [
                        'fieldName' => $val,
                        'field' => $field,
                    ]);
                }

            }
        }


        return $v;
    }
}