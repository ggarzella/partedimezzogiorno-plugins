(function ($, window, document) {
    'use strict';

    $(document).ready(function () {
        var form = $('.form-group-member').last();

        //var frmvalidator  = new Validator("post");

        //frmvalidator.addValidation("role", "req", "Please enter your role");

        var imgFile = form.find('input[name="image-member"]');

        imgFile.on('change', uploadImage);

        $('input[name="saveGroupMember"]').on('click', this, saveGroupMember);

        $('div.modifyGroupMember a').on('click', this, updateGroupMember);

        $('.form-group-member .inputs .btn').on('click', function() { imgFile.click(); });

        function updateGroupMember(event) {

            event.preventDefault();

            var memberContainer = $(this).parents('.member-container'),
                formIndex = memberContainer.attr('member-index'),
                formCopy = $('.form-group-member').last().clone();

            formCopy.attr('form-index', formIndex);
            formCopy.find('input[name="saveGroupMember"]').after('<a href="#" onclick="return false" class="cancelGroupMember">Annulla</a>');

            var role = $('label[name="role"]', memberContainer).text(),
                name = $('span[name="name"]', memberContainer).text(),
                lastname = $('span[name="lastname"]', memberContainer).text(),
                description = $('p', memberContainer).text(),
                imageId = $('input[name="image-id"]', memberContainer).val();

            $('input[name="role"]', formCopy).val(role);
            $('input[name="name"]', formCopy).val(name);
            $('input[name="lastname"]', formCopy).val(lastname);
            $('textarea[name="description"]', formCopy).val(description);
            $('input[name="image-id"]', formCopy).val(imageId);

            $('input[name="saveGroupMember"]', formCopy).on('click', this, saveGroupMember);
            $('.cancelGroupMember', formCopy).on("click", this, cancelGroupMember);

            var imgFile = formCopy.find('input[name="image-member"]', memberContainer),
                btn = formCopy.find('.inputs .btn', memberContainer);

            imgFile.on('change', uploadImage);

            btn.on('click', function() { imgFile.click(); });

            formCopy.hide();
            memberContainer.after(formCopy);

            memberContainer.fadeOut("fast");

            formCopy.fadeIn("slow");

            return false;
        }


        function saveGroupMember(event) {
            var form = $(this).parents('.form-group-member'),
                formIndex = form.attr('form-index');

            if (formIndex == undefined) {
                $('.member-container').each(function (index) {
                    if ($(this).attr('member-index') != undefined) {
                        formIndex = $('.member-container').length - 1;
                        return false;
                    } else
                        formIndex = 0;
                });
            }

            $.post(
                save_member_meta_box_obj.url,
                {
                    action: 'save_member_group',
                    formIndex: formIndex,
                    postId: $('input[name="post_id"]').val(),
                    role: $('input[name="role"]', form).val(),
                    name: $('input[name="name"]', form).val(),
                    lastname: $('input[name="lastname"]', form).val(),
                    description: $('textarea[name="description"]', form).val(),
                    imageId: $('input[name="image-id"]', form).val()
                },
                function (data) {

                    var data = JSON.parse(data);

                    if (data.result == 'success') {

                        var myString = "L\'emozione";
                        myString.replace(/\\/g, "");

                        for (var field in data)
                            if ((typeof data[field]) == 'string')
                                data[field] = data[field].replace(/\\/g, "");

                        var memberContainer = $('.member-container[member-index="'+data.index+'"]');

                        if (memberContainer.length == 0)
                            afterCreation(data);
                        else
                            afterUpdate(data);

                    } else if (data.result == 'failure') {
                        console.log('failure');
                    }
                }
            );

            return false;
        }



        function cancelGroupMember(event) {

            event.preventDefault();

            var element = $(event.currentTarget).get(0),
                formIndex = $(element).parents('.form-group-member').attr('form-index');

            $('.member-container[member-index="'+formIndex+'"]').fadeIn("slow");

            $(element).parents('.form-group-member').fadeOut("fast", function() {
                $(this).remove();
            });

            return false;
        }



        function afterUpdate(data) {

            var formIndex = data.index,
                memberContainer = $('.member-container[member-index="'+formIndex+'"]'),
                formGroup = $('.form-group-member[form-index="'+formIndex+'"]');

            $('label[name="role"]', memberContainer).text(data.role);
            $('span[name="name"]', memberContainer).text(data.name);
            $('span[name="lastname"]', memberContainer).text(data.lastname);
            $('p', memberContainer).text(data.description);
            $('input[name="image-id"]', memberContainer).val(data.imageId);
            $('.img-container img', memberContainer).attr('src', data.imageUrl);

            $('.cancelGroupMember', formGroup).trigger("click", this, cancelGroupMember);
        }



        function afterCreation(data) {

            var formIndex = data.index,
                newMemberContainer = $('.member-container').last().clone(),
                formGroup = $('.form-group-member').last();

            newMemberContainer.attr('member-index', formIndex);

            newMemberContainer.hide();
            formGroup.before(newMemberContainer);

            $('label[name="role"]', newMemberContainer).text(data.role);
            $('span[name="name"]', newMemberContainer).text(data.name);
            $('span[name="lastname"]', newMemberContainer).text(data.lastname);
            $('p[name="description"]', newMemberContainer).text(data.description);
            $('input[name="image-id"]', newMemberContainer).val(data.imageId);
            $('.img-container img', newMemberContainer).attr('src', data.imageUrl);

            $('input[name="role"]', formGroup).val("");
            $('input[name="name"]', formGroup).val("");
            $('input[name="lastname"]', formGroup).val("");
            $('textarea[name="description"]', formGroup).val("");
            $('input[name="image-id"]', formGroup).val("");

            $('div.modifyGroupMember a', newMemberContainer).on('click', this, updateGroupMember);

            newMemberContainer.fadeIn("slow");

            //formGroup.attr('form-index', parseInt(formIndex) + 1);

            /*formGroup.fadeOut("fast", function() {
                $(this).remove();
            });*/
        }



        function uploadImage(e) {

            var loading = $('.loading');

            loading.show();

            $('input[name="saveGroupMember"]').prop('disabled', true);

            e.preventDefault();

            var element = e.target;

            var formData = new FormData(),
                form = $(element).parents('.form-group-member');

            formData.append('action', 'upload-attachment');
            formData.append('async-upload', $(element)[0].files[0]);
            formData.append('name', $(this)[0].files[0].name);
            formData.append('_wpnonce', save_member_meta_box_obj.nonce);

            var imgId = form.find('input[name="image-id"]');

            $.ajax({
                url: save_member_meta_box_obj.upload_url,
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                type: 'POST'
            }).done(function(resp) {
                $('input[name="saveGroupMember"]').prop('disabled', false);
                imgId.val(resp.data.id);
            }).error(function() {
                //imgNotice.html('Fail to upload image. Please try again.');
                imgId.val('');
            }).always(function() {
                loading.hide();
            });
        }

        /*var inputs = document.querySelectorAll('.image-file');
        Array.prototype.forEach.call( inputs, function( input )
        {
            var label	 = input.nextElementSibling,
                labelVal = label.innerHTML;

            input.addEventListener( 'change', function( e )
            {
                var fileName = '';
                if( this.files && this.files.length > 1 )
                    fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
                else
                    fileName = e.target.value.split( '\\' ).pop();

                if( fileName )
                    label.querySelector( 'span' ).innerHTML = fileName;
                else
                    label.innerHTML = labelVal;
            });
        });*/
    });
}
(jQuery, window, document));