<style type="text/css">
    .amap-marker-label {
        border: 0;
        background-color: transparent;
    }

    .info {
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border-radius: .25rem;
        position: fixed;
        top: 2rem;
        background-color: white;
        width: auto;
        min-width: 22rem;
        border-width: 0;
        left: 1.8rem;
        box-shadow: 0 2px 6px 0 rgba(114, 124, 245, .5);
    }
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
    <h4 class="modal-title">高德地图位置(经纬度)</h4>
</div>

<div class="modal-body" style="height: 500px">
    <div class="row">
        <div class="search">
            <div class="input-group">
                <input type="text" id="place" style="width: 300px" class="form-control" placeholder="输入地点"/>
            </div>
        </div>
        <div id="container"></div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary confirm" data-dismiss="modal">确定</button>
</div>

<script type="text/javascript">
    $(function () {
        var boxId = "<?= $boxId; ?>";
        var as, x, y, address, map, lat, lng, geocoder;
        var init = function () {
            AMapUI.loadUI(['misc/PositionPicker', 'misc/PoiPicker'], function (PositionPicker, PoiPicker) {
                //加载PositionPicker，loadUI的路径参数为模块名中 'ui/' 之后的部分
                map = new AMap.Map('container', {
                    zoom: parseInt('<?= $zoom ?>'),
                    center: [<?= $lng ?>, <?= $lat ?>], //初始地图中心点
                });
                geocoder = new AMap.Geocoder({
                    radius: 1000 //范围，默认：500
                });
                var positionPicker = new PositionPicker({
                    mode: 'dragMarker',//设定为拖拽地图模式，可选'dragMap'、'dragMarker'，默认为'dragMap'
                    map: map//依赖地图对象
                });
                //输入提示
                var autoOptions = {
                    input: "place"
                };

                var auto = new AMap.Autocomplete(autoOptions);

                //构造地点查询类
                var placeSearch = new AMap.PlaceSearch({
                    map: map
                });

                //注册监听，当选中某条记录时会触发
                AMap.event.addListener(auto, "select", function (e) {
                    placeSearch.setCity(e.poi.adcode);
                    placeSearch.search(e.poi.name);  //关键字查询查询
                });
                AMap.event.addListener(map, 'click', function (e) {
                    map.panTo([e.lnglat.lng, e.lnglat.lat]);
                    positionPicker.start(e.lnglat);
                    geocoder.getAddress(e.lnglat.lng + ',' + e.lnglat.lat, function (status, result) {
                        if (status === 'complete' && result.regeocode) {
                            var address = result.regeocode.formattedAddress;
                            var label = '<div class="info">地址:' + address + '<br>经度:' + e.lnglat.lng + '<br>纬度:' + e.lnglat.lat + '</div>';
                            positionPicker.marker.setLabel({
                                content: label //显示内容
                            });
                        } else {
                            alert(JSON.stringify(result))
                        }
                    });
                });

                //加载工具条
                var tool = new AMap.ToolBar();
                map.addControl(tool);

                var poiPicker = new PoiPicker({
                    input: 'place',
                    placeSearchOptions: {
                        map: map,
                        pageSize: 6 //关联搜索分页
                    }
                });
                poiPicker.on('poiPicked', function (poiResult) {
                    poiPicker.hideSearchResults();
                    lat = poiResult.item.location.lat
                    lng = poiResult.item.location.lng
                    $('.poi .nearpoi').text(poiResult.item.name)
                    $('.address .info').text(poiResult.item.address)
                    $('#address').val(poiResult.item.address)
                    map.panTo([lng, lat]);
                });

                positionPicker.on('success', function (positionResult) {
                    as = positionResult.position;
                    address = positionResult.address;
                    x = as.lat;
                    y = as.lng;
                });
                positionPicker.on('fail', function (positionResult) {
                    address = '';
                    console.log(positionResult);
                });
                positionPicker.start();
            });
        };

        //点击确定后执行回调赋值
        var close = function (data) {
            $(document).trigger('select-map-' + boxId, [boxId, data]);
            console.log(data);
        };

        //点击搜索按钮
        $(document).on('click', '.confirm', function () {
            var zoom = map.getZoom();
            var data = {lat: x, lng: y, zoom: zoom, address: address};
            close(data);
        });
        init();
    });
</script>