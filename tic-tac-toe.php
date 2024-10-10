<?php
session_start();

// Include the functions for winner checking
function whoIsWinner() {
    $winner = checkWhoHasTheSeries(['1-1', '2-1', '3-1']);
    if ($winner != null) return $winner;
    $winner = checkWhoHasTheSeries(['1-2', '2-2', '3-2']);
    if ($winner != null) return $winner;
    $winner = checkWhoHasTheSeries(['1-3', '2-3', '3-3']);
    if ($winner != null) return $winner;
    $winner = checkWhoHasTheSeries(['1-1', '1-2', '1-3']);
    if ($winner != null) return $winner;
    $winner = checkWhoHasTheSeries(['2-1', '2-2', '2-3']);
    if ($winner != null) return $winner;
    $winner = checkWhoHasTheSeries(['3-1', '3-2', '3-3']);
    if ($winner != null) return $winner;
    $winner = checkWhoHasTheSeries(['1-1', '2-2', '3-3']);
    if ($winner != null) return $winner;
    $winner = checkWhoHasTheSeries(['3-1', '2-2', '1-3']);
    if ($winner != null) return $winner;
    return null; // It's a draw
}

function checkWhoHasTheSeries($list) {
    $XCount = 0;
    $OCount = 0;
    foreach ($list as $value) {
        if ($_SESSION[$value] == 'X') {
            $XCount++;
        } elseif ($_SESSION[$value] == 'O') {
            $OCount++;
        }
    }
    if ($XCount == 3) return 'X';
    elseif ($OCount == 3) return 'O';
    else return null;
}

// Initialize the game state if it's the first request
if (!isset($_SESSION['grid'])) {
    $_SESSION['grid'] = array_fill(0, 3, array_fill(0, 3, '')); // 3x3 grid initialized with empty strings
    $_SESSION['currentPlayer'] = 'X'; // X starts first
    $_SESSION['winner'] = null;
}

// Check if a button was clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the button name that was clicked
    $button = key($_POST);
    $row = (int)explode('-', $button)[0] - 1;
    $col = (int)explode('-', $button)[1] - 1;

    // If the cell is empty, update the grid
    if ($_SESSION['grid'][$row][$col] === '' && $_SESSION['winner'] === null) {
        $_SESSION['grid'][$row][$col] = $_SESSION['currentPlayer'];

        // Save the player's move to session
        $_SESSION[$button] = $_SESSION['currentPlayer'];

        // Check for a winner
        $winner = whoIsWinner();
        if ($winner) {
            $_SESSION['winner'] = $winner;
        } elseif (count(array_filter(array_merge(...$_SESSION['grid']))) === 9) {
            $_SESSION['winner'] = 'Draw';
        } else {
            // Switch player
            $_SESSION['currentPlayer'] = $_SESSION['currentPlayer'] === 'X' ? 'O' : 'X';
        }
    }
}

// Function to display the grid
function displayGrid($grid) {
    foreach ($grid as $row => $columns) {
        echo "<tr>";
        foreach ($columns as $col => $value) {
            $buttonDisabled = $value !== '' ? 'disabled' : '';
            $buttonColor = '';
            if ($value === 'X') {
                $buttonColor = 'style="background-color: green; color: white;"';
            } elseif ($value === 'O') {
                $buttonColor = 'style="background-color: red; color: white;"';
            }
            echo "<td>
                    <button type='submit' name='" . ($row + 1) . "-" . ($col + 1) . "' 
                    value='" . $value . "' " . $buttonDisabled . " " . $buttonColor . ">" . ($value !== '' ? $value : '') . "</button>
                  </td>";
        }
        echo "</tr>";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="HandheldFriendly" content="True">
    <title>Tic Tac Toe</title>
    <style>
        /* Button background is blue with a black border */
        button {
            background-color: #3498db;
            height: 100%;
            width: 100%;
            text-align: center;
            font-size: 20px;
            color: white;
            vertical-align: middle;
            border: 0px;
        }

        /* Styles the table cells to look like a tic-tac-toe grid */
        table td {
            text-align: center;
            vertical-align: middle;
            padding: 0px;
            margin: 0px;
            width: 75px;
            height: 75px;
            font-size: 20px;
            border: 3px solid #040404;
            color: white;
        }

        /* This shows a darker blue background when the mouse hovers over the buttons */
        button:hover,
        button:focus {
            background-color: #04469d;
            text-decoration: none;
            outline: none;
        }
    </style>
</head>

<body>
    <h1>Tic Tac Toe</h1>
    <p>Turn: <?php echo $_SESSION['currentPlayer']; ?></p>
    <p>
        <?php 
            if ($_SESSION['winner']) {
                echo $_SESSION['winner'] === 'Draw' ? "It's a draw!" : "The winner is " . $_SESSION['winner'] . "!";
            } 
        ?>
    </p>

    <form method="POST" action="">
        <table>
            <?php displayGrid($_SESSION['grid']); ?>
        </table>
    </form>

    <form method="POST" action="">
        <button type="submit" name="reset">Reset Game</button>
    </form>

    <?php
    // Handle the reset action
    if (isset($_POST['reset'])) {
        session_destroy();
        header('Location: tic-tac-toe.php');
        exit();
    }
    ?>
</body>

</html>
