function mostrarTabla(mes) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("resultado").innerHTML = xmlhttp.responseText;
        }
    }

    xmlhttp.open("GET", `procesar.php?opcion=mostrarTabla&mes=${mes}`, true);
    xmlhttp.send();
    
    console.log(mes);
}

function calcular() {  
    var sueldo = document.getElementById("SaldoBruto").value;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("resultadoCalculo").innerHTML = xmlhttp.responseText;
        }
    }

    // Actualiza la URL a un archivo PHP
    xmlhttp.open("GET", `./procesar.php?opcion=calcular&saldoBruto=${sueldo}`, true);
    xmlhttp.send();

    console.log(sueldo);
}
