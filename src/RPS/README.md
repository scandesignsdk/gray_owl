Rock, Paper and Scissor
-----------------------

##### Who will win the tournament

* The tournament will be played 1v1, if one player does not have any opponent the player will automaticcly advance to the next round.
* Does 1 player have anything else than rock, paper or scissor, the whole tournament will be **invalid**.
* If two players have the same, the first player in the match will win the match.
* If 1 or less players, the tournament will be **cancelled**

And as always: `Rock` beats `scissor`, `scissor` beats `paper` and `paper` beats `rock`

Sample input

````
'Adam' - 'P',
'Andrew' - 'S',
'Chris' - 'r',
'Casey' - 'P',
'Cadman' - 'R'

winner = 'Cadman';
````

The tournament would look like this

```
Round 1:          Round 2:            Round 3:            Round 4:
Adam - Andrew     Andrew - Casey      Andrew - Cadman     Cadman
Chris - Casey     Cadman
Cadman
```

Write your code in RPSTournament.php, the getWinner should either return the name or throw exception if tournament is invalid

*Maybe there are some other things you need to change?*
