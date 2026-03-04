// ===============================
// Recipe App JS
// Handles Search & Favorites
// ===============================

// ====== DOM Elements ======
const searchInput = document.getElementById("searchInput");
const recipeListElement = document.getElementById("recipeList");
const favoritesList = document.getElementById("favoritesList");

// ====== Favorites Storage ======
let favorites = JSON.parse(localStorage.getItem("favorites") || "[]");

// ====== Render Favorites ======
function renderFavorites() {
    if (!favoritesList) return;
    
    favoritesList.innerHTML = "";

    if (favorites.length === 0) {
        const emptyMsg = document.createElement("div");
        emptyMsg.className = "empty-message";
        emptyMsg.textContent = "No favorites yet. Add recipes to favorites!";
        favoritesList.appendChild(emptyMsg);
        return;
    }

    favorites.forEach((recipe, index) => {
        const card = document.createElement("div");
        card.className = "favorite-card";

        const titleLink = document.createElement("a");
        titleLink.className = "favorite-title";
        titleLink.href = `view.php?id=${recipe.id}`;
        titleLink.textContent = recipe.title;

        const removeBtn = document.createElement("button");
        removeBtn.className = "remove-btn";
        removeBtn.textContent = "Remove";
        removeBtn.addEventListener("click", (e) => {
            e.preventDefault();
            // Remove from favorites array
            favorites = favorites.filter(fav => fav.id != recipe.id);
            localStorage.setItem("favorites", JSON.stringify(favorites));
            renderFavorites();
            alert(`"${recipe.title}" removed from favorites!`);
        });

        card.appendChild(titleLink);
        card.appendChild(removeBtn);
        favoritesList.appendChild(card);
    });
}

// ====== Initial render ======
renderFavorites();

// ====== Search Filter ======
if (searchInput && recipeListElement) {
    searchInput.addEventListener("keyup", () => {
        const filter = searchInput.value.toLowerCase();
        const recipeItems = recipeListElement.getElementsByTagName("li");
        
        for (let li of recipeItems) {
            const aTag = li.querySelector("a");
            if (aTag) {
                const recipeName = aTag.textContent.trim().toLowerCase();
                li.style.display = recipeName.includes(filter) ? "" : "none";
            }
        }
    });
}

// ====== Add to Favorites ======
function setupFavoriteButtons() {
    document.querySelectorAll(".favBtn").forEach(button => {
        // Remove existing event listeners to prevent duplicates
        button.removeEventListener("click", handleFavoriteClick);
        button.addEventListener("click", handleFavoriteClick);
    });
}

function handleFavoriteClick(e) {
    e.preventDefault();
    
    // Find the recipe container
    const recipeItem = this.closest('li');
    if (!recipeItem) return;
    
    // Get recipe link and title
    const recipeLink = recipeItem.querySelector('a');
    if (!recipeLink) return;
    
    const recipeHref = recipeLink.getAttribute('href');
    const recipeId = recipeHref.split('=')[1];
    const recipeTitle = recipeLink.textContent.trim();
    
    // Check if already in favorites
    const exists = favorites.some(fav => fav.id == recipeId);
    
    if (!exists) {
        // Add to favorites
        favorites.push({ 
            id: recipeId, 
            title: recipeTitle 
        });
        
        localStorage.setItem("favorites", JSON.stringify(favorites));
        renderFavorites();
        alert(`"${recipeTitle}" added to favorites!`);
    } else {
        alert(`"${recipeTitle}" is already in favorites!`);
    }
}

// Initialize favorite buttons when page loads
document.addEventListener('DOMContentLoaded', function() {
    setupFavoriteButtons();
});

// Also run when page loads normally
setupFavoriteButtons();

// If recipes are loaded dynamically (like after search), 
// we need to re-attach event listeners
if (recipeListElement) {
    const observer = new MutationObserver(function() {
        setupFavoriteButtons();
    });
    
    observer.observe(recipeListElement, { childList: true, subtree: true });
}