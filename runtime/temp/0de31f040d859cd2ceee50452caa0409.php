<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"C:\htdocs\yishu\public/../application/common\component\TableButton/TableButton.html";i:1568192928;}*/ ?>
<a href="<?php echo $data['url']; ?>"
   <?php if(!empty($data['confirm'])): ?>onclick="return confirm('<?php echo $data['confirm']; ?>')" <?php endif; ?>
    class="btn btn-sm btn-outline-<?php echo $data['type']; ?>"
    <?php if(!empty($data['formUrl'])): ?>data-form="<?php echo $data['formUrl']; ?>"<?php endif; ?>><?php echo $data['name']; ?></a>
