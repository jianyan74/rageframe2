<div id="<?= $name; ?>"></div>

<?php

$this->registerJs(<<<JS
    var treeId = $name;
    var treeCheckIds = JSON.parse('$selectIds');
    var treeData = JSON.parse('$defaultData');
    
    showCheckboxTree(treeData, $(treeId).attr('id'), treeCheckIds);
JS
);
?>