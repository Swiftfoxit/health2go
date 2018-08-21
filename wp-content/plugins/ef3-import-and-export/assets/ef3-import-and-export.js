/**
 * Created by FOX on 3/31/2016.
 */
jQuery(function ($) {
    "use strict";

    var page = 1;
    var step = 0;
    var next = true;
    var export_list = [];

    /**
     * select data
     */
    $('.select-import-data').on('click', function () {

        var data_type = $(this).parent().find('.ef3-data-import');

        if(data_type.hasClass('active')){
            data_type.removeClass('active');
        } else {
            data_type.addClass('active');
        }
    });

    /**
     * install demo data.
     */
    $('.install-demo').on('click', function () {

        if (confirm("Install Demo Data!") == false) {
            return;
        }

        var _install = $(this);

        before_install_demo(_install);

        var demo_name = _install.data('demo');

        var import_list = ['start'];

        /* get data types. */
        var inputs = _install.parents('.ef3-action');
        var input_all = inputs.find('.ef3-data-import input.all-data').prop( "checked" );

        inputs.find('.ef3-data-import input').each(function () {

            if($(this).val() != 'all') {

                if (input_all == true) {
                    import_list.push($(this).val());
                } else if ($(this).prop("checked")) {
                    import_list.push($(this).val());
                }
            }
        });

        /* add end action */
        import_list.push('clear');
        import_list.push('finish');

        next = true;
        step = 0;
        page = 1;

        var _timer_import = setInterval(function () {

            /* export demo. */
            if(next == true && import_list[step] != undefined){

                next = false;

                /* process download. */
                if(import_list[step] == 'attachment'){download_start(_install)}

                $.post(ajaxurl, {
                    'action': 'ef3_import',
                    'id': demo_name,
                    'import': import_list[step],
                }, function (response) {
                    console.log(response);
                    process_bar(((step + 1) * 100) / import_list.length, _install);
                    next = true;
                    step++;
                });
            } else if(step + 1 >= import_list.length) {
                clearInterval(_timer_import);
                reload();
            }
        }, 1000);
    });

    /**
     * create demo data
     */
    $('.create-demo').on('click', function () {
        var demo_slug = $('#ef3-demo-slug').val();

        /* demo name not null. */
        if(demo_slug == undefined || demo_slug == ''){
            $('#ef3-demo-slug').trigger('focus');
            return;
        }

        before_create_demo();

        export_list = ['start'];

        /* get list types for export. */
        $('.ef3-export-types input:checked').each(function () {
            export_list.push($(this).val());
        });

        export_list.push('clear');

        next = true;
        step = 0;
        page = 1;

        var _timer_export = setInterval(function () {

            /* export demo. */
            if(next == true && export_list[step] != undefined){

                next = false;

                $.post(ajaxurl, {
                    'action': 'ef3_export',
                    'id': demo_slug,
                    'export': export_list[step],
                    'types' : export_list
                }, function (response) {
                    console.log(response);
                    process_create_demo( 'Exported : ' + export_list[step]);
                    next = true;
                    step++;
                });
            }  else if(step + 1 >= export_list.length) {
                clearInterval(_timer_export);
                process_create_demo('All Done..');
                after_create_demo();
                reload();
            }
        }, 1000);
    });

    /**
     * download demo
     */
    jQuery(".download-demo").on('click',function(){
        var urlAjaxExport = ajaxurl + "?action=ef3_download";
        location.href = urlAjaxExport;
    });

    function before_install_demo(_e) {
        /* show process. */
        _e.parents('.ef3-content').find('.ef3-demo-process').addClass('is-active');

        /* hide all other demo. */
        _e.parents('ul').find('li').addClass('ef3-opacity-0-5');
        _e.parents('li').removeClass('ef3-opacity-0-5');

        _e.parents('ul').find('button').prop('disabled', true);
    }

    function before_create_demo() {
        $('.ef3-export-demo input,button').prop('disabled', true);
    }

    function after_create_demo() {
        $('.ef3-export-demo input,button').prop('disabled', false);
    }

    function process_create_demo(_log) {
        $('.create-demo').html(_log);
    }

    function process_bar(index, _e) {

        index = Math.ceil(index);

        _e.parents('.ef3-content').find('.ef3-process > div').css('width', index + '%');
        _e.parents('.ef3-content').find('.ef3-process > span').html(index + '%');
    }

    function reload() {
        setTimeout(function(){ location.reload(); }, 5000);
    }
    
    function download_start(_install) {
        process_bar(5, _install);
    }
})