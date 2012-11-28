jQuery(document).ready(function() {
    function set_uploader(button, field, el) {
        // make sure both button and field are in the DOM
        if(jQuery(button) && jQuery(field)) {
            // when button is clicked show thick box
            jQuery(button).click(function() {
                tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
                jQuery('#media-items .savesend .button').addClass('button-primary');
                // when the thick box is opened set send to editor button
                var img = jQuery(el).find('.image-placeholder') || null;
                set_send(field, img);
                return false;
            });
        }
    }

    function set_send(field,img) {
        // store send_to_event so at end of function normal editor works
        window.original_send_to_editor = window.send_to_editor;

        // override function so you can have multiple uploaders pre page
        window.send_to_editor = function(html) {
            imgurl = jQuery('img',html).attr('src');
            jQuery(field).val(imgurl);

            if(img) { jQuery(img).append('<img class="upload-img" id="#acpt_img_slider_url" src="' + imgurl + '" />'); }

            tb_remove();
            // Set normal uploader for editor
            window.send_to_editor = window.original_send_to_editor;
        };
    }

    // place set_uploader functions below, button then field
    jQuery('.control-group').each(function(index, el) {
        var button = jQuery(el).find('.upload-button')[0];
        if(button){ set_uploader(button, jQuery(el).find('.upload-url')[0], el);}

        jQuery(el).on('click', '.image-placeholder .remove-image', function(){
            jQuery(this).parent().parent().find('.upload-url').attr('value', '');
            jQuery(this).parent().remove();
            jQuery(el).append('<div class="image-placeholder"><div class="remove-image"></div></div>');
        });
    });

});