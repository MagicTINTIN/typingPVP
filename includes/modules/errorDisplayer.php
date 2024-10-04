<div id="errorDiv">
    <h4>Errors:</h4>
    <ul>
        <?php
        // Check if there are any errors
        if (!empty($errors)) {
            echo "<script>document.getElementById('errorDiv').style.display = 'block';</script>";
            foreach ($errors as $error) {
                echo "<li>" . htmlspecialchars($error) . "</li>";
            }
        }
        ?>
    </ul>
</div>