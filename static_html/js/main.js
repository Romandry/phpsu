

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


    // fancybox view images
    var
        fancyboxItems = $('.fancybox'),
        fancyboxHrefs = [];

    fancyboxItems.each(function() {
        if (this.href) {
            fancyboxHrefs.push(this.href);
        }
    }).on('click', function() {
        $.fancybox(fancyboxHrefs, {
            index       : $(this).index(),
            openEffect  : 'elastic',
            closeEffect : 'elastic',
            openSpeed   : 300,
            closeSpeed  : 300,
            nextEffect  : 'none',
            prevEffect  : 'none'
        });

        return false;
    });


});


