<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suwmania</title>
    <link rel="icon" type="image/x-icon" href="faviconPink.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="styles4.css" />
</head>

<body>
    <h1>HELLO WORLD</h1>
    <div class="container-fluid">
        <div class="row my-3">
            <h1 id="suwmania" title="game by Arkudias and Barkudias">SUWMANIA</h1>
        </div>

        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4" style="height: 550px;">
                <button onclick="moveTileSequence()" id="start">START</button><br>
                <div id="container">
                    <div id="frame"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- <iframe id="dummyframe" name="dummyframe" style="display:none;"></iframe>
                <form id="nameForm" onsubmit="saveScore(name.value)" target="dummyframe" style="display:none;">
                    <input type="text" name="name">
                </form> -->

                <h2 style="display: block;" id="steps">STEPS: 0</h2>
                <ul id="ranking"></ul>
            </div>
        </div>

    </div>


    <script>
        const rows = 4,
            cols = 4,
            tileSize = 100;
        var posGap = [(cols - 1) * tileSize, (rows - 1) * tileSize];
        var allTilesList = document.getElementsByClassName("tile");

        const winTxt = "UDAŁOMISIĘBRAWO";

        var dir = [0, 0];

        var raedyToPlay = false;
        var steps = 0;
        // var name = null;

        let playersScoreList = {};
        let sortedPlayersScoreList = {};

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
            sortedPlayersScoreList = JSON.parse(JSON.stringify(playersScoreList));
            sortedPlayersScoreList.sort((c1, c2) => (c1.score > c2.score) ? 1 : (c1.score < c2.score) ? -1 : 0);
            for (i = 0; i < Math.min(5, sortedPlayersScoreList.length); i++) {
                document.getElementById("ranking").innerHTML += "<li>" + sortedPlayersScoreList[i].name.toUpperCase() + " - " +
                    sortedPlayersScoreList[i].score + "</li>";
            }
        }

        function saveScore(myName) {
            playersScoreList.push({
                name: myName,
                score: steps
            });

            console.log(playersScoreList);


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

        function drawBoard() {
            let text = '';
            let tileNumberTxt = 1;
            for (let row = 0; row < rows; row++) {
                for (let col = 0; col < cols; col++) {
                    if (!(row == rows - 1 && col == cols - 1)) {
                        text += '<button class="tile" onclick="moveTileSequence(this)" style="top:' +
                            row * tileSize + 'px; left:' + col * tileSize + 'px;">' + tileNumberTxt +
                            '</button>';
                        tileNumberTxt++;
                    }
                }
            }
            document.getElementById('container').innerHTML += text;
        }

        async function moveTileSequence(tile) {
            let allMixed;
            let mixedTest;
            let maxMix = (tile) ? 0 : 2;
            for (mixCount = 0;
                (!((allMixed && mixCount > 20) || mixCount > maxMix)); mixCount++) {
                if (tile) {
                    if (raedyToPlay) {
                        elem = tile;
                    } else {
                        return;
                    }
                } else {
                    document.getElementById('start').style.backgroundColor = "lightgrey";
                    document.getElementById('start').style.borderColor = "grey";
                    document.getElementById("start").disabled = true;

                    do {
                        elem = allTilesList[Math.floor(Math.random() * allTilesList.length)];
                    }
                    while (!(((parseInt(elem.style.left) === posGap[0]) && (dir[1] === 0)) ||
                            ((parseInt(elem.style.top) === posGap[1]) && (dir[0] === 0))));

                }
                let tilesAndDir = await whichTilesWhere(elem);
                await moveTile(tilesAndDir[0], tilesAndDir[1]);
                allMixed = await checkSolve();

                if (tile) {
                    steps++;
                    document.getElementById("steps").innerHTML = "STEPS: " + steps;
                }
            }
            raedyToPlay = true;
            document.getElementById('start').style.backgroundColor = "#ccaebb";
            document.getElementById('start').style.borderColor = "#b399a5";
            document.getElementById("start").disabled = false;

        }

        function whichTilesWhere(elem) {
            return new Promise(function(resolve) {
                let tilesToMove = [];
                let posLeft = parseInt(elem.style.left);
                let posTop = parseInt(elem.style.top);

                if (posLeft === posGap[0] || posTop === posGap[1]) {
                    for (n = 0; n < allTilesList.length; n++) {
                        if (posLeft === posGap[0] && parseInt(allTilesList[n].style.left) === posGap[0] &&
                            ((Math.min(posTop, posGap[1])) <= (parseInt(allTilesList[n].style.top)) &&
                                (parseInt(allTilesList[n].style.top)) <= (Math.max(posTop, posGap[1])))) {
                            tilesToMove.push(allTilesList[n]);
                            dir = (posTop < posGap[1]) ? [0, 5] : [0, -5];
                        } else if (posTop === posGap[1] && parseInt(allTilesList[n].style.top) === posGap[1] &&
                            Math.min(posLeft, posGap[0]) <= parseInt(allTilesList[n].style.left) &&
                            parseInt(allTilesList[n].style.left) <= Math.max(posLeft, posGap[0])) {
                            tilesToMove.push(allTilesList[n]);
                            dir = (posLeft < posGap[0]) ? [5, 0] : [-5, 0];
                        }
                    }
                }
                if (tilesToMove.length) {
                    posGap = [parseInt(elem.style.left), parseInt(elem.style.top)];
                }
                resolve([tilesToMove, dir]);
            })
        }

        function moveTile(elems, dir) {
            return new Promise(function(resolve) {

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
                            resolve();
                        }
                    }
                }
            })
        }

        function checkSolve() {
            return new Promise(function(resolve) {
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
                    for (let t = 0; t < allTilesList.length; t++) {
                        allTilesList[t].innerHTML = winTxt.charAt(t);
                    }
                    console.log(Math.min(4,sortedPlayersScoreList.length - 1));
                    if (steps < sortedPlayersScoreList[Math.min(4,sortedPlayersScoreList.length - 1)].score) {
                        setTimeout(() => {
                            let name = prompt("Congrats! You've made it to top 5! What's your name?:", "name");
                            if (name != null) {
                                saveScore(name);
                            }
                        }, 500)

                    }
                }
                resolve(allMixed, allSolved);
            })
        }
    </script>

</body>

</html>