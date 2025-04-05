// DOM elements
const searchInput = document.getElementById("search-input");
const searchResults = document.getElementById("search-results");

// Search function
searchInput.addEventListener("input", async () => {
    const query = searchInput.value.trim(); // Get the input query and trim whitespace
    searchResults.innerHTML = ""; // Clear previous results

    if (query) {
        try {
            const response = await fetch(`http://localhost/yourproject/search.php?q=${query}`); // Send query to the backend
            const games = await response.json(); // Parse the response as JSON

            if (games.length > 0) {
                games.forEach(game => {
                    const li = document.createElement("li"); // Create a list item for each game
                    li.textContent = game.title; // Set the text to the game's title
                    li.addEventListener("click", () => {
                        searchInput.value = game.title; // Set the input value to the clicked game title
                        searchResults.innerHTML = ""; // Hide results after selection
                    });
                    searchResults.appendChild(li); // Add the list item to the results
                });
                searchResults.style.display = "block"; // Show the results
            } else {
                const li = document.createElement("li"); // If no results found, display a message
                li.textContent = "No results found";
                li.style.color = "#ccc"; // Make the "no results" text gray
                searchResults.appendChild(li); // Add to the results list
                searchResults.style.display = "block"; // Show the results
            }
        } catch (error) {
            console.error("Error fetching search results:", error); // Log any errors during the fetch process
        }
    } else {
        searchResults.style.display = "none"; // Hide the results when the query is empty
    }
});

// Hide search results when clicking outside the search container
document.addEventListener("click", (event) => {
    if (!event.target.closest(".search-container")) { // If the click is outside the search container
        searchResults.style.display = "none"; // Hide the results
    }
});

function searchGames(event) {
    if (event) event.preventDefault(); 

    let query = document.getElementById('search-input').value;

    if (query.trim() === '') {
        alert('Please enter a search term.');
        return false;
    }

    fetch(`../API/search.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            let resultsContainer = document.getElementById('results');
            resultsContainer.innerHTML = ''; 

            if (data.error) {
                resultsContainer.innerHTML = `<p>${data.error}</p>`;
                return;
            }

            data.forEach(game => {
                let li = document.createElement('li');
                li.innerHTML = `<div class="card">
                        <div class="card-text">
                            <h3><a href="game_details.php?game_id=${game.id}">${game.title}</a></h3>
                            <p>${game.description}</p>
                        </div>
                        <div class="card-image">
                            <img src="${game.preview_img}" alt="${game.title}">
                        </div>
                    </div>`;
                
                resultsContainer.appendChild(li);
            });
        })
        .catch(error => console.error('Error:', error));

    return false;
}
