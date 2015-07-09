

$(function() {


    var
        mainBody = $('body');


    // ajax form submit binder
    mainBody.on('submit', 'form.ajax-form', function() {

        var
            form            = $(this),
            method          = form.attr('method').toUpperCase(),
            messageBoxes    = form.find('.elem-message'),
            protectionImage = form.find('img.protection-image');

        form.addClass('loading');
        $.ajax({
            type    : (method == 'POST' ? method : 'GET'),
            url     : form.attr('action'),
            data    : form.serialize(),
            cache   : false,
            success : function(response) {
                messageBoxes.hide();
                protectionImage.click();
                if (response.report) {
                    if (response.report.redirection) {
                        window.location.href = response.report.redirection;
                    } else if (response.report.form_messages) {
                        placeFormMessages(
                            messageBoxes,
                            response.report.form_messages
                        );
                    }
                }
                form.removeClass('loading');
            }
        });

        return false;
    });


    // show ajax form messages
    function placeFormMessages(boxes, messages)
    {
        for (var i in messages) {
            boxes.each(function(x) {
                var box = $(this);
                if (box.hasClass('for-' + messages[i].field)) {
                    box.html(messages[i].description).show();
                }
            });
        }
    }


    // colorbox view images
    $('a.colorbox').colorbox({
        rel:'colorbox',
        maxHeight: '95%',
        maxWidth: '95%'
    });


    $('.main-menu-compact-button').on('click', function(){
        $('.main-menu-compact').toggleClass('main-menu-compact_open');
    });


    var hlPostId = document.location.hash.match(/^#topic-post-(\d+)$/);
    if (hlPostId && hlPostId[1]) {
        $('#topic-post-' + hlPostId[1]).addClass('topic-post_current');
    }

});


