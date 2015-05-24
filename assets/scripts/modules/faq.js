jQuery(document).ready(function(){
    //filter links
    $('#faq-nav-filters a').click(function(e){
        e.preventDefault();
        var selected_filter = $(this).data('filter');

        $('#faq-nav-filters a').removeClass('active');
        $(this).addClass('active');

        

        if(selected_filter != 'all'){

            $('.faq-content-wrapper .faq').hide();
            
            $('.faq-content-wrapper .faq_content').each(function(){
                if($.inArray(selected_filter, $(this).data('filter').split(',')) > -1){
                    $(this).closest('.faq').show();
                }
            });
        }else{
            $('.faq-content-wrapper .faq').show();
        }
        

        return;
    });
});