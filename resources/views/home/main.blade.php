<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Woordspel</title>
    <link rel="stylesheet" href="{{ asset('css/game.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="gamePage">
        <div class="gameContainer">
            <h1>Woordspel</h1>

            <form id="guessForm">
                <div class="grid" id="gameGrid">
                    @for ($row = 0; $row < 5; $row++)
                        @for ($col = 0; $col < 5; $col++)
                            <input type="text" maxlength="1" class="cell" id="cell{{ $row * 5 + $col }}"
                                data-row="{{ $row }}" data-col="{{ $col }}" />
                        @endfor
                    @endfor
                </div>

                <div class="gameButtonsContainer">
                    <div>
                        <button class="gameButton" type="submit">Submit Guess</button>
                    </div>
                    <div>
                        <button type="button" class="gameButton" id="resetGameButton">Start New Game</button>
                    </div>
                </div>
                @csrf
            </form>

            <div id="gameResults"></div>
        </div>
    </div>

    <script>
        let currentRow = 0;
        let currentCol = 0;

        function moveFocusToNextCell() {
            currentCol++;
            if (currentCol >= 5) {
                currentCol = 4;
                return;
            }
            const nextCell = document.getElementById('cell' + (currentRow * 5 + currentCol));
            if (nextCell) {
                nextCell.focus();
            }
        }

        function updateGrid(data) {
            data.result.forEach((res, index) => {
                const cell = document.getElementById('cell' + (data.row * 5 + index));
                if (cell) {
                    cell.value = res.letter.toUpperCase();
                    cell.className = 'cell ' + res.status;
                } else {
                    console.error('Cell not found:', 'cell' + (data.row * 5 + index));
                }
            });
        }

        function handleResponse(data) {
            updateGrid(data);

            const gameResults = document.getElementById('gameResults');
            if (data.win) {
                gameResults.innerHTML = '<p class="result">You win!</p>';
            } else if (data.triesLeft === 0) {
                gameResults.innerHTML = '<p class="result">You lose! The word was ' + data.word + '</p>';
            }

            if (!data.win && data.triesLeft > 0) {
                currentRow++;
                currentCol = 0; // Reset the column to the first one

                const nextRowFirstCell = document.getElementById('cell' + (currentRow * 5));
                if (nextRowFirstCell) {
                    setTimeout(() => {
                        nextRowFirstCell.focus();
                    }, 100); // Slight delay to ensure the DOM is updated
                }
            }
        }

        document.querySelectorAll('.cell').forEach(cell => {
            cell.addEventListener('input', function() {
                let char = this.value.toUpperCase();
                if (/^[A-Z]$/.test(char)) {
                    this.value = char;
                    moveFocusToNextCell();
                } else {
                    this.value = '';
                }
            });

            cell.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.length === 0) {
                    currentCol--;
                    if (currentCol < 0) {
                        currentCol = 0;
                        return;
                    }
                    const prevCell = document.getElementById('cell' + (currentRow * 5 + currentCol));
                    if (prevCell) {
                        prevCell.focus();
                    }
                }
            });
        });

        document.getElementById('guessForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let guess = '';

            for (let col = 0; col < 5; col++) {
                const cell = document.getElementById('cell' + (currentRow * 5 + col));
                guess += cell.value.toUpperCase();
            }

            fetch('{{ route('checkGuess') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    guess: guess,
                    row: currentRow
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                handleResponse(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        document.getElementById('resetGameButton').addEventListener('click', function() {
            fetch('{{ route('resetGame') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(() => {
                // Reload the page after resetting
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        document.getElementById('cell0').focus();
    </script>

</body>
</html>
