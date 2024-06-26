<!DOCTYPE html>
<html>
<head>
    <title>PHP - Simple To Do List App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>PHP - Simple To Do List App</h1>
            <div class="input-group mb-3">
                <input type="text" id="task-title" class="form-control" placeholder="Enter Task">
                <div class="input-group-append">
                    <button id="add-task" class="btn btn-primary">Add Task</button>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="task-list">
                    <!-- Tasks will be appended here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#add-task').on('click', function() {
            var title = $('#task-title').val();
            if (title) {
                $.ajax({
                    url: '/tasks',
                    method: 'POST',
                    data: {
                        title: title,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#task-title').val('');
                        loadTasks();
                    }
                });
            }
        });

        $(document).on('click', '.delete-task', function() {
            if (confirm('Are you sure to delete this task?')) {
                var id = $(this).data('id');
                $.ajax({
                    url: '/tasks/' + id,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        loadTasks();
                    }
                });
            }
        });

        $(document).on('click', '.toggle-completed', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '/tasks/' + id,
                method: 'PATCH',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    loadTasks();
                }
            });
        });

        function loadTasks() {
            $.ajax({
                url: '/tasks',
                method: 'GET',
                success: function(response) {
                    var taskList = $('#task-list');
                    taskList.empty();
                    response.forEach(function(task, index) {
                        var statusText = task.completed ? 'Done' : 'Pending';
                        var listItem = `<tr>
                            <td>${index + 1}</td>
                            <td>${task.title}</td>
                            <td>${statusText}</td>
                            <td>
                                <button class="btn btn-success btn-sm toggle-completed" data-id="${task.id}">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-danger btn-sm delete-task" data-id="${task.id}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>`;
                        taskList.append(listItem);
                    });
                }
            });
        }

        loadTasks();
    });
</script>
</body>
</html>
