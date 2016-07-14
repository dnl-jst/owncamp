$(function() {

    $('.add-task-form').submit(function(e) {

        e.preventDefault();

        var that = this;
        var taskSetId = $(that).data('task-set-id');
        var taskName = $(that).find('.task-name').val();

        $.ajax({
            type: 'post',
            url: '/task/add',
            data: {
                task_set_id: taskSetId,
                task_name: taskName
            },
            dataType: 'json',
            success: function(result) {
                if (result !== false) {
                    $('.unfinished-tasks-' + taskSetId).append('<li><input type="checkbox" class="finish-task" data-task-id="' + result.id + '" data-task-set-id="' + taskSetId + '"/> <a href="/task/' + result.id + '">' + taskName + '</a></li>');
                    $(that).find('.task-name').val('');
                }
            }
        });
    });

    $('body').on('click', '.finish-task', function(e) {

        var that = this;
        var taskId = $(that).data('task-id');
        var taskSetId = $(that).data('task-set-id');
        var finished = $(that).is(':checked');

        $(that).parent('li').css({textDecoration: (finished) ? 'line-through' : 'none'});

        $.ajax({
            type: 'post',
            url: '/task/finish',
            data: {
                id: taskId,
                finished: (finished) ? 1 : 0
            },
            dataType: 'json',
            success: function(result) {
                if (result !== false) {

                    var $target = (finished) ? $('.finished-tasks-' + taskSetId) : $('.unfinished-tasks-' + taskSetId);

                    if (!$target.size()) {
                        $(that).parent('li').remove();
                    } else {
                        $(that).parent('li').appendTo($target);
                    }
                }
            }
        });
    });

});
