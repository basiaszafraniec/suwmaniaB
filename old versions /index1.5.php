<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suwmania</title>
    <style>
        #container {
            width: 400px;
            height: 400px;
            position: relative;
            background: yellow;
        }

        .tile {
            width: 100px;
            height: 100px;
            position: absolute;
            background-color: pink;
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

        br {
            height: 100px;
            background-color: red;
        }

        #animate {}
    </style>
</head>

<body>

    <h1 onclick="test()" id="h">Suwmania</h1>

    <button onclick="mix()" id="start">Start!</button>
    <br><br>

    <div id="container">
    </div>

    <script>
        // var posY = 0;
        // var posX = 0;
        var rows = 4;
        var cols = 4;
        var tileSize = 100;
        var posGap = [(cols - 1) * tileSize, (rows - 1) * tileSize];

        drawBoard();

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
            //let moves = 20;
            let list = document.getElementsByClassName("tile");
            //document.getElementById("h").innerHTML = test1();
            //for (let move = 0; move < moves; move++) {

            //if (dir = 0 && )
            let dir = Math.floor(Math.random() * 4); //0 - top, 1 - right, 2 - bottom, 3 - left
            //for (var n = 0; n < list.length; n++){
            if (dir === 0) {
                for (var n = 0; n < list.length; n++) {
                    if (posGap[0] === parseInt(list[n].style.left) && posGap[1] === parseInt(list[n].style.top) + tileSize) {
                        document.getElementById("h").innerHTML = list[n].innerHTML;
                    }
                }
            } else if (dir === 1) {
                for (var n = 0; n < list.length; n++) {
                    if (posGap[0] === parseInt(list[n].style.left) - tileSize && posGap[1] === parseInt(list[n].style.top)) {
                        document.getElementById("h").innerHTML = list[n].innerHTML;
                    }
                }
            } else if (dir === 2) {
                for (var n = 0; n < list.length; n++) {
                    if (posGap[0] === parseInt(list[n].style.left) && posGap[1] === parseInt(list[n].style.top) - tileSize) {
                        document.getElementById("h").innerHTML = list[n].innerHTML;
                    }
                }
            } else if (dir === 3) {
                for (var n = 0; n < list.length; n++) {
                    if (posGap[0] === parseInt(list[n].style.left) + tileSize && posGap[1] === parseInt(list[n].style.top)) {
                        document.getElementById("h").innerHTML = list[n].innerHTML;
                    }
                }
            }
        }




        function myMove(elem) {
            let id = null;
            let posLeft = parseInt(elem.style.left);
            let posTop = parseInt(elem.style.top);
            let moveDir = whereToGo();

            function whereToGo() {
                if (posLeft + tileSize === posGap[0] && posTop === posGap[1]) {
                    posGap = [posLeft, posTop];
                    return [5, 0];
                } else if (posLeft === posGap[0] && posTop + tileSize === posGap[1]) {
                    posGap = [posLeft, posTop];
                    return [0, 5];
                } else if (posLeft - tileSize === posGap[0] && posTop === posGap[1]) {
                    posGap = [posLeft, posTop];
                    return [-5, 0];
                } else if (posLeft === posGap[0] && posTop - tileSize === posGap[1]) {
                    posGap = [posLeft, posTop];
                    return [0, -5];
                }
                return [0, 0];
            }

            // clearInterval(id);
            id = setInterval(frame, 1);

            function frame() {
                posLeft += moveDir[0];
                posTop += moveDir[1];
                elem.style.left = posLeft + "px";
                elem.style.top = posTop + "px";
                // elem.innerHTML = elem.style.top;
                if (posTop % tileSize == 0 && posLeft % tileSize == 0) {
                    clearInterval(id);
                    testSolve();
                }
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

        function test() {}
    </script>

</body>

</html>