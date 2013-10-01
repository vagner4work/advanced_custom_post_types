/**
 * Created with JetBrains PhpStorm.
 * User: kevindees
 * Date: 4/4/13
 * Time: 11:08 AM
 * To change this template use File | Settings | File Templates.
 */
jQuery(document).ready(function($){

    $('.date-picker').each(function(){
        $(this).datepicker();
    });

    $('.color-picker').each(function(){
        pal = $(this).attr('id') + '_color_palette';
        def = $(this).attr('id') + '_defaultColor';
        myobj = { palettes: window[pal], defaultColor: window[def]  }
        // console.log(myobj);
        $(this).wpColorPicker(myobj);
    });

    function mapIt(str) {
        if(str == '') {
            str = '';
        } else {
            str = encodeURIComponent(str.toString());
        }
        return str;
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
                $(input[0]).siblings('.googleMap-encoded').attr('value', center);
            }, 1000);

        });
    });

    // Tabs
    $('.acpt-tabs li').each(function(){

        $(this).click(function(e){
            $(this).addClass('active').siblings().removeClass('active');
            var section = $(this).find('a').attr('href');
            $(section).addClass('active').siblings().removeClass('active');
            editorHeight();

            e.preventDefault();
        });
    });

    $('.contextual-help-tabs a').click(function(){
        editorHeight()
    });

    // fork from theme options framework
    function editorHeight() {
        // Editor Height (needs improvement)
        $('.wp-editor-wrap').each(function() {
            var editor_iframe = $(this).find('iframe');
            if ( editor_iframe.height() < 30 ) {
                editor_iframe.css({'height':'auto'});
            }
        });
    }

    // text repeater field
    $('.text-repeater-field').each(function(index) {
      console.log(index);
      var
        field = $(this).data('name'),
        list = $(this).next(),
        el = $('<div class="add-text-repeater button" style="float: right">Add</div>');

      el.click(function(e){
        list.append('<li><input type="text" name="'+field+'"/><b>Remove</b></li>');
      });

      $(this)
        .parent()
        .parent()
        .prepend(el);
    });

    $('.text-repeater-field + .list').sortable();
    $('.text-repeater-field + .list').on('click', 'b', function(){
      $(this).parent().remove();
    });

});