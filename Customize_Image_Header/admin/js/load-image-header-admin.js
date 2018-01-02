(function ($, window, document) {
    'use strict';

    $(document).ready(function() {

        var data = {
            action: 'be_ajax_load_image_header',
            page: page,
            query: beloadmore.query,
            category: category
        };

        $.get(beloadmore.url, data, function(res)
        {
            /*if (res.success)
            {
                var otherPost = (page * res.data.post_per_page < res.data.total);

                if (page > 1)
                {
                    $('.other-events').after(res.data.content);
                    var otherEvents = $('.other-events').clone();
                    $('.other-events').remove();

                    if (otherPost)
                        $('.box-container').last().after($(otherEvents));
                }
                else
                {
                    $('.async-container').append(res.data.content);
                    if (otherPost)
                        $('.box-container').last().after("<h2 class='other-events'><span>Leggi altri eventi</span></h2>");
                }
            } else {
                // console.log(res);
            }*/
        }).fail(function(xhr, textStatus, e) {
            // console.log(xhr.responseText);
        });
    });
});