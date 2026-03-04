<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
include 'config.php';

if(isset($_POST['submit'])){
    $title = $conn->real_escape_string($_POST['title']);
    $ingredients = $conn->real_escape_string($_POST['ingredients']);
    $instructions = $conn->real_escape_string($_POST['instructions']);

    // Image upload
    $imageName = null;
    if(!empty($_FILES['image']['name'])){
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetPath = "../uploads/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    }

    $sql = "INSERT INTO recipes (title, ingredients, instructions, image)
            VALUES ('$title', '$ingredients', '$instructions', '$imageName')";

    if($conn->query($sql)){
        header("Location: index.php");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}

$page_title = "Add Recipe";
$extra_css = ['add-edit'];
include 'header.php';
?>

<header>
    <h1>Add Recipe</h1>
    <nav>
        <a href="index.php">Home</a> |
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <h2>Add New Recipe</h2>

    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Title</label><br>
        <input type="text" name="title" required><br><br>

        <label>Ingredients</label><br>
        <textarea name="ingredients" required></textarea><br><br>

        <label>Instructions</label><br>
        <textarea name="instructions" required></textarea><br><br>

        <label>Recipe Image</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <button type="submit" name="submit">Add Recipe</button>
    </form>
</main>

<?php include 'footer.php'; ?>