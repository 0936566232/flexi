jQuery(function ($) {
    $('#news-filter-form').submit(function () {
        var filter = $('#news-filter-form');
        $.ajax({
            url: filter.attr('action'),
            data: filter.serialize(),
            type: filter.attr('method'),
            success: function (data) {
                $('.news__items').html(data);
            }
        });
        return false;
    });

    $('#reset-filter-all').click(function () {
        $('#news-filter-form input[name="news_category[]"]').prop('checked', false);
        $('#news-filter-form').submit();
        return false;
    });



});
