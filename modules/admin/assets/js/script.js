jQuery(function () {
    $(document).on('click', '.option-form', function (e) {
        e.preventDefault();
        $('#modal-option-update').modal('show')
            .find('#modalContent')
            .load(
                $(this).attr('href')
            );
    });

    $('#btn-option-create').on('click', function () {
        $('#modal-option-create').modal('show')
            .find('#modalCreateContent')
            .load($(this).attr('value'));
    });

    //AJAX удаление значения аттрибута
    $(document).on('click', '.option-delete', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        $.post(href, function () {
            var pjax_id = 'pjax-widget';
            $.pjax.reload('#' + pjax_id);
        });
        return false;
    });

    //Транслитерация
    $('.transIt').liTranslit({
        elAlias: $('.transTo'),
        reg: "' '='-','«'='','»'=''"
    });

    //AJAX удаление
    $(document).on('click', '.grid-action', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        $.post(href, function () {
            $.pjax.reload('#pjax-widget');
        });
    });

    $(document).on('click', '#add-product-photo', function (e) {
        e.preventDefault();
        var update = $('#additional-photos');
        //var model = $(this).data('model') ? $(this).data('model') : null;
        $.ajax({
            type: 'get',
            url: '/product/backend/generate-image-input',
            data: {
                model: $(this).data('model')
            },
            cache: false,
            success: function (data) {
                update.append(data);
            }
        });
    });
});