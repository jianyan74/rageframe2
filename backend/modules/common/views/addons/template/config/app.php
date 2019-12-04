<?php

use common\helpers\StringHelper;

$menuCount = 0;
$menus = $bindings['menu'][$appID] ?? [];
if (isset($menus['title'])) {
    $menuCount = count($menus['title']);
}

$coverCount = 0;
$covers = $bindings['cover'][$appID] ?? [];
if (isset($covers['title'])) {
    $coverCount = count($covers['title']);
}

echo "<?php\n";
?>

return [
    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [
<?php for ($i = 0; $i < $coverCount; $i++){
    if (!empty($covers['title'][$i]) && !empty($covers['route'][$i])){
        $params = !empty($covers['params'][$i]) ? StringHelper::parseAttr($covers['params'][$i]) : [];
        ?>
        [
            'title' => '<?= trim($covers['title'][$i]); ?>',
            'route' => '<?= trim($covers['route'][$i]); ?>',
            'icon' => '<?= trim($covers['icon'][$i]); ?>',
            'params' => [
            <?php foreach ($params as $key => $param) { ?>
    '<?= trim($key); ?>' => '<?= trim($param); ?>',
            <?php } ?>

            ],
        ],
    <?php }
} ?>

    ],

    // ----------------------- 菜单配置 ----------------------- //
    'menuConfig' => [
         'location' => 'addons', // default:系统顶部菜单;addons:应用中心菜单
         'icon' => 'fa fa-puzzle-piece',
    ],

    'menu' => [
<?php for ($i = 0; $i < $menuCount; $i++){
    if (!empty($menus['title'][$i]) && !empty($menus['route'][$i])){
        $params = !empty($menus['params'][$i]) ? StringHelper::parseAttr($menus['params'][$i]) : [];
        ?>
        [
            'title' => '<?= trim($menus['title'][$i]); ?>',
            'route' => '<?= trim($menus['route'][$i]); ?>',
            'icon' => '<?= trim($menus['icon'][$i]); ?>',
            'params' => [
            <?php foreach ($params as $key => $param) { ?>
    '<?= trim($key); ?>' => '<?= trim($param); ?>',
            <?php } ?>

            ],
            'child' => [

            ],
        ],
    <?php }
}
?>

    ],

    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [

    ],
];