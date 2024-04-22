<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>#Hatio</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
<?php
$current_page = basename($_SERVER['REQUEST_URI'], '.php');
$project_id = isset($_GET['project_id']) ? $_GET['project_id'] : '';
?>
<nav class="navbar navbar-light bg-light justify-content-between">
  <a class="navbar-brand" style="color: purple;">#hatio</a>
  <?php if ($current_page == 'dashboard'): ?>
    <a href="../view/logout.php" class="btn btn-secondary my-2 my-sm-0">Logout</a>
  <?php elseif ($current_page == 'todo_page' && !empty($project_id)): ?>
    <a href="../view/dashboard.php" class="btn btn-outline-primary my-2 my-sm-0">Back</a>
  <?php elseif ($current_page == 'todo_page' && empty($project_id)): ?>
    <a href="../view/dashboard.php" class="btn btn-outline-primary my-2 my-sm-0">Back</a>

  <?php endif; ?>
</nav>





