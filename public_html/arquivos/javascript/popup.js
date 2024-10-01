function abrirPopup_id(id_popup){

    document.querySelector('#'+id_popup).style.display = 'block';
    document.querySelector('#'+id_popup+'Fundo').style.display = 'block';
}

function fecharPopup_id(id_popup){

    document.querySelector('#'+id_popup).style.display = 'none';
    document.querySelector('#'+id_popup+'Fundo').style.display = 'none';
}