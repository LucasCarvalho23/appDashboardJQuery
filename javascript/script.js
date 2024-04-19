$(document).ready(() => {

    $("#documentation-id").on("click", ()=> {
        $("#pagina").load('documentation.html')
    })

    $("#suport-id").on("click", ()=> {
        $("#pagina").load('suport.html')
    })

})

