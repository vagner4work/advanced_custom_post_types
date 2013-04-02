jQuery(document).ready(function($) {

    function set_uploader(button, url, container, type) {
        $(button).click(function() {

            var send_attachment_bkp = wp.media.editor.send.attachment;

            wp.media.editor.send.attachment = function(props, attachment) {

                var placeholder = $(container).find('.image-placeholder img');
                if(placeholder.length > 0) {
                    $(placeholder).attr('src', attachment.url);
                } else {
                    $(container).find('.image-placeholder').append($('<img>').attr('src', attachment.url));
                }

                $(url).val(attachment.url);

                wp.media.editor.send.attachment = send_attachment_bkp;
            }

            wp.media.editor.open();

            return false;
        });
    }

    // place set_uploader functions below, button then field
    $('.control-group').each(function(index, el) {
        var button = $(el).find('.upload-button'), uploadUrl = $(el).find('.upload-url'), type = null;

        if($(uploadUrl).hasClass('file')) {
            type = 'file';
        } else if($(uploadUrl).hasClass('image')) {
            type = 'image';
        }

        //console.log(type);

        if(button){ set_uploader(button[0], uploadUrl[0], el, type);}

        $(el).on('click', '.image-placeholder .remove-image', function(){
            $(this).parent().parent().find('.upload-url').attr('value', '');
            $(this).parent().remove();
            $(el).append('<div class="image-placeholder"><div class="remove-image"></div></div>');
        });
    });

});