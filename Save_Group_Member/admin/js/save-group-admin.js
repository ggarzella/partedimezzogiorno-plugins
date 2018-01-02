(function ($, window, document) {
    'use strict';

    $(document).ready(function () {

        var form = $('.form-group-member').last();

        var imgFile = form.find('input[name="image-member"]');

        imgFile.on('change', uploadImage);

        $('input[name="saveGroupMember"]').on('click', this, saveGroupMember);

        $('div.modifyGroupMember a.modifyMember').on('click', this, updateGroupMember);

        $('.form-group-member .inputs .btn').on('click', function() { imgFile.click(); });

        function updateGroupMember(event)
        {
            console.log("updateGroupMember");

            event.preventDefault();

            var memberContainer = $(this).parents('.member-container'),
                formIndex = memberContainer.attr('member-index'),
                formCopy = $('.form-group-member').last().clone();

            formCopy.attr('form-index', formIndex);
            formCopy.find('input[name="saveGroupMember"]').after('<a href="#" onclick="return false" class="cancelGroupMember">Annulla</a>');

            var role = $('span[name="role"]', memberContainer).text(),
                name = $('span[name="name"]', memberContainer).text(),
                lastname = $('span[name="lastname"]', memberContainer).text(),
                description = $('p', memberContainer).text(),
                imageId = $('input[name="image-id"]', memberContainer).val();

            $('span[name="role"]', formCopy).text(role);
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



        function saveGroupMember(event)
        {
            console.log("saveGroupMember");

            var form = $(this).parents('.form-group-member'),
                formIndex = form.attr('form-index');

            $.post(
                save_member_meta_box_obj.url,
                {
                    action: 'save_member_group',
                    formIndex: formIndex,
                    postId: $('input[name="post_id"]').val(),
                    role: $('span[name="role"]', form).text(),
                    name: $('input[name="name"]', form).val(),
                    lastname: $('input[name="lastname"]', form).val(),
                    description: $('textarea[name="description"]', form).val(),
                    imageId: $('input[name="image-id"]', form).val()
                },
                function (data) {

                    var data = JSON.parse(data);

                    if (data.result == 'success') {

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

            console.log("cancelGroupMember");

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

            console.log("afterUpdate");

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

            console.log("afterCreation");

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

            $('div.modifyGroupMember a.modifyMember', newMemberContainer).on('click', this, updateGroupMember);

            newMemberContainer.fadeIn("slow");

            //formGroup.attr('form-index', parseInt(formIndex) + 1);

            /*formGroup.fadeOut("fast", function() {
                $(this).remove();
            });*/
        }



        function uploadImage(e) {

            console.log("uploadImage");

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
    });
}
(jQuery, window, document));