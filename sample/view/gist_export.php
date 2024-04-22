<?php include_once('../includes/header.php')?>

<div class="container">
    <div class="box" style="padding: 20px; text-align: center;">
        <?php
        session_start();
        require_once "../database/db.php";

        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }

        // GitHub username and personal access token
        $username = 'santo-3778';
        $token = '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';

        // Get project details from the URL parameter
        $project_id = $_GET['project_id'];

        // Fetch project details from the database
        $sql = "SELECT * FROM project WHERE project_id = '$project_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $projectTitle = $row['project_title'];

            // Fetch todos for the project
            $sql = "SELECT * FROM todos WHERE project_id = '$project_id'";
            $result = $conn->query($sql);

            // Initialize markdown content
            $markdownContent = "";

            $totalTodos = $result->num_rows;
            $completedTodos = 0;
            $pendingTodos = "";
            $completedTodosList = "";

            // Generate markdown content for todos
            while ($row = $result->fetch_assoc()) {
                if ($row['status'] == 'complete') {
                    $completedTodosList .= "- [x] " . $row['description'] . "\n";
                    $completedTodos++;
                } else {
                    $pendingTodos .= "- [ ] " . $row['description'] . "\n";
                }
            }

            // Generate summary
            $summary = "## Summary";
            $summary .= "- $completedTodos / $totalTodos completed.\n\n";

            // File name for the gist
            $fileName = $projectTitle . '.md';

            // Add project name to markdown content
            $markdownContent .= "# $projectTitle\n\n";

            // Add summary to markdown content
            $markdownContent .= $summary;



            // Add pending todos to markdown content
            if (!empty($pendingTodos)) {
                $markdownContent .= "### Pending Todos\n\n";
                $markdownContent .= $pendingTodos . "\n";
            }

            // Add completed todos to markdown content
            if (!empty($completedTodosList)) {
                $markdownContent .= "### Completed Todos\n\n";
                $markdownContent .= $completedTodosList . "\n";
            }

            // Create Gist
            $url = 'https://api.github.com/gists';
            $data = [
                'description' => $projectTitle,
                'public' => false,
                'files' => [
                    $fileName => [
                        'content' => $markdownContent
                    ]
                ]
            ];

            $options = [
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => [
                    "Authorization: token $token",
                    'User-Agent: PHP'
                ],
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_RETURNTRANSFER => true,
            ];

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($result === FALSE) {
                echo "<div class='mb-3'>Failed to create gist. Error: " . curl_error($ch) . "</div>";
            } else {
                if ($httpCode == 201) {
                    $response = json_decode($result, true);
                    if (isset($response['html_url'])) {
                        $gistUrl = $response['html_url'];
                        echo "<div class='mb-3'>Gist created successfully. Gist URL: <a href='$gistUrl' target='_blank'>$gistUrl</a></div>";
                        echo "<div><button class='btn btn-primary mt-3 mr-2' onclick=\"window.location.href='$gistUrl'\">View Gist</button><button class='btn btn-primary mt-3' onclick=\"window.location.href='./dashboard.php'\">Back</button></div>";
                    } else {
                        echo "<div class='mb-3'>Failed to create gist. Response: " . print_r($response, true) . "</div>";
                    }
                } else {
                    echo "<div class='mb-3'>Failed to create gist. HTTP Error Code: $httpCode</div>";
                }
            }

        } else {
            echo "<div class='mb-3'>Project not found.</div>";
        }

        $conn->close();
        ?>
    </div>
</div> 
<?php include_once('../includes/footer.php')?>
