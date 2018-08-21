jQuery(function ($) {
    /*
     * ajax download cv
     */

    $(document).on('click', 'button.download-cv', function (e) {
        var account = $(this).data('account');
        $.post(ajaxurl, {action: "jobboard_download_cv", account: account})
            .done(function (data) {
                data = JSON.parse(data);
                if (data.url_download !== undefined) {
                    window.open(data.url_download, '_blank');
                }
            });
        e.preventDefault();
    });
});