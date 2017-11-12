(function ($, window, document) {
    'use strict';

    $(document).ready(function() {

        if ($('.other-events').length == 0)
            return;

        var page = 2;
        var loading = false;
        var otherEvents;

        $('body').on('click', '.other-events', function(){
            if(!loading) {
                loading = true;
                var data = {
                    action: 'be_ajax_load_more',
                    page: page,
                    query: beloadmore.query,
                };
                $.post(beloadmore.url, data, function(res) {
                    if (res.success) {
                        otherEvents = $('.other-events').clone();
                        $('.other-events').after(res.data.content);
                        $('.other-events').remove();
                        if (res.data.continue)
                            $('.home-container').last().after($(otherEvents));
                        page = page + 1;
                        loading = false;
                    } else {
                        // console.log(res);
                    }
                }).fail(function(xhr, textStatus, e) {
                    // console.log(xhr.responseText);
                });
            }
        });
    });
}
(jQuery, window, document));