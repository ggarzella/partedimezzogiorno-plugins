(function ($, window, document) {
    'use strict';

    $(document).ready(function() {

        if ($('.async-container').length == 0)
            return;

        var category = $('.async-container').attr('class').replace(/((.?)+)category-/, ''),
            page = 1,
            loading = false;

        load(page, category);
        page = page + 1;

        $('body').on('click', '.other-events', function()
        {
            if (!loading)
            {
                loading = true;

                load(page, category);

                page = page + 1;
                loading = false;
            }
        });
    });
}
(jQuery, window, document));

function load(page, category) {

    var data = {
        action: 'be_ajax_load_more',
        page: page,
        query: beloadmore.query,
        category: category
    };

    console.log(category);
    console.log(data);

    $.post(beloadmore.url, data, function(res)
    {
        if (res.success)
        {
            var otherPost = (page * res.data.post_per_page < res.data.total);

            if (page > 1)
            {
                $('.other-events').after(res.data.content);
                var otherEvents = $('.other-events').clone();
                $('.other-events').remove();

                if (otherPost)
                    $('.home-container').last().after($(otherEvents));
            }
            else
            {
                $('.async-container').append(res.data.content);
                if (otherPost)
                    $('.home-container').last().after("<h2 class='other-events'><span>Leggi altri eventi</span></h2>");
            }
        } else {
            // console.log(res);
        }
    }).fail(function(xhr, textStatus, e) {
        // console.log(xhr.responseText);
    });
}