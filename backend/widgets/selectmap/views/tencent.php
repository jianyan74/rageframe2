<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
    <h4 class="modal-title">腾讯地图位置(经纬度)</h4>
</div>

<div class="modal-body" style="height: 500px">
    <div class="row">
        <div class="search">
            <div class="input-group">
                <input type="text" id="place" name="q" class="form-control" placeholder="输入地点"/>
                <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
        </span>
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
        var map, marker, geocoder, infoWin, searchService, address = null;
        var init = function () {
            var center = new qq.maps.LatLng(<?= $lat ?>, <?= $lng ?>);
            map = new qq.maps.Map(document.getElementById('container'), {
                center: center,
                zoom: parseInt("<?= $zoom ?>")
            });
            //初始化marker
            initmarker(center);

            //实例化信息窗口
            infoWin = new qq.maps.InfoWindow({
                map: map
            });
            geocoder = new qq.maps.Geocoder({
                complete: function (result) {
                    infoWin.open();
                    address = result.detail.addressComponents.province +
                        result.detail.addressComponents.city +
                        result.detail.addressComponents.district;
                    if (result.detail.addressComponents.streetNumber == '') {
                        address += result.detail.addressComponents.street;
                    } else {
                        address += result.detail.addressComponents.streetNumber;
                    }
                    infoWin.setContent(address);
                    infoWin.setPosition(result.detail.location);
                }
            });
            //显示当前marker的位置信息窗口
            geocoder.getAddress(center);

            var latlngBounds = new qq.maps.LatLngBounds();
            //查询poi类信息
            searchService = new qq.maps.SearchService({
                complete: function (results) {
                    var pois = results.detail.pois;
                    for (var i = 0, l = pois.length; i < l; i++) {
                        var poi = pois[i];
                        latlngBounds.extend(poi.latLng);
                        initmarker(poi.latLng);
                        //显示当前marker的位置信息窗口
                        geocoder.getAddress(poi.latLng);
                    }
                    map.fitBounds(latlngBounds);
                }
            });
            //实例化自动完成
            var ap = new qq.maps.place.Autocomplete(document.getElementById('place'));
            //添加监听事件
            qq.maps.event.addListener(ap, "confirm", function (res) {
                searchKeyword();
            });
            qq.maps.event.addListener(
                map,
                'click',
                function (event) {
                    try {
                        infoWin.setContent('<div style="text-align:center;white-space:nowrap;margin:10px;">加载中</div>');
                        var latLng = event.latLng,
                            lat = latLng.getLat().toFixed(5),
                            lng = latLng.getLng().toFixed(5);
                        var location = new qq.maps.LatLng(lat, lng);
                        //调用获取位置方法
                        geocoder.getAddress(location);
                        infoWin.setPosition(location);
                        marker.setPosition(location);
                    } catch (e) {
                        console.log(e);
                    }
                }
            );
        };

        //实例化marker和监听拖拽结束事件
        var initmarker = function (latLng) {
            marker = new qq.maps.Marker({
                map: map,
                position: latLng,
                draggable: true,
                title: '拖动图标选择位置'
            });
            //监听拖拽结束
            qq.maps.event.addListener(marker, 'dragend', function (event) {
                var latLng = event.latLng,
                    lat = latLng.getLat().toFixed(5),
                    lng = latLng.getLng().toFixed(5);
                var location = new qq.maps.LatLng(lat, lng);
                //调用获取位置方法
                geocoder.getAddress(location);
            });
        };
        var close = function (data) {
            $(document).trigger('select-map-' + boxId, [boxId, data]);
            console.log(data);
        };

        //执行搜索方法
        var searchKeyword = function () {
            searchService.clear();//先清除
            marker.setMap(null);
            infoWin.close();
            var keyword = $("#place").val();
            searchService.setLocation("<?= $defaultSearchAddress ?>");//设置默认检索范围（默认为全国），类型可以是坐标或指定的城市名称。
            searchService.setPageIndex(0);//设置检索的特定页数。
            searchService.setPageCapacity(1);//设置每页返回的结果数量。
            searchService.search(keyword);//开始查询
        };

        //点击确定后执行回调赋值
        $(document).on('click', '.confirm', function () {
            var as = marker.getPosition();
            var x = as.getLat().toFixed(5);
            var y = as.getLng().toFixed(5);
            var zoom = map.getZoom();
            var data = {lat: x, lng: y, zoom: zoom, address: address};
            close(data);
        });

        //点击搜索按钮
        $(document).on('click', '#search-btn', function () {
            if ($("#place").val() == '')
                return;
            searchKeyword();
        });

        init();
    });
</script>