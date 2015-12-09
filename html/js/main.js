$(function(){
    $('.js-toggle-archive').on('click', function(ev){
        ev.preventDefault();

        var $parent = $(this).parents('[data-item-id]');
        var item_id = $parent.data('item-id');

        var action = $parent.hasClass('archive') ? "/unarchive" : "/archive";

        $.post(action, { item_id:item_id }).success(function(){
            $parent.toggleClass('archive');
        });
    });
});
