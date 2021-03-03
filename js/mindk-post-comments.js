jQuery(document).ready(function($){
    $('#mindk_post_comment').submit(function(){
        var data = $(this).serialize();
        $.ajax({
            type: "POST",
            url: mindk_ajax.url,
            dataType: 'json',
            data: {
                formData: data,
                security: mindk_ajax.nonce,
                action: 'mindk_post_comments'
            },
            success: function(res) {
                $('.comments').prepend(res.body);
                $('#mindk_post_comment').find('input[type=text], input[type=email], textarea').val('');
            },
            error: function(res) {
                let errorMessage = 'Ошибка.';
                if (res.responseJSON.message) {
                    errorMessage += ' ' + res.responseJSON.message;
                }
                alert(errorMessage);
            }
        });
        return false;
    });
});
