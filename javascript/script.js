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
                $('#clientesAtivos').html(dados.clientesAtivos)
                $('#clientesInativos').html(dados.clientesInativos)
                $('#totalReclamantes').html(dados.totalReclamantes)
                $('#totalFeliz').html(dados.totalFeliz)
                $('#totalSugestoes').html(dados.totalSugestoes)
                $('#totalPerdas').html(dados.totalPerdas)
            },
            error: erro => console.log(error)
        })

    })

})

