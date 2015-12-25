$(function(){
    $('.js-do-archive').on('click', function(ev){
        ev.preventDefault();

        var $parent = $(this).parents('[data-item-id]');
        var item_id = $parent.data('item-id');

        var action = $parent.hasClass('archive') ? "unarchive" : "archive";

        $parent.toggleClass('archive');

        $.post(action, { item_id:item_id }).success(function(){
            //
        }).error(function(xhr){
            $parent.toggleClass('archive');
            alert(xhr.statusText);
        });
    });

    $('.js-do-delete').on('click', function(ev){
        ev.preventDefault();

        if (confirm("Realy?")) {

            var $parent = $(this).parents('[data-item-id]');
            var item_id = $parent.data('item-id');

            var posted = $.Deferred();

            $.post('delete', {item_id: item_id}).success(function () {
                posted.resolve();
                console.log('posted.resolve');
            }).error(function(xhr){
                posted.reject();
                alert(xhr.statusText);
            });

            var animated = $.Deferred();

            $parent.toggleClass('delete');
            $parent.one('transitionend', function () {
                animated.resolve();
                console.log('animated.resolve');
            });

            $.when(posted, animated)
                .done(function(){
                    $parent.show();
                    $parent.slideUp();
                    $parent.queue(function () {
                        $parent.remove();
                        $parent.dequeue();
                    })
                })
                .fail(function(){
                    $parent.toggleClass('delete');
                })
            ;
        }
    });
});
