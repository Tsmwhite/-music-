<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:68:"C:\htdocs\yishu\public/../application/admin\view\feedback\index.html";i:1568192922;s:69:"C:\htdocs\yishu\public/../application/common\component\Menu/Menu.html";i:1570551403;}*/ ?>
<dl class="<?php echo $deep==1?'layui-nav layui-nav-tree' : 'layui-nav-child'; ?>" data-level="<?php echo isset($deep)?$deep: 'unknow'; ?>">
    <?php foreach($list as $vo): ?>
    <dd class="<?php echo $deep == 1 ? 'layui-nav-item' : ''; if ($m == ($vo['node_module'] ?? '') && $c == ($vo['node_controller'] ?? '') && $a == ($vo['node_action'] ?? '') && $deep != 1) {echo 'layui-nav-itemed';} ?>" data-i="<?php echo $vo['menu_nid']; ?>">
        <a data-left="<?php echo $deep; ?>" data-link="<?php echo $vo['menu_id']; ?>" href="<?php echo !empty($vo['menu_nid'])?('/' . $vo['node_module'] . '/' . $vo['node_controller'] . '/' . $vo['node_action']) : 'javascript:void(0);'; ?>"><i class="<?php echo $vo['menu_class']; ?> menu-icon"></i><?php echo $vo['menu_name']; ?></a>
        <?php if(count($vo['child_menu'] ?? [])): ?>
        <?php echo \app\common\component\Menu\Menu::getContent(['list' => $vo['child_menu'], 'deep' => ($deep + 1)]); endif; ?>
    </dd>
    <?php endforeach; ?>
</dl>