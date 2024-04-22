<?php 
include_once('../includes/header.php');
session_start();
require_once "../database/db.php";
$userid=$_SESSION["user_id"];

if(isset($_POST['create_project'])){
    $project_title = $_POST['project_title'];
    $createddate = date('Y-m-d H:i:s');
    $sql = "INSERT INTO project (user_id, project_title, createddate) VALUES ('$userid', '$project_title', '$createddate')";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if(isset($_POST['edit_project'])){
    $project_id = $_POST['project_id'];
    $project_title = $_POST['project_title'];
    $sql = "UPDATE project SET project_title='$project_title' WHERE project_id='$project_id'";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

if(isset($_POST['approve'])){
    $project_id = $_POST['project_id'];
    $sql = "SELECT * FROM project WHERE project_id='$project_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $project_title = $row['project_title'];
    echo "<script>
            $(document).ready(function(){
                $('#editProjectModal').modal('show');
                $('#project_id_edit').val('$project_id');
                $('#project_title_edit').val('$project_title');
            });
          </script>";
}

if(isset($_POST['complete'])){
    $project_id = $_POST['project_id'];
    $sql = "DELETE FROM project WHERE project_id = '$project_id'";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$sql = "select * from project where user_id = $userid";
$result = $conn->query($sql);
?>
<div class="container mt-5">
  <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createProjectModal">Create Project</button>
  <?php if ($result->num_rows > 0): ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th>Id</th>
          <th>Title</th>
          <th>Created On</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $count = 1; while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo $count++; ?></td>
            <td><a href="todo_page.php?project_id=<?php echo $row['project_id']; ?>"><?php echo $row['project_title']; ?></a></td>
            <td><?php echo $row['createddate']; ?></td>
            <td>
              <form action="" method="post">
                <input type="hidden" name="project_id" value="<?php echo $row['project_id']; ?>">
                <button type="submit" name="approve" class="btn btn-success mr-2">Edit</button>
                <button type="submit" name="complete" class="btn btn-danger">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No projects found.</p>
  <?php endif; ?>
</div>

<div class="modal fade" id="createProjectModal" tabindex="-1" role="dialog" aria-labelledby="createProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createProjectModalLabel">Create Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="">
            <div class="form-group">
                <label for="project_title">Project Title:</label>
                <input type="text" class="form-control" id="project_title" name="project_title">
            </div>
            <button type="submit" name="create_project" class="btn btn-primary">Create</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editProjectModal" tabindex="-1" role="dialog" aria-labelledby="editProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="">
            <input type="hidden" id="project_id_edit" name="project_id">
            <div class="form-group">
                <label for="project_title_edit">Project Title:</label>
                <input type="text" class="form-control" id="project_title_edit" name="project_title">
            </div>
            <button type="submit" name="edit_project" class="btn btn-primary">Update</button>
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
