$(document).on("click","#checkbox-all",function (){
    var subset = $(this).data("check-target");

    if( $(this).is(':checked') ){
        $(subset+":not(:checked)").each(function (){
            $(this).prop("checked",true);
            isChecked(this,"#ids");
        });
        
    }else{
        $(subset+":checked").each(function (){
            $(this).prop("checked",false);
            isChecked(this,"#ids");
        });
    }
    
});

$(document).on("click",".list-check-box",function (){
    isChecked(this,"#ids");
});

function isChecked(dom,vessel)
{
    if( $(dom).is(':checked') ){
        addChecked($(dom).val(),vessel);
    }else{
        delectChecked($(dom).val(),vessel);
    }
}

function addChecked(val,dom)
{
    var arr = $(dom).html().split(",");
    arr.push(val);
    arr = arr.filter(s => $.trim(s).length > 0);
    $(dom).html(arr.join(","));
}

function delectChecked(val,dom)
{
    var arr = $(dom).html().split(",");
    delete arr[$.inArray(val,arr)];
    arr = arr.filter(s => $.trim(s).length > 0);
    $(dom).html(arr.join(","));
}