$(function () {
    $("body").on("click","#submit_ajax_replace",function(){
        check_form('#ajaxFormReplace','replace');
    });

    $("body").on("click","#submit_ajax_popup",function(){
        check_form('#ajaxFormPopup','popup');
    });

    $("body").on("click","#submit_ajax",function(){
        check_form('#ajaxForm','general');
    });

    //删除提示
    $(".delete-prompt").click(function (){
        if (confirm("你确定删除吗？")) { 
            $.post($(this).data("href"),{ids:$(this).data("ids")},function(result){
                window.location.reload();
            });
        } 
    });

    /*
     * 表单验证并异步提交
     * require          必填验证
     * regex            正则验证
     * compare_less     小于比较
     * compare_greater  大于比较
     * compare_between  区间比较 --待建立
     */
    function check_form(action_element,action_type)
    {
        console.log(action_element);
        var action_url      = $(action_element).attr('action');
        var form_data       = $(action_element).serializeArray();
        var element         = $(action_element).attr('data-element');
        var request_data    = new FormData();

        if(form_data){
            $.each(form_data, function() {
                request_data.append(this.name,this.value);
            });
        }

        $.ajax({
            url:action_url,
            type:"post",
            dataType: "json",
            processData: false,
            contentType: false,
            data:request_data,
            async:true,
            success: function(res){

                if(res['code'] == 'ok'){

                    if(action_type == 'replace'){
                        $(element).html(res.content);
                    }
                    else if(action_type == 'popup')
                    {
                        alert(res.message);
                        $('.close').trigger("click");
                        window.location.href = res.url;
                    }
                    else if(action_type == 'general')
                    {
                        if(res.message){

                            var error = '';

                            $.each(res.message,function (k,v) {
                                error += v+'\n';
                            });

                            alert(error);
                            window.location.href = res.url;
                        }
                    }

                    if(res.type == 'edit'){
                        window.localStorage.removeItem("draft_edit"+res.id);
                    }else{
                        window.localStorage.removeItem("draft_add");
                    }

                    return false;
                }else{
                    if(action_type == 'general')
                    {
                        if(res.message){

                            var error = '';

                            $.each(res.message,function (k,v) {
                                error += v+'\n';
                            });

                            alert(error);
                            return false;
                        }
                    }
                }

            }
        });
    }

    /*
     * 企业搜索
     */
    $('.search_enterprise').on('input propertychange',function()
    {

        var action_url  = $(this).attr('action_url');
        var keywords    = $(this).val();
        var obj         = $(this);

        if(!keywords || keywords.length < 2)
            return false;

        $.ajax({
            url:action_url,
            type:"get",
            dataType: "json",
            data:{keywords:keywords},
            async:true,
            success: function(data){

                if(data['code'] == 'ok')
                {
                    var html = '';

                    if(data['list'].length >= 1)
                    {
                        $(data['list']).each(function(k,v){
                            if(k%2 == 0){
                                html += '<div class="ms-res-item" data-id="'+v.id+'" data-province="'+v.province+'" ' +
                                    'data-city="'+v.city+'" data-district="'+v.district+'" data-fullname="'+v.full_name+'"' +
                                    'data-position="'+v.position+'" data-mobile="'+v.mobile+'" data-qq="'+v.qq+'"' +
                                    'data-webchat="'+v.webchat+'" data-email="'+v.email+'">'+v.ename+'</div>';
                            }else{
                                html += '<div class="ms-res-item ms-res-odd" data-id="'+v.id+'" data-province="'+v.province+'" ' +
                                    'data-city="'+v.city+'" data-district="'+v.district+'" data-fullname="'+v.full_name+'"' +
                                    'data-position="'+v.position+'" data-mobile="'+v.mobile+'" data-qq="'+v.qq+'"' +
                                    'data-webchat="'+v.webchat+'" data-email="'+v.email+'">'+v.ename+'</div>';
                            }
                        });
                    }else{
                        html += '<div class="ms-res-item" data-id="0">暂无搜索结果</div>';

                        var html_area = '<select class="province" name="province" data-value="">' +
                            '</select>' +
                            '<select class="city" name="city" data-value="">' +
                            '</select>' +
                            '<select class="district" name="district" data-value="">' +
                            '</select>';

                        $('.area_block #element_id').html(html_area);

                        $('#element_id').cxSelect({
                            url: "/static/dist/js/cityData.js",
                            selects: ['province', 'city', 'district'],
                        });
                    }

                    $('.enterprise_list').html(html);
                    $('.enterprise_list').show();

                }

                return false;
            }
        });

    });

    /*
     * 企业名称选中
     */
    $('body').delegate('.enterprise_list .ms-res-item', 'click', function() {

        var data_id = $(this).attr('data-id');
     

        if(data_id > 0)
        {
            $('input[name="eid"]').val( data_id );
            $('input[name="ename"]').val( $(this).html() );
            $('.enterprise_list').hide();

            var html = '<select class="province" name="province" data-value="'+$(this).attr('data-province')+'">' +
                '</select>' +
                '<select class="city" name="city" data-value="'+$(this).attr('data-city')+'">' +
                '</select>' +
                '<select class="district" name="district" data-value="'+$(this).attr('data-district')+'">' +
                '</select>';

            $('.area_block #area_list').html(html);
            $('#area_list').cxSelect({
                url: "/static/dist/js/cityData.js",
                selects: ['province', 'city', 'district'],
            });

            if($(this).attr('data-fullname')){
                $('.full_name_second').val($(this).attr('data-fullname'));
            }else{
                $('.full_name_second').val('');
            }

            if($(this).attr('data-position')){
                $('.position_second').val($(this).attr('data-position'));
            }else{
                $('.position_second').val('');
            }

            if($(this).attr('data-mobile')){
                $('.mobile_second').val($(this).attr('data-mobile'));
            }else{
                $('.mobile_second').val('');
            }

            if($(this).attr('data-qq')){
                $('.qq_second').val($(this).attr('data-qq'));
            }else{
                $('.qq_second').val('');
            }

            if($(this).attr('data-webchat')){
                $('.webchat_second').val($(this).attr('data-webchat'));
            }else{
                $('.webchat_second').val();
            }

            if($(this).attr('data-email')){
                $('.email_second').val($(this).attr('data-email'));
            }else{
                $('.email_second').val();
            }

        }

    });

    //监听账号名称改变
    $('#account_name').on('input propertychange',function()
    {

        var keywords    = $(this).val();
        var action_url  = $(this).attr('action_url');
        var obj         = $(this);

        if(!keywords || keywords.length < 2)
            return false;

        $.ajax({
            url:action_url,
            type:"get",
            dataType: "json",
            data:{keywords:keywords},
            async:true,
            success: function(data){

                var color = data.code == 'ok' ? 'green' : 'red';
                var html = "<p class="+color+" >"+data.message+"</p>";

                obj.parent().find('.message').html(html);

                if(data.code != 'ok'){
                    $('.submit_btn').attr("disabled","true");
                }else{
                    $('.submit_btn').removeAttr("disabled");
                }

                return false;
            }
        });

    });

    //监听账号ID改变
    $('#account_id').on('blur',function ()
    {

        var account_id = $(this).val();
        var action_url = $(this).attr('action_url');
        var obj        = $(this);

        if(account_id.length < 2)
            return false;

        $.ajax({
            url:action_url,
            type:"get",
            dataType: "json",
            data:{account_id:account_id},
            async:true,
            success: function(data){

                var color = data.code == 'ok' ? 'green' : 'red';
                var html = "<p class="+color+" >"+data.message+"</p>";

                if(data.code == 'ok'){
                    obj.parent().find('.message').html('');
                    $('#upload_list img').attr('src',data.path);
                    $("input[name='avatar']").val(data.path);
                }


                if(data.code != 'ok')
                    obj.parent().find('.message').html(html);

                return false;
            }
        });

    });

    /*删除记录*/
    $("body").on("click",".remove",function(){

        if( !confirm('是否确认删除该记录') )
            return false;

        var action_url      = $(this).attr('action_url');
        var page            = $('.page-block li.active span').html();
        var form_data       = $('.search_filter').find('form').serializeArray();
        var request_data    = new FormData();
        var type            = $(this).attr('data-type');

        request_data.append('page',page);
        request_data.append('type',type);

        if(form_data){
            $.each(form_data, function() {
                request_data.append(this.name,this.value);
            });
        }

        $.ajax({
            url:action_url,
            type:"post",
            dataType: "json",
            processData: false,
            contentType: false,
            data:request_data,
            async:true,
            success: function(data){
                if(data['code'] == 'ok'){
                    $('#list-table').html(data['content']);
                }else{
                    alert(data['message']);
                }
                return false;
            }
        });
    });

    /*
     * 输入框获得焦点时储存时间节点之前数据
     */
    $("#account_tag").on('focus',function(){
        tag_old = $(this).val();
    });

    /*
     * 账号特色标签搜索
     */
    $('#account_tag').on('input propertychange',function()
    {

        var action_url  = $(this).attr('action_url');
        var keywords    = $(this).val();

        if(keywords.length < 1)
            tag_old = '';

        if(!keywords || keywords.length < 1)
            return false;

        if(tag_old)
            keywords = keywords.replace(tag_old,'');

        $.ajax({
            url:action_url,
            type:"get",
            dataType: "json",
            data:{keywords:keywords},
            async:true,
            success: function(data){

                if(data['code'] == 'ok')
                {
                    var html = '';

                    if(data['list'].length >= 1)
                    {
                        $(data['list']).each(function(k,v){
                            if(k%2 == 0){
                                html += '<label class="tab" data-id="1" data-key="'+tag_old+'">'+v.tag+'</label>';
                            }else{
                                html += '<label class="tab" data-id="1" data-key="'+tag_old+'">'+v.tag+'</label>';
                            }
                        });
                    }

                    $('.tag_list').html(html);
                    $('.tag_list').show();

                }

                return false;
            }
        });

    });

    /*
     * 账号特色标签选中
     */
    $('body').delegate('.tag_list .tab', 'click', function() {

        let data_id     = $(this).attr('data-id');

        if(data_id > 0){

            let tag_val;

            tag_val = tag_old.length > 0 ? tag_old+'，'+$(this).html() : $(this).html();

            tag_old = tag_val;
            console.log(tag_old);
            $('input[name="tag"]').val( tag_val );

            $(this).hide();
        }

    });

    /*
     * 跳转至指定分页请求
     */
    $("body").on("click",".page_jump",function(){
        var action_url      = $(this).parent().parent().attr('action_url');
        var page            = $("input[name='jump_page']").val();
        var form_data       = $('.search_filter').find('form').serializeArray();
        var request_data    = new FormData();

        if(page < 1){
            alert('请填写页码值');
            return false;
        }

        request_data.append('page',page);

        if(form_data){
            $.each(form_data, function() {
                request_data.append(this.name,this.value);
            });
        }

        $.ajax({
            url:action_url,
            type:"post",
            dataType: "json",
            processData: false,
            contentType: false,
            data:request_data,
            async:true,
            success: function(data){
                if(data['code'] == 'ok'){
                    $('#list-table').html(data['content']);
                    window.localStorage.setItem("page",page);
                }else{
                    alert(data['message']);
                }
                return false;
            }
        });
    });
});