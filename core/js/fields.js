/**
 * Created with JetBrains PhpStorm.
 * User: kevindees
 * Date: 4/4/13
 * Time: 11:08 AM
 * To change this template use File | Settings | File Templates.
 */
jQuery(document).ready(function($){

    $('.date-picker').datepicker();

    function mapIt(str) {
        return encodeURIComponent(str.toString());
    }

    var typewatch = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        }
    })();

    var mapBase = 'maps.googleapis.com/maps/api/staticmap?',
    mapData = '&zoom=15&size=1200x140&sensor=false&markers=',
    prefix = parent.location.protocol + '//';

    $('.map-image').each(function(){

        var src = $(this).attr('src'),
            input = $(this).parent().parent().find('.googleMap'),
            img = $(this);

        $(input[0]).keyup(function(){

            typewatch(function() {
                var addr = $(input[0]).val();
                var center = mapIt(addr);
                var fullUrl = prefix + mapBase + 'center=' + center + mapData + center;
                //console.log(fullUrl);
                $(img).attr('src', fullUrl);
            }, 1000);

        });


    });
});