<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suwmania</title>
    <link rel="stylesheet" type="text/css" href="styles3.css" />
</head>

<body>

    <h1 id="suwmania" title="game by Arkudias and Barkudias">SUWMANIA</h1>

    <iframe id="dummyframe" name="dummyframe" style="display:none;"></iframe>

    <form id="nameForm" onsubmit="saveScore(name.value)" target="dummyframe" style="display:none;">
        <input type="text" name="name">
    </form>

    <button onclick="start()" id="start">START</button><br>

    <div id="container">
        <div id="frame"></div>
    </div>

    <div>
        <h2 id="score">0</h2>

    </div>
    <ul id="ranking"></ul>


    <script>
        const rows = 4,
            cols = 4,
            tileSize = 100;
        var posGap = [(cols - 1) * tileSize, (rows - 1) * tileSize];
        var allTilesList = document.getElementsByClassName("tile");

        const winTxt = "UDAŁOMISIĘBRAWO";

        var mixPossible = true;
        var lastDir = [];

        var allMixed = false;
        var raedyToPlay = false;
        let score = 0;

        let playersScoreList = [];

        //setting container size
        document.getElementById("container").style.width = (rows * tileSize) + "px";
        document.getElementById("container").style.height = (cols * tileSize) + "px";

        drawBoard();
        readScore();

        function readScore() {
            const xhttp = new XMLHttpRequest();
            xhttp.open("GET", "highscore.json", true);
            xhttp.onload = function() {
                playersScoreList = JSON.parse(xhttp.responseText);
                rankingSort();
            }
            xhttp.send();
        }

        function rankingSort() {
            document.getElementById("ranking").innerHTML = "";

            const sortedPlayersScoreList = JSON.parse(JSON.stringify(playersScoreList));
            sortedPlayersScoreList.sort((c1, c2) => (c1.score < c2.score) ? 1 : (c1.score > c2.score) ? -1 : 0);

            for (i in sortedPlayersScoreList) {
                document.getElementById("ranking").innerHTML += "<li>" + sortedPlayersScoreList[i].name + " - " +
                    sortedPlayersScoreList[i].score + "</li>"
            }
        }

        function drawBoard() {
            let text = '';
            let tileNumberTxt = 1;
            for (let row = 0; row < rows; row++) {
                for (let col = 0; col < cols; col++) {
                    if (!(row == rows - 1 && col == cols - 1)) {
                        text += '<button class="tile" onclick="myMove(this)" style="top:' +
                            row * tileSize + 'px; left:' + col * tileSize + 'px;">' + tileNumberTxt +
                            '</button>';
                        tileNumberTxt++;
                    }
                }
            }
            document.getElementById('container').innerHTML += text;
        }

        function start() {
            document.getElementById('start').style.backgroundColor = "lightgrey";
            document.getElementById('start').style.borderColor = "grey";
            document.getElementById("start").disabled = true;

            raedyToPlay = false;
            let id = null;
            id = setInterval(mix, 120);
            let mixCount = 0;

            function mix() {
                let tilesAndDir = [];
                let r = null;
                do {
                    r = Math.floor(Math.random() * allTilesList.length);
                    tilesAndDir = whichTilesWhere(allTilesList[r]);
                } while (!(mixPossible));

                moveTile(tilesAndDir[0], tilesAndDir[1], allTilesList[r]);
                lastDir = tilesAndDir[1];
                mixCount++;
                if ((allMixed && mixCount > 20) || mixCount > 50) {
                    clearInterval(id);
                    raedyToPlay = true;
                    document.getElementById('start').style.backgroundColor = "#ccaebb";
                    document.getElementById('start').style.borderColor = "#b399a5";
                    document.getElementById("start").disabled = false;

                }
            }
        }

        function myMove(elem) {
            if (raedyToPlay) {
                let tilesAndDir = whichTilesWhere(elem)
                if (tilesAndDir[0].length > 0) {
                    moveTile(tilesAndDir[0], tilesAndDir[1], elem);
                    score++;
                    document.getElementById("score").innerHTML = score;
                }
            }
        }

        function whichTilesWhere(elem) {
            mixPossible = true
            let tilesToMove = [];
            let dir = [];
            let posLeft = parseInt(elem.style.left);
            let posTop = parseInt(elem.style.top);

            if (posLeft === posGap[0] || posTop === posGap[1]) {
                for (n = 0; n < allTilesList.length; n++) {
                    if (posLeft === posGap[0] && parseInt(allTilesList[n].style.left) === posGap[0] &&
                        ((Math.min(posTop, posGap[1])) <= (parseInt(allTilesList[n].style.top)) &&
                            (parseInt(allTilesList[n].style.top)) <= (Math.max(posTop, posGap[1])))) {
                        tilesToMove.push(allTilesList[n]);
                        dir = (posTop < posGap[1]) ? [0, 5] : [0, -5];
                        if (lastDir[0] === 0) {
                            mixPossible = false;
                        }

                    } else if (posTop === posGap[1] && parseInt(allTilesList[n].style.top) === posGap[1] &&
                        Math.min(posLeft, posGap[0]) <= parseInt(allTilesList[n].style.left) &&
                        parseInt(allTilesList[n].style.left) <= Math.max(posLeft, posGap[0])) {
                        tilesToMove.push(allTilesList[n]);
                        dir = (posLeft < posGap[0]) ? [5, 0] : [-5, 0];
                        if (lastDir[1] === 0) {
                            mixPossible = false;
                        }
                    }
                }
            } else {
                mixPossible = false;
            }
            return [tilesToMove, dir];
        }

        function moveTile(elems, dir, gap) {
            let id = null;
            clearInterval(id);
            id = setInterval(frame, 1);

            function frame() {
                for (e = 0; e < elems.length; e++) {
                    let posLeft = parseInt(elems[e].style.left);
                    let posTop = parseInt(elems[e].style.top);
                    posLeft += dir[0];
                    posTop += dir[1];
                    elems[e].style.left = posLeft + "px";
                    elems[e].style.top = posTop + "px";
                    if (posLeft % tileSize == 0 && posTop % tileSize == 0) {
                        clearInterval(id);

                    }
                }
                checkSolve();
            }
            if (elems.length) {
                posGap = [parseInt(gap.style.left), parseInt(gap.style.top)];
            }
        }

        function checkSolve() {
            let allMixed = true;
            let allSolved = true;
            let id = 0;
            for (let row = 0; row < rows; row++) {
                for (let col = 0; col < cols; col++) {
                    if (!(row == rows - 1 && col == cols - 1)) {
                        if (!(row * tileSize == parseInt(document.getElementsByClassName('tile')[id].style.top) &&
                                col * tileSize == parseInt(document.getElementsByClassName('tile')[id].style.left))) {
                            allSolved = false
                        } else {
                            allMixed = false;
                        }
                        id++;
                    }
                }
            }
            if (allSolved) {
                setTimeout(win, 500);
            }


            function win() {
                for (let t = 0; t < allTilesList.length; t++) {
                    allTilesList[t].innerHTML = winTxt.charAt(t);
                }
                setTimeout(() => {
                    document.getElementById("nameForm").style.display = "inline"
                }, 500)
            }
        }

        function saveScore(name) {
            document.getElementById("nameForm").style.display = "none";
            playersScoreList.push({
                name: name,
                score: score
            });

            try {
                const xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "phpindex.php", true);
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState !== 4) return;
                };
                xmlhttp.send(JSON.stringify(playersScoreList));
            } catch (ex) {
                alert('Błąd zapisu!');
            }
            rankingSort();

        }
    </script>

</body>

</html>