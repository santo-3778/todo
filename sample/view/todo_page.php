<?php 
include_once('../includes/header.php');
session_start();
require_once "../database/db.php";
$userid=$_SESSION["user_id"];

date_default_timezone_set('Asia/Kolkata');

$project_id = $_GET['project_id']; 

$sql_project = "SELECT project_title FROM project WHERE project_id='$project_id'";
$result_project = $conn->query($sql_project);
$project_name = "";
if ($result_project->num_rows > 0) {
  $row_project = $result_project->fetch_assoc();
  $project_name = $row_project['project_title'];
}

if(isset($_POST['create_todo'])){
    $description = $_POST['description'];
    $status = 'pending';
    $createddate = date('Y-m-d H:i:s');
    $sql = "INSERT INTO todos (project_id, description, status, created_date, updated_date) VALUES ('$project_id', '$description', '$status', '$createddate', '$createddate')";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if(isset($_POST['update_todo'])){
    $todo_id = $_POST['todo_id'];
    $description = $_POST['description'];
    $status = isset($_POST['status']) ? 'complete' : 'pending'; 
    $updateddate = date('Y-m-d H:i:s');
    $sql = "UPDATE todos SET description='$description', status='$status', updated_date='$updateddate' WHERE id='$todo_id'";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

if(isset($_POST['delete_todo'])){
    $todo_id = $_POST['todo_id'];
    $sql = "DELETE FROM todos WHERE id = '$todo_id'";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql_pending_count = "SELECT COUNT(*) AS pen_count FROM todos WHERE project_id='$project_id' AND status='pending'";
$result_pending_count = $conn->query($sql_pending_count);
$row_pending_count = $result_pending_count->fetch_assoc();
$pen_count = $row_pending_count['pen_count'];

$sql_completed_count = "SELECT COUNT(*) AS comp_count FROM todos WHERE project_id='$project_id' AND status='complete'";
$result_completed_count = $conn->query($sql_completed_count);
$row_completed_count = $result_completed_count->fetch_assoc();
$comp_count = $row_completed_count['comp_count'];

$sql_pending = "SELECT * FROM todos WHERE project_id='$project_id' AND status='pending'";
$result_pending = $conn->query($sql_pending);

$sql_completed = "SELECT * FROM todos WHERE project_id='$project_id' AND status='complete'";
$result_completed = $conn->query($sql_completed);

?>

<div class="container mt-5">
  <h2><?php echo $project_name; ?></h2>
  
  <div class="text-right mb-3">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTodoModal">Create Todo</button>
    <a href="./gist_export.php?project_id=<?php echo $project_id; ?>" class="btn btn-primary ml-2">Export Summary as Gist</a>
    <a href="./dashboard.php" class="btn btn-secondary ml-2">Back</a>

  </div>
  <h5 style="color: blue;">Summary: <?php echo $comp_count; ?>/<?php echo $pen_count+$comp_count; ?> todos completed</h5>

  <h3>Pending Todos</h3>
  <?php if ($result_pending->num_rows > 0): ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Description</th>
          <th>Status</th>
          <th>Created On</th>
          <th>Updated On</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $count = 1; while ($row = $result_pending->fetch_assoc()): ?>
          <tr>
            <td><?php echo $count++; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo date('d-m-Y H:i:s', strtotime($row['created_date'])); ?></td>
            <td><?php echo date('d-m-Y H:i:s', strtotime($row['updated_date'])); ?></td>
            <td>
              <form action="" method="post">
                <input type="hidden" name="todo_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="description" value="<?php echo $row['description']; ?>">
                <input type="hidden" name="status" value="<?php echo $row['status']; ?>">
                <button type="button" name="edit_todo" class="btn btn-success" data-toggle="modal" data-target="#editTodoModal" onclick="document.getElementById('description_edit').value = '<?php echo $row['description']; ?>'; document.getElementById('status_edit').checked = '<?php echo ($row['status'] == 'complete' ? 'checked' : ''); ?>'; document.getElementById('todo_id_edit').value = '<?php echo $row['id']; ?>';">Edit</button>
                <button type="submit" name="delete_todo" class="btn btn-danger">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No pending todos found.</p>
  <?php endif; ?>

  <h3>Completed Todos </h3>
  <?php if ($result_completed->num_rows > 0): ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Description</th>
          <th>Status</th>
          <th>Created On</th>
          <th>Updated On</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $count = 1; while ($row = $result_completed->fetch_assoc()): ?>
          <tr>
            <td><?php echo $count++; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo date('d-m-Y H:i:s', strtotime($row['created_date'])); ?></td>
            <td><?php echo date('d-m-Y H:i:s', strtotime($row['updated_date'])); ?></td>
            <td>
              <form action="" method="post">
                <input type="hidden" name="todo_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="description" value="<?php echo $row['description']; ?>">
                <input type="hidden" name="status" value="<?php echo $row['status']; ?>">
                <button type="button" name="edit_todo" class="btn btn-success" data-toggle="modal" data-target="#editTodoModal" onclick="document.getElementById('description_edit').value = '<?php echo $row['description']; ?>'; document.getElementById('status_edit').checked = '<?php echo ($row['status'] == 'complete' ? 'checked' : ''); ?>'; document.getElementById('todo_id_edit').value = '<?php echo $row['id']; ?>';">Edit</button>
                <button type="submit" name="delete_todo" class="btn btn-danger">Delete</button>

              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No completed todos found.</p>
  <?php endif; ?>
</div>

<div class="modal fade" id="createTodoModal" tabindex="-1" role="dialog" aria-labelledby="createTodoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createTodoModalLabel">Create Todo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="">
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" id="description" name="description">
            </div>
            <button type="submit" name="create_todo" class="btn btn-primary">Create</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editTodoModal" tabindex="-1" role="dialog" aria-labelledby="editTodoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTodoModalLabel">Edit Todo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="">
            <input type="hidden" id="todo_id_edit" name="todo_id">
            <div class="form-group">
                <label for="description_edit">Description:</label>
                <input type="text" class="form-control" id="description_edit" name="description">
            </div>
            <div class="form-group">
                <label for="status_edit">Status:</label>
                <input type="checkbox" id="status_edit" name="status" value="complete"> Complete
            </div>
            <button type="submit" name="update_todo" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>  
<?php include_once('../includes/footer.php')?>
