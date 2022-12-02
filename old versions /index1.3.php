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
        }

        #animate {}
    </style>
</head>

<body>
    <h1>Suwmania</h1>

    <div id="container">
    </div>

    <script>
        var posY = 0;
        var posX = 0;
        var rows = 4;
        var cols = 4;
        var tileSize = 100;
        var posGap = [(cols - 1) * tileSize, (rows - 1) * tileSize];

        drawBoard(rows, cols);

        function drawBoard(r, c) {
            let text = '';
            let digitsToTxt = 1;
            for (let row = 0; row < r; row++) {
                for (let col = 0; col < c; col++) {
                    if (!(row == r - 1 && col == c - 1)) {
                        text += '<button class="tile" onclick="myMove(this)" style="top:' +
                            row * tileSize + 'px; left:' + col * tileSize + 'px;">' + digitsToTxt +
                            '</button>';
                        digitsToTxt++;
                    }

                }
            }
            document.getElementById('container').innerHTML = text;

        }

        function myMove(elem) {
            let id = null;
            let posLeft = Number(elem.style.left.slice(0, -2));
            let posTop = Number(elem.style.top.slice(0, -2));
            let moveDir = whereToGo(elem);

            function whereToGo(obj) {
                //alert(posGap);
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
                } else {
                    return [0, 0]
                }

            }




            clearInterval(id);
            id = setInterval(frame, 1);

            function frame() {
                posLeft += moveDir[0];
                posTop += moveDir[1];
                elem.style.left = posLeft + "px";
                elem.style.top = posTop + "px";
                elem.innerHTML = elem.style.top;
                if (posTop % tileSize == 0 && posLeft % tileSize == 0) {
                    clearInterval(id);
                }
            }
        }
    </script>

</body>

</html>