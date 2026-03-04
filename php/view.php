<?php
session_start();
include 'config.php';

// Check if recipe ID is provided
if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$recipe_id = (int)$_GET['id'];

// Fetch recipe from DB
$sql = "SELECT * FROM recipes WHERE id = $recipe_id";
$result = $conn->query($sql);

if($result->num_rows != 1){
    echo "Recipe not found!";
    exit;
}

$row = $result->fetch_assoc();

$page_title = htmlspecialchars($row['title']) . " - Recipe Details";
$extra_css = ['view'];
include 'header.php';
?>

<header>
    <h1>Recipe Details</h1>
    <nav>
        <a href="index.php">Home</a> |
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="add.php">Add Recipe</a> |
            <span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a> |
            <a href="signup.php">Signup</a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <h2><?= htmlspecialchars($row['title']) ?></h2>

    <?php if(!empty($row['image'])): ?>
        <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" 
             alt="<?= htmlspecialchars($row['title']) ?>" 
             class="recipe-detail-image">
    <?php endif; ?>

    <h3>Ingredients:</h3>
    <ul>
        <?php 
        $ingredients = explode(",", $row['ingredients']);
        foreach($ingredients as $ing){
            echo "<li>" . htmlspecialchars(trim($ing)) . "</li>";
        }
        ?>
    </ul>

    <h3>Instructions:</h3>
    <p><?= nl2br(htmlspecialchars($row['instructions'])) ?></p>

    <?php if(isset($_SESSION['user_id'])): ?>
        <div class="recipe-actions">
            <a href="edit.php?id=<?= $row['id'] ?>"><button>Edit</button></a>
            <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?');">
                <button class="delete-btn">Delete</button>
            </a>
        </div>
    <?php endif; ?>

    <a href="index.php"><button class="back-btn">Back to Recipes</button></a>
</main>

<?php include 'footer.php'; ?>