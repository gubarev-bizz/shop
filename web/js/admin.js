(function ($) {
    $(document).ready(function () {
        $('form').on('sonata.add_element', function(){
            initTinyMCE();
        });
    });
})(jQuery);