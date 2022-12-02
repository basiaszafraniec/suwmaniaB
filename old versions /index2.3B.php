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

    <!-- <form action="phpindex.php" onsubmit="test()">
        <input type="text" id="inputName">
    </form> -->

    <button onclick="start()" id="start">START</button><br>

    <button onclick="test()" id="testButton">test</button><br>

    <div id="container">
    </div>

    <div>
        <h2 id="count">0</h2>
    </div>


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


        document.getElementById("container").style.width = (rows * tileSize) + "px";
        document.getElementById("container").style.height = (cols * tileSize) + "px";

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
            document.getElementById('container').innerHTML += text;
        }

        function start() {
            document.getElementById('start').style.backgroundColor = "lightgrey";
            document.getElementById('start').style.borderColor = "grey";

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
                }
            }
        }

        function myMove(elem) {
            if (ready) {
                let x = whichTilesWhere(elem)
                if (x[0].length > 0) {
                    moveTile(x[0], x[1], elem);
                    myMoves++;
                    document.getElementById("count").innerHTML = myMoves;
                }
            }
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
                for (let t = 0; t < list.length; t++) {
                    list[t].innerHTML = winTxt.charAt(t);
                }
            }
        }

        function test() {
            // winner = {
            //     name: "Szymon",
            //     age: "69",
            //     score: "200"
            // };

            // document.getElementById("h").innerHTML = document.getElementById("inputName").value;

            // const xhttp = new XMLHttpRequest();
            // xhttp.onload = function() {
            //     myFunction(this);
            // }
            // xhttp.open("GET", "highscore.json");
            // xhttp.send();


            // function myFunction(json) {
            //     const jsonDoc = json.responseText;
            //     // const x = jsonDoc.getElementsByTagName("CD");
            //     // let table = "<tr><th>Artist</th><th>Title</th></tr>";
            //     // for (let i = 0; i < x.length; i++) {
            //     //     table += "<tr><td>" +
            //     //         x[i].getElementsByTagName("ARTIST")[0].childNodes[0].nodeValue +
            //     //         "</td><td>" +
            //     //         x[i].getElementsByTagName("TITLE")[0].childNodes[0].nodeValue +
            //     //         "</td></tr>";
            //     // }
            //     document.getElementById("h").innerHTML = jsonDoc;
            // }


            // document.getElementById("h").innerHTML = JSON.stringify(winner);

            // try {
            //     var xmlhttp = new XMLHttpRequest();
            //     xmlhttp.open("POST", "phpindex.php", true);
            //     xmlhttp.onreadystatechange = function() {
            //         if (xmlhttp.readyState !== 4) return;
            //     };
            //     xmlhttp.send(JSON.stringify(winner));
            // } catch (ex) {
            //     alert('Błąd zapisu!');
            // }


            // const myObject = {name:Basia, age:19};
            // const myJSON = JSON.stringify(myObject);
            // window.location = "highscore.json" + myJSON;


            // // xmlhttp.onload = function(){
            // //     document.getElementById("h").innerHTML = this.responseText;
            // // }


            // var xmlhttp = new XMLHttpRequest();
            // xmlhttp.open("POST","highscore.json",true);
            // xmlhttp.onreadystatechange = function() {
            //     if (this.readyState == 4 && this.status == 200) {
            //         document.getElementById("h").innerHTML = this.responseText;
            //     }
            // }
            // xmlhttp.send(("gysgd"));

        }
    </script>

</body>

</html>