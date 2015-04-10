$('.search-select').change(function(){
    $('div.hider').css('display','none');
    user_choice = $(this).val();
    if(user_choice == 'obj_id') {
        $('input.search-form-input').attr("placeholder", "Enter product id...");
        $('div.hider').css('display','block');
    }
    else if (user_choice == 'name') {
        $('input.search-form-input').attr("placeholder", "Enter product name...");
        $('div.hider').css('display','block');
    }
    else if (user_choice == 'price') {
        $('input.search-form-input').attr("placeholder", "Enter product price...");
        $('div.hider').css('display','block');
    }
    else if (user_choice == 'description') {
        $('input.search-form-input').attr("placeholder", "Enter product description...");
        $('div.hider').css('display','block');
    }
});