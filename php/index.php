<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
include 'config.php';

// Fetch recipes from DB
$sql = "SELECT * FROM recipes ORDER BY created_at DESC";
$result = $conn->query($sql);

$page_title = "Recipe App - Home";
$extra_css = ['home', 'favorites', 'search'];
include 'header.php';
?>

<header>
    <h1>My Recipe App</h1>
    <nav>
        <div class="nav-left">
            <a href="index.php">Home</a> |
            <a href="add.php">Add Recipe</a>
        </div>
        <div class="user-info">
            <span class="welcome-text">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>
</header>

<main>
    <h2>Recipes</h2>

    <!-- Search bar -->
    <input type="text" id="searchInput" placeholder="Search recipes...">

   <ul id="recipeList">
    <?php if($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <li>
                
                <!-- Recipe Image -->
                <?php if(!empty($row['image'])): ?>
                    <img 
                        src="../uploads/<?= htmlspecialchars($row['image']) ?>" 
                        alt="Recipe Image"
                    >
                <?php else: ?>
                    <div class="no-image">No Image</div>
                <?php endif; ?>

                <!-- Recipe Title -->
                <a href="view.php?id=<?= $row['id'] ?>">
                    <?= htmlspecialchars($row['title']) ?>
                </a>

                <!-- Buttons -->
                <div class="recipe-buttons">
                    <button class="favBtn">Add to Favorites</button>
                    <a href="edit.php?id=<?= $row['id'] ?>">
                        <button class="edit-btn">Edit</button>
                    </a>
                    <a href="delete.php?id=<?= $row['id'] ?>"
                       onclick="return confirm('Delete this recipe?');">
                        <button class="delete-btn">Delete</button>
                    </a>
                </div>

            </li>
        <?php endwhile; ?>
    <?php else: ?>
        <li>No recipes found.</li>
    <?php endif; ?>
</ul>

<!-- favorite -->
<h3 class="favorites-heading">⭐ My Favorites</h3>
<div class="favorites-container" id="favoritesList">
    <!-- Favorites will be added by JavaScript -->
</div>
</main>

<?php include 'footer.php'; ?>