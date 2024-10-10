<?php
/*
This function checks the session to see if there are any winners of the game.
It checks the 8 possible combinations to see if one of the players has won.
This assumes the session contains a value for each player's selection where the
first number is the column (1-based) and the second is the row (1-based), with a
dash in between. For example:
'1-3' = 'X' means that player X has selected column 1, row 3
'2-2' = 'O' means that player O has selected column 2, row 2
The session would only have values if the player has selected it, if it is not
found in the session it means that no player has selected that spot yet.

The board will look like this:

'1-1'  '2-1'  '3-1'
'1-2'  '2-2'  '3-2'
'1-3'  '2-3'  '3-3'
*/
function whoIsWinner()
{
    // 1 of 8: top row
    $winner = checkWhoHasTheSeries(['1-1', '2-1', '3-1']);
    if ($winner != null) return $winner;
    // 2 of 8: middle row
    $winner = checkWhoHasTheSeries(['1-2', '2-2', '3-2']);
    if ($winner != null) return $winner;
    // 3 of 8: bottom row
    $winner = checkWhoHasTheSeries(['1-3', '2-3', '3-3']);
    if ($winner != null) return $winner;
    // 4 of 8: left column
    $winner = checkWhoHasTheSeries(['1-1', '1-2', '1-3']);
    if ($winner != null) return $winner;
    // 5 of 8: middle column
    $winner = checkWhoHasTheSeries(['2-1', '2-2', '2-3']);
    if ($winner != null) return $winner;
    // 6 of 8: right column
    $winner = checkWhoHasTheSeries(['3-1', '3-2', '3-3']);
    if ($winner != null) return $winner;
    // 7 of 8: diagonal left to right
    $winner = checkWhoHasTheSeries(['1-1', '2-2', '3-3']);
    if ($winner != null) return $winner;
    // 8 of 8: diagonal right to left
    $winner = checkWhoHasTheSeries(['3-1', '2-2', '1-3']);
    if ($winner != null) return $winner;
    return null; // Its a draw
}

/*
This function is given a list of values, which can be either 'X' or 'O'.
It returns:
  An 'X' if all 3 items in the list are 'X'
  An 'O' if all 3 items in the list are 'O'
  A null otherwise (i.e. there is no winner for thie combination)
*/
function checkWhoHasTheSeries($list)
{
    $XCount = 0;
    $OCount = 0;
    foreach ($list as $value) {
        if ($_SESSION[$value] == 'X') {
            $XCount++;
        } elseif ($_SESSION[$value] == 'O') {
            $OCount++;
        }
    }
    if ($XCount == 3)
        return 'X';
    elseif ($OCount == 3)
        return 'O';
    else
        return null;
}
?>