<?php

class Database
{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $name = 'tms';
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
    
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function establishConnection()
    {
        return $this->conn;
    }
}

class TaskManager
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

//user functions


  public function addUser($userName)
    {
        $conn = $this->db->establishConnection();
        $query = $conn->prepare("INSERT INTO users (name) VALUES (?)");
        $query->bind_param("s", $userName);
        $query->execute();
        $query->close();
    }

 public function deleteUser($userId)
{
    // Set userId to NULL for associated tasks
    $this->updateTasksUserIdToNull($userId);

    $conn = $this->db->establishConnection();
    $query = $conn->prepare("DELETE FROM users WHERE userId = ?");
    $query->bind_param("i", $userId);
    $query->execute();
    $query->close();
}

private function updateTasksUserIdToNull($userId)
{
    $conn = $this->db->establishConnection();
    $query = $conn->prepare("UPDATE tasks SET userId = NULL WHERE userId = ?");
    $query->bind_param("i", $userId);
    $query->execute();
    $query->close();
}

    public function updateUser($userId, $newUsername)
    {
        $conn = $this->db->establishConnection();
        $query = $conn->prepare("UPDATE users SET name = ? WHERE userId = ?");
        $query->bind_param("si", $newUsername, $userId);
        $query->execute();
        $query->close();
    }

public function getUsers()
    {
        $conn = $this->db->establishConnection();
        $userQuery = $conn->query("SELECT * FROM users");

        $users = [];
        while ($row = $userQuery->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }




    //task functions



public function addTask($taskName, $userId = null)
{
    $conn = $this->db->establishConnection();
    
    // Check if userId is provided for assignment
    if ($userId !== null) {
        $query = $conn->prepare("INSERT INTO tasks (taskName, userId) VALUES (?, ?)");
        $query->bind_param("si", $taskName, $userId);
    } else {
        $query = $conn->prepare("INSERT INTO tasks (taskName) VALUES (?)");
        $query->bind_param("s", $taskName);
    }

    $query->execute();
    $query->close();
}

    public function markTaskAsDone($taskId)
    {
        $conn = $this->db->establishConnection();
        $query = $conn->prepare("UPDATE tasks SET is_done = IF(is_done = 0, 1, 0) WHERE taskId = ?");
        $query->bind_param("i", $taskId);
        $query->execute();
        $query->close();
    }

    public function getTasks()
    {
        $conn = $this->db->establishConnection();
        $taskQuery = $conn->query("select * from tasks");
        // $tasks = $taskQuery->fetch_all(MYSQLI_ASSOC);

        $tasks = [];
        while ($row = $taskQuery->fetch_assoc()) {
            $tasks[] = $row;
        }

        return $tasks;
    }

public function getTaskUser($userId) {
    $conn = $this->db->establishConnection();
    $query = $conn->prepare("SELECT * FROM users WHERE userId = ?");
    $query->bind_param("i", $userId);
    $query->execute();

    $result = $query->get_result();
    $user = $result->fetch_assoc();

    $query->close();

    return $user;
}
    


    
//update task user

 public function updateTaskUser($taskId, $newUserId)
    {
        $conn = $this->db->establishConnection();

        $query = $conn->prepare("UPDATE tasks SET userId = ? WHERE taskId = ?");
        $query->bind_param("ii", $newUserId, $taskId);
        $query->execute();
        $query->close();
    }


// delete task user
public function deleteTask($taskId)
{
    $conn = $this->db->establishConnection();
    $query = $conn->prepare("DELETE FROM tasks WHERE taskId = ?");
    $query->bind_param("i", $taskId);
    $query->execute();
    $query->close();
}
}
?>
