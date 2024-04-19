$(document).ready(() => {

    $("#documentation-id").on("click", ()=> {
        $.get("documentation.html", data => {
            $("#pagina").html(data)
        })
    })

    $("#suport-id").on("click", ()=> {
        $.post("suport.html", data => {
            $("#pagina").html(data)
        })
    })


    $("#competencia").on("change", event => {
        
        this.competencia = $(event.target).val()

        $.ajax({
            type: 'GET',
            url: '../php/app.php',
            data: `competencia=${this.competencia}`,
            dataType: 'json',
            success: dados => {
                $('#numeroVendas').html(dados.numeroVendas)
                $('#totalVendas').html(dados.totalVendas)
            },
            error: erro => console.log(error)
        })
//        console.log();
    })

})

