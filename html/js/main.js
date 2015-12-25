$(function(){
    $('.js-do-archive').on('click', function(ev){
        ev.preventDefault();

        var $parent = $(this).parents('[data-item-id]');
        var item_id = $parent.data('item-id');

        var action = $parent.hasClass('archive') ? "unarchive" : "archive";

        $.post(action, { item_id:item_id }).success(function(){
            $parent.toggleClass('archive');
        }).error(function(xhr){
            alert(xhr.statusText);
        });
    });

    $('.js-do-delete').on('click', function(ev){
        ev.preventDefault();

        if (confirm("Realy?")) {

            var $parent = $(this).parents('[data-item-id]');
            var item_id = $parent.data('item-id');

            $.post('delete', {item_id: item_id}).success(function () {
                $parent.toggleClass('delete');
                $parent.one('transitionend', function () {
                    $parent.show();
                    $parent.slideUp();
                    $parent.queue(function () {
                        $parent.remove();
                        $parent.dequeue();
                    })
                });
            }).error(function(xhr){
                alert(xhr.statusText);
            });
        }
    });
});
