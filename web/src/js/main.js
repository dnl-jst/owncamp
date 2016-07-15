$(function() {

    tinymce.init({
        selector: '.tinymce',
        plugins: 'autolink link lists',
        relative_urls: false,
        language_url : '/bower_components/tinymce-i18n/langs/de.js',
        language: 'de',
        removed_menuitems: 'newdocument'
    });

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

    $('.edit-task-description').click(function(e) {

        var $that = $(this);
        var $taskDescription = $(this).parent('.task-description');

        if ($that.data('state') === 'save') {

            tinymce.triggerSave();

            var newTaskDescription = $taskDescription.find('.input-task-description').val();

            $that.find('.fa').removeClass('fa-save').addClass('fa-spinner fa-spin');

            $.ajax({
                type: 'post',
                url: '/task/edit',
                data: {
                    id: $that.data('task-id'),
                    keys: ['description'],
                    description: newTaskDescription
                },
                dataType: 'json',
                success: function(result) {
                    if (result !== false) {
                        $taskDescription.find('.output').html(newTaskDescription);
                        $taskDescription.find('.edit').addClass('hidden');
                        $taskDescription.find('.output').removeClass('hidden');
                        $that.find('.fa').removeClass('fa-spinner fa-spin').addClass('fa-edit');
                        $that.data('state', 'edit');
                    }
                }
            });

        } else {
            $taskDescription.find('.output').addClass('hidden');
            $taskDescription.find('.edit').removeClass('hidden');
            $that.find('.fa').removeClass('fa-edit').addClass('fa-save');
            $that.data('state', 'save');
        }
    });

    $('.edit-task-assigned-to').click(function(e) {

        var $that = $(this);
        var $taskAssignedTo = $(this).parent('.task-assigned-to');

        if ($that.data('state') === 'save') {

            var newAssignedTo = $taskAssignedTo.find('.input-task-assigned-to').val();

            $that.find('.fa').removeClass('fa-save').addClass('fa-spinner fa-spin');

            $.ajax({
                type: 'post',
                url: '/task/edit',
                data: {
                    id: $that.data('task-id'),
                    keys: ['assignedTo'],
                    assignedTo: newAssignedTo
                },
                dataType: 'json',
                success: function(result) {
                    if (result !== false) {
                        $taskAssignedTo.find('.output').html($taskAssignedTo.find('.input-task-assigned-to option:selected').html());
                        $taskAssignedTo.find('.edit').addClass('hidden');
                        $taskAssignedTo.find('.output').removeClass('hidden');
                        $that.find('.fa').removeClass('fa-spinner fa-spin').addClass('fa-edit');
                        $that.data('state', 'edit');
                    }
                }
            });

        } else {
            $taskAssignedTo.find('.output').addClass('hidden');
            $taskAssignedTo.find('.edit').removeClass('hidden');
            $that.find('.fa').removeClass('fa-edit').addClass('fa-save');
            $that.data('state', 'save');
        }

    });

});
