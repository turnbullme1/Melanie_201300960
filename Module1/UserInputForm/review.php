<?php
    // get the data from the form
    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email');
    $comments = filter_input(INPUT_POST, 'comments');


?>
<!DOCTYPE html>
<html>

<head>
    <title>User Input Form</title>
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <main>
        <h1>User Input Form</h1>

        <label>Name:</label>
        <span><?php echo htmlspecialchars($name); ?></span>
        <br>

        <label>Email:</label>
        <span><?php echo $email; ?></span>
        <br>

        <label>Comments</label>
        <span><?php echo $comments; ?></span>
        <br>


    </main>
</body>
</html>