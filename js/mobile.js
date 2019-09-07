/**
 * Created by yangkai on 2019/9/5.
 */
(function (doc, win){

    var startPosition;
    var endPosition;


    doc.addEventListener('touchstart',function (e,a){
        var touch = e.touches[0];
        startPosition = {
            x: touch.pageX,
            y: touch.pageY
        };
    });
    // document.addEventListener('touchmove',touch, false);
    doc.addEventListener('touchend',function (e,a){
        var touch = e.changedTouches[0];
        endPosition = {
            x: touch.pageX,
            y: touch.pageY
        };

        var triggerYU = win.innerHeight/10*2;
        var triggerXU = win.innerWidth/10*4;

        var moveAbsY = Math.abs(startPosition.y-endPosition.y);
        var moveY = startPosition.y-endPosition.y;

        var moveAbsX = Math.abs(startPosition.x-endPosition.x);
        var moveX = startPosition.x-endPosition.x;

        if(0 > moveY && triggerYU < moveAbsY && ( doc.body.clientHeightt <= win.innerHeight || (startPosition.y < win.innerHeight && endPosition.y < win.innerHeight) ) )
        {
            yangkaiMoveY('up',e);
        }

        if(0 < moveY && triggerYU < moveAbsY && ( doc.body.clientHeight <= win.innerHeight || ((doc.body.clientHeight - win.innerHeight) < startPosition.y && (doc.body.clientHeight - win.innerHeight) < endPosition.y) ) )
        {
            yangkaiMoveY('down',e);
        }


        if(0 > moveX && triggerXU < moveAbsX)
        {
            yangkaiMoveX('left',e);
        }

        if(0 < moveX && triggerXU < moveAbsX)
        {
            yangkaiMoveX('right',e);
        }

        //alert(triggerXU+"="+moveAbsX);
        //alert(triggerU + "<br/>移动Y" + moveY );
    });

    doc.addEventListener('touchstart',function (e,a){
        var touch = e.touches[0];
        startPosition = {
            x: touch.pageX,
            y: touch.pageY
        };
    });


})(document, window);
