<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suwmania</title>
    <style>
        * {
            text-align: center;
            margin-left: auto;
            margin-right:auto;
        }


        #container {
            /* margin-left: auto;
            margin-right: auto; */
            width: 100px;
            height: 100px;
            position: relative;
            /* background: yellow; */
        }

        .tile {
            width: 100px;
            height: 100px;
            position: absolute;
            background-color: darkgreen;
            color: white;
            font-size: 30px;
            font-weight: 700;
            border-radius: 10px;
            transition-duration: 0.2s;

        }

        .tile:hover {
            background-color: red;
        }

        #start {
            width: 100px;
            height: 30px;
        }
    </style>
</head>

<body>

    <h1 onclick="test()" id="h" style="font-size:px">Suwmania</h1>

    <button onclick="mix()" id="start">MIX</button>
    <br><br>

    <div id="container">
    </div>

    <script>
        var rows = 4;
        var cols = 4;
        var tileSize = 100;
        var posGap = [(cols - 1) * tileSize, (rows - 1) * tileSize];
        var mixPossible = true
        let lastDir = [];
        let nextTile = null;


        document.getElementById("container").style.width = (rows * tileSize) + "px";
        document.getElementById("container").style.height = (cols * tileSize) + "px";

        drawBoard();
        let list = document.getElementsByClassName("tile");

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
            document.getElementById('container').innerHTML = text;
        }

        function mix() {
            let x = [];
            let r = null;
            do {
                r = Math.floor(Math.random() * list.length);
                x = whichTilesWhere(list[r]);
            } while (!(mixPossible));

            moveTile(x[0], x[1], list[r]);
            lastDir = x[1];

        }

        function myMove(elem) {
            let x = whichTilesWhere(elem)
            moveTile(x[0], x[1], elem);
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
            let id = 0;
            for (let row = 0; row < rows; row++) {
                for (let col = 0; col < cols; col++) {
                    if (!(row == rows - 1 && col == cols - 1)) {
                        if (!(row * tileSize == parseInt(document.getElementsByClassName('tile')[id].style.top) &&
                                col * tileSize == parseInt(document.getElementsByClassName('tile')[id].style.left))) {
                            return;
                        }
                        id++;
                    }
                }
            }
            setTimeout(win, 800)

            function win() {
                alert('udało ci się brawo');
            }
        }
    </script>

</body>

</html>