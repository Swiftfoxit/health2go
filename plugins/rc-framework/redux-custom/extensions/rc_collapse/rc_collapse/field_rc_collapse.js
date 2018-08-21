/**
 * Created by Quan on 7/22/2017.
 */
jQuery(function ($) {
    $(document).on('click', '.add-collapse', function (e) {
        var id = $(this).data('id');
        var panel = $('#' + id).find('.panel');
        var last_panel = (panel.length > 0) ? panel[panel.length - 1] : '';
        var number = (last_panel !== '') ? (parseInt($(last_panel).data('number')) + 1) : 0;
        var new_collapse = rc_collapse_js.template;
        new_collapse = new_collapse.replaceAll('{{number}}', number);
        new_collapse = new_collapse.replaceAll('{{id}}', rc_collapse_js.field.id + number);
        new_collapse = new_collapse.replaceAll('{{title}}', rc_collapse_js.field.title + number);
        for (var i = 0; i < rc_collapse_js.field.fields.length; i++) {
            new_collapse = new_collapse.replaceAll('{{' + rc_collapse_js.field.fields[i].name + '}}', rc_collapse_js.field.name + '[' + number + ']' + '[' + rc_collapse_js.field.fields[i].name + ']');
        }
        $('#' + id).append(new_collapse);
    });

    $(document).on('click', '.delete-collapse', function () {
        $(this).parents('.panel').remove();
    });
});

String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};
