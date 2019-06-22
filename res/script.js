$(function () {

    /* Hide blocks */
    $('div.d, div.f').click(function (e) {
        if (e.target !== this) return;
        $(this).toggleClass('hide');
        e.preventDefault();
        e.stopPropagation();
    });

    /* Collapse parameters */
    $('span.params, span.return').click(function (e) {
        if (e.target !== this) return;
        $(this).toggleClass('short');
        e.preventDefault();
        e.stopPropagation();
    });

    /* Hide internal (PHP core) functions */
    $('#internal').change(function () {
        $('div.i').toggle();
    });

    /* Hide functions */
    $('span.name').click(function (e) {
        if (e.target !== this) return;

        var $fn = $(this);
        var name = $(this).text();
        $("span.name:contains('"+name+"')").closest('div.f').toggleClass('hide');

        e.preventDefault();
        e.stopPropagation();
    });

    /* Mark important */
    $('span.time').click(function (e) {
        if (e.target !== this) return;

        $(this).closest('div.f').toggleClass('mark');

        e.preventDefault();
        e.stopPropagation();
    });

    /* Hide internal funcs */
    $('#marked').change(function(){
        $('div.f').toggle();
        $('div.f.mark').show();
    });
});