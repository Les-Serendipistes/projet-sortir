$(document).ready(function(){
    $(function(){
        $('#search').submit(function(e){
            e.preventDefault();
            let form = $(this);
            let post_url = form.attr('action');
            let post_data = form.serialize();
            $('#js-filter-content', form).html('<img src="../img/site/ajax-loader.gif" />       Please wait...');
            $.ajax({
                type: 'GET',
                url: post_url,
                data: post_data,
                success: function(msg) {
                    $(form).fadeOut(800, function(){
                        form.html(msg).fadeIn().delay(2000);

                    });
                }
            });
            return false;
        });
    });
});
