<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
include 'config.php';

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// Fetch existing recipe
$result = $conn->query("SELECT * FROM recipes WHERE id = $id");
if($result->num_rows == 0){
    header("Location: index.php");
    exit;
}
$recipe = $result->fetch_assoc();

// Handle update
if(isset($_POST['update'])){
    $title = $conn->real_escape_string($_POST['title']);
    $ingredients = $conn->real_escape_string($_POST['ingredients']);
    $instructions = $conn->real_escape_string($_POST['instructions']);

    $imageName = $recipe['image'];

    // If new image uploaded
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $allowed = ['image/jpeg','image/png','image/jpg'];
        if(in_array($_FILES['image']['type'], $allowed)){
            $imageName = time() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                "../uploads/" . $imageName
            );
        }
    }

    $sql = "UPDATE recipes 
            SET title='$title',
                ingredients='$ingredients',
                instructions='$instructions',
                image='$imageName'
            WHERE id=$id";

    if($conn->query($sql)){
        header("Location: index.php");
        exit;
    } else {
        $error = "Error updating recipe!";
    }
}

$page_title = "Edit Recipe";
$extra_css = ['add-edit'];
include 'header.php';
?>

<header>
    <h1>Edit Recipe</h1>
    <nav>
        <a href="index.php">Home</a> |
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Recipe Title</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($recipe['title']) ?>" required><br><br>

        <label>Ingredients</label><br>
        <textarea name="ingredients" rows="4" required><?= htmlspecialchars($recipe['ingredients']) ?></textarea><br><br>

        <label>Instructions</label><br>
        <textarea name="instructions" rows="4" required><?= htmlspecialchars($recipe['instructions']) ?></textarea><br><br>

        <label>Current Image</label><br>
        <?php if($recipe['image']): ?>
            <img src="../uploads/<?= htmlspecialchars($recipe['image']) ?>"
                 class="preview-image"><br><br>
        <?php else: ?>
            <p>No image uploaded</p>
        <?php endif; ?>

        <label>Change Image</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <button type="submit" name="update">Update Recipe</button>
    </form>
</main>

<?php include 'footer.php'; ?>