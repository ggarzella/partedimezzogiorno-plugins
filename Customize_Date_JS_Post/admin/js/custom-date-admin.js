(function ($, window, document) {
    'use strict';

    $(document).ready(function() {

        console.log("Script");

        $('#datepicker').datetimepicker(
            {step:5, format:'d-m-Y H:i'}
        );
    });
}
(jQuery, window, document));