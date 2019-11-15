<?php

use common\helpers\Html;

?>
<?= $form->field($model, $provincesName)->hiddenInput(['id' => 'provinceIds'])->label(false) ?>

<?php if ($level >= 2) {
    echo $form->field($model, $cityName)->hiddenInput(['id' => 'cityIds'])->label(false);
} ?>
<?php if ($level >= 3) {
    echo $form->field($model, $areaName)->hiddenInput(['id' => 'areaIds'])->label(false);
} ?>

<!--模拟框加载 -->
<div class="modal fade" id="ajaxModalLgForExpress" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closeAjaxModalLgForExpress"><span aria-hidden="true">×</span><span
                            class="sr-only">关闭</span></button>
                <h4 class="modal-title">选择地区</h4>
            </div>
            <div class="modal-body">
                <table class="table m-b-none">
                    <tbody>
                    <?php foreach ($addressList as $key => $item) { ?>
                        <tr>
                            <td width="20%" align="center">
                                <?= Html::checkbox('region', false, [
                                    'data-first-index' => $key,
                                ]) ?>
                                <label for=""><?= $key; ?></label>
                            </td>
                            <td class="js-regions">
                                <?php foreach ($item as $province) { ?>
                                    <div class="shopProvinces">
                                        <?= Html::checkbox('province[]', false, [
                                            'data-second-parent-index' => $key,
                                            'data-is-disabled' => $province['is_disabled'],
                                            'data-province-id' => $province['id'],
                                            'data-province-name' => $province['title'],
                                            'value' => $province['id'],
                                            'disabled' => $province['is_disabled']
                                        ]); ?>
                                        <label for=""><?= $province['title']; ?></label>
                                        <!-- 显示市-->
                                        <?php if ($level >= 2) { ?>
                                            <i class="icon ion-arrow-down-b drop-down" data-level="province"
                                               data-open="0"></i>
                                            <div class="shopCitys">
                                                <?php foreach ($province['-'] as $city) { ?>
                                                    <div>
                                                        <?= Html::checkbox('city[]', false, [
                                                            'data-third-parent-index' => $key,
                                                            'data-is-disabled' => $city['is_disabled'],
                                                            'data-province-id' => $province['id'],
                                                            'data-city-id' => $city['id'],
                                                            'value' => $city['id'],
                                                            'disabled' => $city['is_disabled']
                                                        ]) ?>
                                                        <label for=""><?= $city['title']; ?></label>
                                                        <!-- 显示区-->
                                                        <?php if ($level >= 3) { ?>
                                                            <i class="icon ion-arrow-down-b drop-down" data-level="city"
                                                               data-open="0"></i>
                                                            <div class="shopAreas">
                                                                <?php foreach ($city['-'] as $area) { ?>
                                                                    <span>
                                                            <?= Html::checkbox('area[]', false, [
                                                                'data-four-parent-index' => $key,
                                                                'data-is-disabled' => $area['is_disabled'],
                                                                'data-province-id' => $province['id'],
                                                                'data-city-id' => $city['id'],
                                                                'value' => $area['id'],
                                                                'disabled' => $area['is_disabled']
                                                            ]); ?>
                                                                <label for=""><?= $area['title']; ?></label>
                                                            </span>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white closeAjaxModalLgForExpress">关闭</button>
                <button type="button" class="btn btn-primary js-confirm closeAjaxModalLgForExpress">保存</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="feeId" value="<?= $model['id'] ?>"/>

<script>
    /**
     * 确定选择地区
     */
    $(".js-confirm").click(function () {
        setProvinceIdArray();
        setCityIdArray();
        setDistrictIdArray();
        $(".js-region-info").html(getRegions());
    });

    // 隐藏model
    $(".closeAjaxModalLgForExpress").click(function () {
        $('#ajaxModalLgForExpress').modal("hide");
    });

    // 小模拟框清除
    $('#ajaxModalLgForExpress').on('hide.bs.modal', function () {
        $('.shopCitys').removeClass('show');
        $('.shopAreas').removeClass('show');
        $('.shopProvinces').removeClass('open');
    });

    // 选择下拉框
    $('.drop-down').click(function () {
        var level = $(this).data('level');
        if (level == 'province') {
            $('.shopCitys').removeClass('show');
            $('.shopAreas').removeClass('show');
            $('.shopProvinces').removeClass('open');
            if ($(this).data('open') == 0) {
                $(this).next().addClass('show');
                $(this).parent().addClass('open');
                $(this).data('open', 1);
            } else {
                $(this).data('open', 0);
            }
        } else {
            var left = $(this).prev().position().left - 25;
            var top = $(this).position().top + 16;

            $('.shopAreas').removeClass('show');
            if ($(this).data('open') == 0) {
                $(this).next().addClass('show');
                $(this).next().css({
                    'left': left,
                    'top': top,
                });
                $(this).data('open', 1);
            } else {
                $(this).data('open', 0);
            }
        }
    });

    /**
     * 修改运费模板时，把弹出框的地区选择选中
     */
    if (parseInt($("#feeId").val())) {
        //省id组
        if ($("#provinceIds").val()) {
            var province_id_array = $("#provinceIds").val().split(",");
            for (var i = 0; i < province_id_array.length; i++) {

                if (province_id_array[i]) {
                    $("input[data-second-parent-index][value='" + province_id_array[i] + "']").prop("checked", true);
                }
            }
        }

        //市id组
        if ($("#cityIds").val()) {
            var city_id_array = $("#cityIds").val().split(",");
            for (var i = 0; i < city_id_array.length; i++) {
                if (city_id_array[i]) {
                    $("input[data-third-parent-index][value='" + city_id_array[i] + "']").prop("checked", true);
                }
            }
        }

        //区县id组
        if ($("#areaIds").val()) {
            var district_id_array = $("#areaIds").val().split(",");
            for (var i = 0; i < district_id_array.length; i++) {
                if (district_id_array[i]) {
                    $("input[data-four-parent-index][value='" + district_id_array[i] + "']").prop("checked", true);
                }
            }
        }
        $(".js-region-info").html(getRegions());
    }

    /**
     * 一级地区（大类）例如：华北、华东、东北、西北、港澳台等
     * 根据当前地区的选中状态对应的改变它的子地区
     */
    $("input[data-first-index]").change(function () {
        if (!$(this).is(":disabled") && !$(this).attr("data-is-disabled")) {
            var curr = $(this);//当前对象
            var index = curr.attr("data-first-index");//索引
            var checked = curr.is(":checked");//选中状态
            //省
            if ($("input[data-second-parent-index='" + index + "']").length) {
                $("input[data-second-parent-index='" + index + "']").each(function () {
                    if (!$(this).is(":disabled") && !$(this).attr("data-is-disabled")) {
                        $(this).prop("checked", checked);
                    }
                });

                //市
                if ($("input[data-third-parent-index='" + index + "']").length) {
                    $("input[data-third-parent-index='" + index + "']").each(function () {
                        if (!$(this).is(":disabled") && !$(this).attr("data-is-disabled")) {
                            $(this).prop("checked", checked);
                        }
                    });

                    //区县
                    if ($("input[data-four-parent-index='" + index + "']").length) {

                        $("input[data-four-parent-index='" + index + "']").each(function () {
                            if (!$(this).is(":disabled") && !$(this).attr("data-is-disabled")) {
                                $(this).prop("checked", checked);
                            }
                        });
                    }
                }
            }
        }
    });

    /**
     * 二级地区（省）例如：山西省、山东省、河北省等
     * 根据当前地区的选中状态对应的改变它的子地区
     */
    $("input[data-second-parent-index]").change(function () {
        var curr = $(this);//当前对象
        var checked = curr.is(":checked");//选中状态
        if (curr.parent().find("div input[type='checkbox']").length) {
            curr.parent().find("div input[type='checkbox']").each(function () {
                if (!$(this).is(":disabled") && !$(this).attr("data-is-disabled")) {
                    $(this).prop("checked", checked);
                }
            });
        }
    });

    /**
     * 三级地区（市区）例如：太原市、运城市等
     * 只要改变了三级地区那它的上一级为不选中状态
     */
    $("input[data-third-parent-index]").change(function () {
        var curr = $(this);//当前对象
        var checked = curr.is(":checked");//选中状态
        if (curr.parent().find("div input[type='checkbox']").length) {
            curr.parent().find("div input[type='checkbox']").each(function () {
                if (!$(this).is(":disabled") && !$(this).attr("data-is-disabled")) {
                    $(this).prop("checked", checked);
                }
            });
        }

        //一个没有选择，父级则不选中
        if (curr.parent().parent().children("div").children("input[type='checkbox']:checked").length == 0) {
            curr.parent().parent().parent().children("input").prop("checked", false);
        }
        //选中一个，父类则选中
        if (checked) curr.parent().parent().parent().children("input").prop("checked", true);
    });

    // 四级地区（区县）选择一个区县，父类（省市就选中）
    $("input[data-four-parent-index]").change(function () {
        var curr = $(this);
        var checked = curr.is(":checked");//选中状态
        var index = curr.attr("data-four-parent-index");//下标
        var province_id = curr.attr("data-province-id");//父级，省id
        var city_id = curr.attr("data-city-id");//父级,市id

        //一个没有选择，父级则不选中
        if (curr.parent().parent().children("span").children("input[type='checkbox']:checked").length == 0) {
            curr.parent().parent().parent().children("input").prop("checked", false);
        }

        //选中一个，父类则选中
        if (checked) {
            $("[data-second-parent-index='" + index + "'][data-province-id='" + province_id + "']").prop("checked", true);
            $("[data-third-parent-index='" + index + "'][data-city-id='" + city_id + "']").prop("checked", true);
        }
    });

    // 获取选中的地区（只显示省），逗号隔开
    function getRegions() {
        var regions_arr = [];
        if ($(".js-regions input[data-second-parent-index]:checked").length) {
            $(".js-regions input[data-second-parent-index]:checked").each(function () {
                regions_arr.push($(this).attr("data-province-name"));
            });
        }

        return regions_arr.toString();//.replace(",","&nbsp;,&nbsp;");
    }

    // 保存选中的省id组
    function setProvinceIdArray() {
        var id_arr = [];
        if ($(".js-regions input[data-second-parent-index]:checked").length) {
            $(".js-regions input[data-second-parent-index]:checked").each(function () {
                if (!$(this).is(":disabled") && !$(this).attr("data-is-disabled")) {
                    id_arr.push($(this).val());
                }
            });
        }
        $("#provinceIds").val(id_arr.toString());
    }

    // 保存选中的市id组
    function setCityIdArray() {
        var id_arr = [];
        if ($(".js-regions input[data-third-parent-index]:checked").length) {
            $(".js-regions input[data-third-parent-index]:checked").each(function () {
                if (!$(this).is(":disabled") && !$(this).attr("data-is-disabled")) {
                    id_arr.push($(this).val());
                }
            });
        }
        $("#cityIds").val(id_arr);// 市id
    }

    // 保存选中的区县id组
    function setDistrictIdArray() {
        var id_arr = [];
        if ($(".js-regions input[data-four-parent-index]:checked").length) {
            $(".js-regions input[data-four-parent-index]:checked").each(function () {
                if (!$(this).is(":disabled") && !$(this).attr("data-is-disabled")) {
                    id_arr.push($(this).val());
                }
            });
        }

        $("#areaIds").val(id_arr);// 区县id
    }
</script>