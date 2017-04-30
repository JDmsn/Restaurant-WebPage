function update(url, id, mode, data){
    $.post(url,
        (data == undefined)?{
            id: id,
            mode: mode
        }:
        {
            id: id,
            mode: mode,
            data: data
        },
        function(data, status){
            if(status) {
                main.innerHTML = data;
                if(onRefresh != undefined)
                    onRefresh()
            }else{
                alert('Hubo un error en la conexión');
            }
        });
}