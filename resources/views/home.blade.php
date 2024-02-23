<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<div class="d-flex justify-content-between m-5">
    <h2 class="">Hello {{auth()->user()->name}}!</h2>
    <a href="logout"><button class="btn btn-danger">Logout</button></a>
</div>
<div class="container">
    <h2>TODO List</h2>

    <!-- Alert Message -->
    <div class="alert alert-success d-none" id="successMessage" role="alert">
        Task has been created.
    </div>

    <form id="taskForm">
        <div class="form-group">
            <label for="taskName">Task Name:</label>
            <input type="text" class="form-control" id="taskName" name="taskName">
        </div>
        <button type="button" class="btn btn-primary" onclick="addTask()">Add Task</button>
    </form>

    <br>

    <table class="table">
        <thead>
            <tr>
                <th>SI No</th>
                <th>Task Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="taskList">
            <!-- Tasks will be dynamically added here -->
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    let siNo = 1;

    function addTask() {
        const taskName = document.getElementById('taskName').value;

        if (taskName.trim() !== '') {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${siNo}</td>
                <td>${taskName}</td>
                <td><input type="checkbox" class="status" onchange="updateStatus(this)"> Pending</td>
                <td><button class="btn btn-success" onclick="updateTask(this)">Update</button></td>
            `;
            document.getElementById('taskList').appendChild(newRow);
            siNo++;
            document.getElementById('taskForm').reset(); // Reset form after adding task

            // Show success message
            $('#successMessage').removeClass('d-none').addClass('show');
            setTimeout(function() {
                $('#successMessage').removeClass('show').addClass('d-none');
            }, 3000); // Hide after 3 seconds
        } else {
            alert('Task name cannot be empty!');
        }
    }

    function updateStatus(checkbox) {
        const statusText = checkbox.nextSibling;
        if (checkbox.checked) {
            statusText.nodeValue = ' Completed';
        } else {
            statusText.nodeValue = ' Pending';
        }
    }

    function updateTask(button) {
        // Add your update logic here


        const row = button.parentNode.parentNode;
        const id = row.children[0].textContent;
        const taskName = row.children[1].textContent;
        const status = row.children[2].children[0].checked ? 'completed' : 'pending';

        $.ajax({
            url: '{{ route("task.store") }}', // Update with correct route
            type: 'POST',
            data: {
                id: id,
                user_id: '{{auth()->user()->id}}',
                task_name: taskName,
                status: status,
                _token: '{{csrf_token()}}' // For CSRF protection in Laravel
            },
            success: function(response) {
                // Handle success
                console.log(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle error
                console.log(textStatus, errorThrown);
            }
        });
    }
</script>


