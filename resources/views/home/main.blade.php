<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/game.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>Woordspel</title>
</head>
<body>
    <div class="gamePage">
        <div class="gameContainer">
            <h1>Woordspel</h1>
            <form id="guessForm">
                <div class="grid" id="gameGrid">
                    @for ($row = 0; $row < 5; $row++)
                        @for ($col = 0; $col < 5; $col++)
                            <input type="text" maxlength="1" class="cell" id="cell{{ $row * 5 + $col }}" data-row="{{ $row }}" data-col="{{ $col }}" />
                        @endfor
                    @endfor
                </div>
                <button type="submit">Submit Guess</button>
                @csrf
            </form>
            <!-- Inside the gameContainer div -->
<button id="resetGameButton">Start New Game</button>

        </div>

        <!-- Container for displaying game results -->
        <div id="gameResults">
            <!-- Results will be dynamically inserted here -->
        </div>
    </div>

    <script>
        // JavaScript code remains mostly the same as before

        // Initialize currentRow and currentCol
        let currentRow = 0;
        let currentCol = 0;

        // Function to move focus to the next cell
        function moveFocusToNextCell() {
            currentCol++;
            if (currentCol >= 5) {
                currentCol = 4; // Limit within the row
                return;
            }
            const nextCell = document.getElementById('cell' + (currentRow * 5 + currentCol));
            if (nextCell) {
                nextCell.focus();
            }
        }

        // Function to update the game grid based on server response
        function updateGrid(data) {
            data.result.forEach((res, index) => {
                const cell = document.getElementById('cell' + (data.row * 5 + index));
                if (cell) {
                    cell.value = res.letter;
                    cell.className = 'cell ' + res.status;
                } else {
                    console.error('Cell not found:', 'cell' + (data.row * 5 + index));
                }
            });
        }

        // Function to handle response from server
        function handleResponse(data) {
    updateGrid(data); // Update grid based on response

    // Display results on the page
    const gameResults = document.getElementById('gameResults');
    if (data.win) {
        gameResults.innerHTML = '<p class="result">You win!</p>';
    } else if (data.triesLeft === 0) {
        gameResults.innerHTML = '<p class="result">You lose! The word was ' + data.word + '</p>';
    }

    // Check if game is over (win or lose) to reset the game
    if (data.win || data.triesLeft === 0) {
        resetGame(); // Reset the game state
    } else {
        // Move focus to the next row
        currentRow++;
        currentCol = 0;
        const nextRowFirstCell = document.getElementById('cell' + (currentRow * 5));
        if (nextRowFirstCell) {
            nextRowFirstCell.focus();
        }
    }
}




        // Event listeners for input and keydown
document.querySelectorAll('.cell').forEach(cell => {
    cell.addEventListener('input', function() {
        let char = this.value.toUpperCase(); // Convert to uppercase
        if (/^[A-Z]$/.test(char)) {
            moveFocusToNextCell(); // Move focus to the next cell
        } else {
            this.value = ''; // Clear input if not a valid letter
        }
    });

    cell.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && this.value.length === 0) {
            currentCol--;
            if (currentCol < 0) {
                currentCol = 0; // Limit within the row
                return;
            }
            const prevCell = document.getElementById('cell' + (currentRow * 5 + currentCol));
            if (prevCell) {
                prevCell.focus();
            }
        }
    });
});


        // Submit form event listener
document.getElementById('guessForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let guess = '';

    // Collect guess from input fields
    for (let col = 0; col < 5; col++) {
        const cell = document.getElementById('cell' + (currentRow * 5 + col));
        guess += cell.value.toUpperCase(); // Ensure guess is uppercase
    }

    fetch('{{ route('checkGuess') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ guess: guess, row: currentRow })
    })
    .then(response => response.json())
    .then(data => {
        handleResponse(data); // Call handleResponse to process the data
    })
    .catch(error => {
        console.error('Error:', error);
    });
});


        // Focus on the first cell initially
        document.getElementById('cell0').focus();

        // Function to reset the game
// Function to reset the game
// Function to reset the game
function resetGame() {
    // Clear all input fields and reset their classes
    document.querySelectorAll('.cell').forEach(cell => {
        cell.value = '';
        cell.className = 'cell';
    });

    // Reset game state variables
    currentRow = 0;
    currentCol = 0;

    // Clear game results display
    const gameResults = document.getElementById('gameResults');
    gameResults.innerHTML = '';

    // Set focus to the first cell of the first row
    document.getElementById('cell0').focus();
}

// Event listener for the reset button
document.getElementById('resetGameButton').addEventListener('click', function() {
    resetGame(); // Call resetGame function when button is clicked
});


    </script>
</body>
</html>
