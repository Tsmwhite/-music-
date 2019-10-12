<?php
namespace app\api\controller;

use think\Controller;
#use think\helper\Time;
#use controller\BasicWechat;
#use service\DataService;
#use service\PayService;
use think\Db;

use DexterHo\FastPayPal\PayConfig;
use DexterHo\FastPayPal\FastPayPal;
use DexterHo\FastPayPal\Notify;
use DexterHo\FastPayPal\Refund;

class Paypal extends Controller
{
    private $pay_config;

    public function __construct()
    {
        parent::__construct();
        import('dexterho.fast-paypal.src.PayConfig', EXTEND_PATH, '.php');
        import('dexterho.fast-paypal.src.FastPayPal', EXTEND_PATH, '.php');
        import('dexterho.fast-paypal.src.Notify', EXTEND_PATH, '.php');
        import('dexterho.fast-paypal.src.Refund', EXTEND_PATH, '.php');
        $pay_config = [
            /**
             * 支付参数
             */
            'url'                  => 'https://www.sandbox.paypal.com/cgi-bin/webscr', // Live https://www.paypal.com/cgi-bin/webscr
            'cmd'                  => '_xclick', // 告诉paypal 是立即购买
            'business'             => '764990304-facilitator@qq.com', // 收款账号 buy 920625788-buyer@qq.com, pwd 12345678
            'item_name'            => 'test', // 商品名称
            'item_number'          => 'test', // 商品编号
            'amount'               => '10', // 支付金额
            'currency_code'        => 'USD', //
            'return'               => 'http://wemusic.ikenweb.com/api/paypal/notify',  // 同步回调地址
            'notify_url'           => 'http://wemusic.ikenweb.com/api/paypal/notify', // 异步回调地址，一定得外网可以访问到的！！！
            'cancel_return'        => 'http://wemusic.ikenweb.com', // 在支付界面取消返回商家的地址
            'invoice'              => '111111111111', // 订单号
            'charset'              => 'utf-8', // 字符集
            'no_shipping'          => '1', // 不要求客户提供收货地址
            'no_note'              => '1', // 付款说明
            'rm'                   => '2', // 没找到说明该参数的文档
            /**
             * 退款参数
             */
            'client_id' => 'AceApz30EObKX0owUIbgKXOGlAi4Q7yEq0CvP9iawZV4r0bR_3O4b6WjSziLfzJqEj0aYy3E-y6n9tVF',
            'client_secret' => 'EJtDLBeUdl7UKNwMYQ6yU-NoIq3RfTf44x4jWXocobPuYLTuh-lQs7Pi9jB0a9GEUri7hT9tW5m_WSxn',
            'refund_amount'        => '0',
            'refund_currency_code' => 'USD',
            'model'                => '', // live 环境下请将modle 的值设置为 live
            'txn_id'               => '', // 交易ID，异步回调可以获取到
        ];
        $this->pay_config = new PayConfig($pay_config);
    }

    public function index()
    {
        echo 'Paypal test!';
    }

    public function pay($array)
    {
        $this->pay_config->invoice = $array['order_id']; //input('order_num'); // 订单号不能重复
        $this->pay_config->item_name = $array['order_name']; //input('order_name');// 商品名称
        $this->pay_config->item_number = $array['order_money_id']; ///input('order_num');// 商品编号
        $this->pay_config->amount =  $array['order_money']; //input('order_money');// 支付金额
        $this->pay_config->currency_code = 'USD'; // 支付货币
        $pay = FastPayPal::redirectPayBody($this->pay_config);
        return $pay;
    }

    public function onepay($array)
    {
        $this->pay_config->invoice = $array['order_id']; //input('order_num'); // 订单号不能重复
        $this->pay_config->item_name = $array['order_name']; //input('order_name');// 商品名称
        $this->pay_config->item_number = $array['order_money_id']; ///input('order_num');// 商品编号
        $this->pay_config->amount =  $array['order_money']; //input('order_money');// 支付金额
        $this->pay_config->currency_code = 'USD'; // 支付货币
        $this->pay_config->notify_url = 'http://wemusic.ikenweb.com/api/paypal/onenotify'; // 回调
        $pay = FastPayPal::redirectPayBody($this->pay_config);
        return $pay;
    }
    public function pay1()
    {

        $pay = FastPayPal::redirectPayBody($this->pay_config);
        var_dump($pay);
    }
    /**
     * 异步回调
     * @return string
     */
    public function notify()
    {
        $post = $_REQUEST;
        //$this->logs($post, 'REQUEST'); // 日志

        $url = $this->pay_config->url;

        $data['cmd'] = '_notify-validate'; // 增加cmd参数，

        foreach ($post as $key => $item) $data[$key] = ($item);  // 如果数据验证失败，请在这里将参数urlencode
        Db::name("paypal_log")->insert(array('body' => json_encode($data), 'time' => time()));
        $res = self::http($url, $data, 'POST'); // 验证回调数据，必须！ 为了安全
        //var_dump($res);
        if (!empty($res)) {
            if (strcmp($res, "VERIFIED") == 0) {

                if ($post['payment_status'] == 'Completed') {
                    //成功修改订单状态
                    $money = Db::name("paypal_list")->where(['id' => $post['invoice']])->find();
                    if ($money['type'] == 0) {
                        Db::name("student")->where('id', $money['user_id'])->update(['is_vip' => 2, 'money' => Db::raw('money+' . ($money['money'] + $money['give_money']))]);
                        Db::name("paypal_list")->where(['id' => $post['invoice']])->update(['type' => 1, 'ok_time' => time(), 'data' => json_encode($data)]);
                    }
                    // 付款完成，这里修改订单状态，注意：同步和异步都会有订单号返回，请注意防止重复处理
                    // 订单号：$post['invoice']
                    // 本次交易ID：$post['txn_id'] 记得保留，用于退款
                    echo 'success. txn_id:' . $post['txn_id'] . ' order_id:' . $post['invoice'];
                    exit;
                }
            } elseif (strcmp($res, "INVALID") == 0) {
                //未通过认证，有可能是编码错误或非法的 POST 信息
                echo 'fail';
                exit;
            }
        } else {
            //未通过认证，有可能是编码错误或非法的 POST 信息

            echo 'fail';
            exit;
        }
        echo 'fail';
        exit;
        /*
         同步返回结果
        {
            "payer_email":"764990304-buyer@qq.com",
            "payer_id":"U26MN87AT6JT4",
            "payer_status":"VERIFIED",
            "first_name":"test",
            "last_name":"buyer",
            "txn_id":"7TL264676A985080U",
            "mc_currency":"USD",
            "mc_fee":"0.64",
            "mc_gross":"10.00",
            "protection_eligibility":"ELIGIBLE",
            "payment_fee":"0.64",
            "payment_gross":"10.00",
            "payment_status":"Completed",
            "payment_type":"instant",
            "item_name":"test",
            "item_number":"test",
            "quantity":"1",
            "txn_type":"web_accept",
            "payment_date":"2019-03-25T04:20:00Z",
            "business":"764990304-facilitator@qq.com",
            "receiver_id":"TA3KZXWFA2BWN",
            "notify_version":"UNVERSIONED",
            "invoice":"388968354",
            "verify_sign":"AGRlivd-w1.x76rooYVtlFKN43YvArdR8CRZMFBXTKmOL58FkF-X-0ML"
        }

        异步返回结果
        {
            "mc_gross":"10.00",
            "invoice":"116903697",
            "protection_eligibility":"Eligible",
            "payer_id":"U26MN87AT6JT4",
            "payment_date":"23:32:43 Mar 24, 2019 PDT",
            "payment_status":"Completed",
            "charset":"gb2312",
            "first_name":"test",
            "mc_fee":"0.64",
            "notify_version":"3.9",
            "custom":"",
            "payer_status":"verified",
            "business":"764990304-facilitator@qq.com",
            "quantity":"1",
            "verify_sign":"AOXxW.y7kXQpSbApwJHSZHXiqJlqAm2lTzi2XQnTM7niBd6h1R.p9rVq",
            "payer_email":"764990304-buyer@qq.com",
            "txn_id":"56766261WR018210Y",
            "payment_type":"instant",
            "last_name":"buyer",
            "receiver_email":"764990304-facilitator@qq.com",
            "payment_fee":"0.64",
            "shipping_discount":"0.00",
            "receiver_id":"TA3KZXWFA2BWN",
            "insurance_amount":"0.00",
            "txn_type":"web_accept",
            "item_name":"test",
            "discount":"0.00",
            "mc_currency":"USD",
            "item_number":"test",
            "residence_country":"CN",
            "test_ipn":"1",
            "shipping_method":"Default",
            "transaction_subject":"",
            "payment_gross":"10.00",
            "ipn_track_id":"276fb034e73a"
        }
        */
    }



    /**
     * 异步回调
     * @return string
     */
    public function onenotify()
    {
        $post = $_REQUEST;
        //$this->logs($post, 'REQUEST'); // 日志

        // exit;
        $url = $this->pay_config->url;

        $data['cmd'] = '_notify-validate'; // 增加cmd参数，

        foreach ($post as $key => $item) {
            $data[$key] = ($item);
        };  // 如果数据验证失败，请在这里将参数urlencode
        Db::name("paypal_log")->insert(array('body' => json_encode($data), 'time' => time()));
        $res = self::http($url, $data, 'POST'); // 验证回调数据，必须！ 为了安全

        if (!empty($res)) {
            if (strcmp($res, "VERIFIED") == 0) {
                if ($post['payment_status'] == 'Completed') {
                    //成功修改订单状态
                    $money = Db::name("one_paypal_list")->where(['id' => $post['invoice']])->find();
                    if ($money['type'] == 0) {
                        $class_list = Db::name("class_list")->where('id', $post['item_number'])->find();
                        if ($class_list) {
                            Db::table('class_list')->where('id', $post['item_number'])->update(array('type' => 3));
                            Db::table('order')->insert(array('class_id' => $post['item_number'], 'student_id' => $class_list['student_id'], 'teacher_id' => $class_list['teacher_id'], 'type' => 1, 'time' => time(), 'price' => $class_list['price']));
                        }
                        Db::name("one_paypal_list")->where(['id' => $post['invoice']])->update(['type' => 1, 'ok_time' => time(), 'data' => json_encode($data)]);
                    }
                    // 付款完成，这里修改订单状态，注意：同步和异步都会有订单号返回，请注意防止重复处理
                    // 订单号：$post['invoice']
                    // 本次交易ID：$post['txn_id'] 记得保留，用于退款
                    echo 'success. txn_id:' . $post['txn_id'] . ' order_id:' . $post['invoice'];
                    exit;
                }
            } elseif (strcmp($res, "INVALID") == 0) {
                //未通过认证，有可能是编码错误或非法的 POST 信息
                echo 'fail';
                exit;
            }
        } else {
            //未通过认证，有可能是编码错误或非法的 POST 信息

            echo 'fail';
            exit;
        }
        echo 'fail';
        exit;
    }

    /**
     * 退款
     * @return array|\PayPal\Api\Refund
     */
    public function refunddddddd()
    {
        $this->pay_config->txn_id = '1VV24730UK063530S';
        $this->pay_config->refund_amount = '5'; // 退款金额
        $this->pay_config->refund_currency_code = 'USD'; // 退款货币
        $res = Refund::payPalRefundForTxnId($this->pay_config); // 成功返回的是个对象
        $this->logs($res, 'refund');
        if (isset($res->state)) {
            if ($res->state == 'completed') {
                echo '退款成功';
                exit;
            } else {
                echo '退款失败';
                exit;
            }
        } else {
            echo '退款失败';
            exit;
        }

        /*
         正确时返回结果：
           {
            "id":"2GN60820SW920893G",
            "create_time":"2019-03-25T04:30:45Z",
            "update_time":"2019-03-25T04:30:45Z",
            "state":"completed",
            "amount":{
                "total":"10.00",
                "currency":"USD"
            },
            "refund_from_transaction_fee":{
                "currency":"USD",
                "value":"0.34"
            },
            "total_refunded_amount":{
                "currency":"USD",
                "value":"10.00"
            },
            "refund_from_received_amount":{
                "currency":"USD",
                "value":"9.66"
            },
            "sale_id":"7TL264676A985080U",
            "parent_payment":"PAY-4JD194871S538523MLSMFN3Y",
            "links":[
                {
                    "href":"https://api.sandbox.paypal.com/v1/payments/refund/2GN60820SW920893G",
                    "rel":"self",
                    "method":"GET"
                },
                {
                    "href":"https://api.sandbox.paypal.com/v1/payments/payment/PAY-4JD194871S538523MLSMFN3Y",
                    "rel":"parent_payment",
                    "method":"GET"
                },
                {
                    "href":"https://api.sandbox.paypal.com/v1/payments/sale/7TL264676A985080U",
                    "rel":"sale",
                    "method":"GET"
                }
            ]
        }*/
    }

    /**
     * 日志
     * @param $data
     * @param $parameter
     */
    public function logs($data, $parameter)
    {
        if (is_array($data) || is_object($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        $data = date('Y-m-d H:i:s', time()) . " - " . $data . " - " .  $parameter;
        $filename = __DIR__ . "/logs.txt";
        if (file_exists($filename)) {
            $goldlock_time = @filemtime($filename);
        }
        $handle = fopen($filename, "a+");
        $str = fwrite($handle, "{$data}\r\n");
        fclose($handle);
    }

    private static function http($url, $data = [], $method = 'GET')
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            if ($data != '') {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
            }
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
}
