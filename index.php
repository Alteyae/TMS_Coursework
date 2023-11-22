    <?php
    require_once('Controller.php');

    $taskManager = new TaskManager();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['addUser'])) {
            $userName = $_POST['userName'];
            $taskManager->addUser($userName);
        } elseif (isset($_POST['deleteUser'])) {
            $userId = $_POST['userId'];
            $taskManager->deleteUser($userId);
        } elseif (isset($_POST['updateUser'])) {
            $userId = $_POST['userId'];
            $newUsername = $_POST['newUsername'];
            $taskManager->updateUser($userId, $newUsername);
        } elseif (isset($_POST['addTask'])) {
            $taskName = $_POST['taskName'];
            $assignUser = $_POST['assignUser']; 
            $taskManager->addTask($taskName, $assignUser);
        } elseif (isset($_POST['markDone'])) {
            $taskId = $_POST['taskId'];
            $taskManager->markTaskAsDone($taskId);
        }
  elseif (isset($_POST['updateTaskUser'])) {
    $taskId = $_POST['taskId'];
    $newUserId = $_POST['newUserId'];
    $taskManager->updateTaskUser($taskId, $newUserId);

    if ($taskId == ""){
        echo "no user select";
    }
}

elseif (isset($_POST['updateTaskUser'], $_POST['taskId'], $_POST['newUserId'])) {
    $taskId = $_POST['taskId'];
    $newUserId = $_POST['newUserId'];
    $taskManager->updateTaskUser($taskId, $newUserId);
}

if (isset($_POST['deleteTask'])) {
        $taskId = $_POST['taskId'];
        $taskManager->deleteTask($taskId);
    }
}
    $users = $taskManager->getUsers();
    $tasks = $taskManager->getTasks();

    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        function confirmDelete(taskId) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('#deleteTaskForm input[name="taskId"]').value = taskId;
                    document.querySelector('#deleteTaskForm button[name="deleteTask"]').click();
                }
            });
        }
        </script>

        <script>
        function confirmDeleteUser(userId) {
            Swal.fire({
                title: "Are you sure?",
                text: "All assigned task to this user will be unassigned. You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // If user clicks "OK," set the userId and submit the form to delete the user
                    document.querySelector('#deleteUserForm input[name="userId"]').value = userId;
                    document.querySelector('#deleteUserForm button[name="deleteUser"]').click();
                }
            });
        }
        </script>



</head>

<body>


<!-- USER -->
<div class="container mt-5">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">ADD USER</button> 

    <form method="post" class="mt-3">
         <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="userName" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="userName" name="userName" placeholder="Ex. John Doe" aria-label="User" aria-describedby="button-addon2">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                   
                <button type="submit" class="btn btn-primary" name="addUser" type="button" id="button-addon2">Add User</button>
            </div>
        </div>
    </div>
</div>
</form>



       
<!-- Display users -->
<h3>Users</h3>
<ul class="list-group">
    <?php foreach ($users as $user) : ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="col">
                <small>ID: <?php echo $user['userId'] ?></small>
                <h5 class="mb-1">User: <?php echo $user['name']; ?></h5>
            </div>
            <form method="post">
                <input type="hidden" name="userId" value="<?php echo $user['userId']; ?>">
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDeleteUser(<?php echo $user['userId']; ?>)">Delete User</button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $user['userId']; ?>">Edit Username</button>
          
                <!--- Edit User Modal -->
                <div class="modal fade" id="editUserModal<?php echo $user['userId']; ?>" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel">Update User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label for="newUsername" class="form-label">Enter New User Name</label>
                                <input type="text" class="form-control" id="newUsername" name="newUsername" placeholder="New Username" required>
                                <input type="hidden" name="userId" value="<?php echo $user['userId']; ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-sm" name="updateUser">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </li>
    <?php endforeach; ?>
</ul>

<form method="post" id="deleteUserForm">
    <input type="hidden" name="userId" value="">
    <button type="submit" class="btn btn-danger btn-sm" name="deleteUser" style="display: none;">Delete User</button>
</form>     
</div>

<!-- END FOR USER -->

<!-- TASK -->
        <div class="container mt-5">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">ADD TASK </button>
      <!-- Modal for Task -->
        <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Add Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <label for="taskName" class="form-label">Task Name</label>
                    <input type="text" class="form-control" id="taskName" name="taskName" placeholder="Task" required>
                    
                    <!-- Dropdown for user assignment -->
                    <div class="mt-3">
                        <label class="form-label">Assign to User</label>
                        <select class="form-select" name="assignUser" required>
                            <option selected disabled>Select User</option>
                            <?php foreach ($users as $user) : ?>
                                <option value="<?php echo $user['userId']; ?>"><?php echo $user['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="addTask">Add Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Display tasks -->
<h3>Tasks</h3>
<ul class="list-group">
    <?php foreach ($tasks as $task) : ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="col">
                <small>ID: <?php echo $task['taskId'] ?></small>
                <h5 class="mb-1">Task: <?php echo $task['taskName']; ?></h5>
                <?php if ($task['userId'] == NULL) : ?>
                    <small>Not yet assigned</small>
                <?php else : ?>
                    <?php $user = $taskManager->getTaskUser($task['userId']); ?>
                    <small>Assigned To: <?php echo $user['name']; ?></small>
                <?php endif; ?>
            </div>


             <form method="post">
                <input type="hidden" name="taskId" value="<?php echo $task['taskId']; ?>">
                <small style="margin-right: 10px;">Status:  </small>
                <button type="submit" class="btn <?php echo $task['is_done'] ? 'btn-success' : 'btn-danger'; ?> btn-sm" name="markDone" style="margin-right: 200px;">
                    <?php echo $task['is_done'] ? 'Done' : 'Pending'; ?>
                </button>
            </form>

            <form method="post">
                <button type="button" class="btn btn-primary btn-sm" style="margin-right: 5px;" data-bs-toggle="modal" data-bs-target="#editTaskDetailsModal_<?php echo $task['taskId']; ?>">Assign to someone</button>
            </form>

            <form method="post" class="mt-3">
                <input type="hidden" name="taskId" value="<?php echo $task['taskId']; ?>">
                <div class="modal fade" id="editTaskDetailsModal_<?php echo $task['taskId']; ?>" tabindex="-1" aria-labelledby="editTaskDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTaskDetailsModal">EDIT TASK DETAILS</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="taskId" value="<?php echo $task['taskId']; ?>">
                           <label for="newUserId" class="form-label">Select Assigned Person</label>
                            <select class="form-select" name="newUserId" id="newUserId" required>
                                <option selected disabled>Select Person</option>
                                <?php foreach ($users as $user) : ?>
                                    <option value="<?php echo $user['userId']; ?>"><?php echo $user['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm" name="updateTaskUser">Update Assigned User</button>
                        </div>
                    </div>
                </div>
            </div>
            </form>

            <form method="post">
                <input type="hidden" name="taskId" value="<?php echo $task['taskId']; ?>">
            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $task['taskId']; ?>)">Delete Task</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>

            <form method="post" id="deleteTaskForm">
                <input type="hidden" name="taskId" value="">
                <button type="submit" class="btn btn-danger btn-sm" name="deleteTask" style="display: none;">Delete Task</button>
            </form>
            <!-- END FORTASK -->
    
    
</body>
</html>
