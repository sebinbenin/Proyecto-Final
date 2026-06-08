function mostrarMenu(){
    let x = document.getElementById("nav");
        if (x.className === "encabezados") {
            x.className += "responsive";
        }else{
            x.className = "encabezados";
    }
}