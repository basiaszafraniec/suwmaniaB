<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suwmania</title>
    <link rel="stylesheet" type="text/css" href="styles2.css" />
</head>

<body>

    <h1 id="h" title="game by Arkudias and Barkudias">SUWMANIA</h1>

    <iframe id="dummyframe" name="dummyframe" style="display:none;"></iframe>

    <form id="nameForm" onsubmit="test(name.value)" target="dummyframe" style="display:none;">
        <input type="text" name="name">
    </form>

    <button onclick="start()" id="start">START</button><br>

    <!-- <button onclick="testRead()" id="testButton">test</button><br> -->

    <div id="container">
    </div>

    <div>
        <h2 id="count">0</h2>

    </div>
    <ul id="ranking"></ul>


    <script>
        var rows = 4;
        var cols = 4;
        var tileSize = 100;
        var posGap = [(cols - 1) * tileSize, (rows - 1) * tileSize];
        var mixPossible = true;
        var mixMoves = 20;
        var winTxt = "UDAŁOMISIĘBRAWO";
        let lastDir = [];
        let mixed = false;
        let ready = false;
        let myMoves = 0;
        let list = document.getElementsByClassName("tile");
        let winList = [];


        document.getElementById("container").style.width = (rows * tileSize) + "px";
        document.getElementById("container").style.height = (cols * tileSize) + "px";

        drawBoard();
        testRead();

        function testRead() {
            const xhttp = new XMLHttpRequest();
            xhttp.open("GET", "highscore.json", true);
            xhttp.onload = function() {
                winList = JSON.parse(xhttp.responseText);
                // document.getElementById("ranking").innerHTML = winList[0].name + " - " + winList[0].score;
                // alert(winList.length)
                sortWin(true);
            }
            xhttp.send();
        }

        function sortWin(x) {
            console.log(winList);
            let sortedWinList = winList.sort((c1, c2) => (c1.score < c2.score) ? 1 : (c1.score > c2.score) ? -1 : 0);
            if (x) {
                for (i in sortedWinList) {
                    document.getElementById("ranking").innerHTML += "<li>" + sortedWinList[i].name + " - " + sortedWinList[i].score + "</li>"
                }
            } else {
                document.getElementById("ranking").innerHTML += "<li>" + winList[winList.length-1].name + " - " + winList[winList.length-1].score + "</li>"

            }console.log(winList);
        }

        function drawBoard() {
            let text = '';
            let digitsToTxt = 1;
            for (let row = 0; row < rows; row++) {
                for (let col = 0; col < cols; col++) {
                    if (!(row == rows - 1 && col == cols - 1)) {
                        text += '<button class="tile" onclick="myMove(this)" style="top:' +
                            row * tileSize + 'px; left:' + col * tileSize + 'px;">' + digitsToTxt +
                            '</button>';
                        digitsToTxt++;
                    }
                }
            }
            document.getElementById('container').innerHTML += text;
        }

        function start() {
            document.getElementById('start').style.backgroundColor = "lightgrey";
            document.getElementById('start').style.borderColor = "grey";
            document.getElementById("start").disabled = true;

            ready = false;
            let id = null;
            id = setInterval(mix, 120);
            let num = 0;

            function mix() {
                let x = [];
                let r = null;
                do {
                    r = Math.floor(Math.random() * list.length);
                    x = whichTilesWhere(list[r]);
                } while (!(mixPossible));

                moveTile(x[0], x[1], list[r]);
                lastDir = x[1];
                num++;
                if ((mixed && num > mixMoves) || num > 50) {
                    clearInterval(id);
                    ready = true;
                    document.getElementById('start').style.backgroundColor = "#ccaebb";
                    document.getElementById('start').style.borderColor = "color(srgb 0.705 0.5992 0.6441)";
                    document.getElementById("start").disabled = false;

                }
            }
        }

        function myMove(elem) {
            // if (ready) {
            let x = whichTilesWhere(elem)
            if (x[0].length > 0) {
                moveTile(x[0], x[1], elem);
                myMoves++;
                document.getElementById("count").innerHTML = myMoves;
            }
            // }
        }

        function whichTilesWhere(elem) {
            mixPossible = true
            let tilesList = [];
            let dir = [];
            let posLeft = parseInt(elem.style.left);
            let posTop = parseInt(elem.style.top);

            if (posLeft === posGap[0] || posTop === posGap[1]) {
                for (n = 0; n < list.length; n++) {
                    if (posLeft === posGap[0] && parseInt(list[n].style.left) === posGap[0] &&
                        ((Math.min(posTop, posGap[1])) <= (parseInt(list[n].style.top)) &&
                            (parseInt(list[n].style.top)) <= (Math.max(posTop, posGap[1])))) {
                        tilesList.push(list[n]);
                        dir = (posTop < posGap[1]) ? [0, 5] : [0, -5];
                        if (lastDir[0] === 0) {
                            mixPossible = false;
                        }

                    } else if (posTop === posGap[1] && parseInt(list[n].style.top) === posGap[1] &&
                        Math.min(posLeft, posGap[0]) <= parseInt(list[n].style.left) &&
                        parseInt(list[n].style.left) <= Math.max(posLeft, posGap[0])) {
                        tilesList.push(list[n]);
                        dir = (posLeft < posGap[0]) ? [5, 0] : [-5, 0];
                        if (lastDir[1] === 0) {
                            mixPossible = false;
                        }
                    }
                }
            } else {
                mixPossible = false;
            }
            return [tilesList, dir];
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
                        testSolve();
                    }
                }
            }
            if (elems.length > 0) {
                posGap = [parseInt(gap.style.left), parseInt(gap.style.top)];
            }
        }

        function testSolve() {
            mixed = true;
            let ok = true
            let id = 0;
            for (let row = 0; row < rows; row++) {
                for (let col = 0; col < cols; col++) {
                    if (!(row == rows - 1 && col == cols - 1)) {
                        if (!(row * tileSize == parseInt(document.getElementsByClassName('tile')[id].style.top) &&
                                col * tileSize == parseInt(document.getElementsByClassName('tile')[id].style.left))) {
                            ok = false
                        } else {
                            mixed = false;
                        }
                        id++;
                    }
                }
            }
            if (ok) {
                setTimeout(win, 500);
            }


            function win() {
                // document.getElementById("testID").value = myMoves;
                for (let t = 0; t < list.length; t++) {
                    list[t].innerHTML = winTxt.charAt(t);

                }
                setTimeout(() => {
                    document.getElementById("nameForm").style.display = "inline"
                }, 500)
            }
        }

        function test(x) {
            winList.push({
                name: x,
                score: myMoves
            });

            try {
                const xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "phpindex.php", true);
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState !== 4) return;
                };
                xmlhttp.send(JSON.stringify(winList));
            } catch (ex) {
                alert('Błąd zapisu!');
            }
            sortWin(false);

        }
    </script>

</body>

</html>