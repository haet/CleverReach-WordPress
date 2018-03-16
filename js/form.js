var haet_cleverreach = haet_cleverreach || {};

jQuery(document).ready(function($) {
    $('.haet-cleverreach-form').submit(function(){
        var $form = $(this);
        var submission = {};
        $form.find('.cleverreach-loader').slideDown(400);
        $form.find('[name]').each(function(){
            var $field = $(this);
            submission[$field.attr('name')] = $field.val();
        })

        var data = {
            'action': 'cleverreach_submit',
            'submission': submission
        };

        $.post(haet_cr_ajax.ajax_url, data, function (response) {
            $form.replaceWith(response);
        });

        return false;
    });
});