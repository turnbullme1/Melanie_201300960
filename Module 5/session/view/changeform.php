<main>
	 <h1> Welcome <?php echo $username ?></h1>
        <form action="session_example.php" method="post">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo $username ?>"><br>
				<label>Email:</label>
				<input type="email" name="email" value="<?php echo $email ?>"><br>
                <input type="submit" name = "action" value="Change"><br>
        </form>

</main>