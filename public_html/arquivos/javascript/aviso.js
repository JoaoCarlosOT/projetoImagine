document.addEventListener("DOMContentLoaded", function(event) {
    // Gerar html aviso
    var divAviso = document.createElement('div');
    divAviso.setAttribute('id', 'im_aviso');
    
    var divAvisof1 = document.createElement('div');
    divAvisof1.setAttribute('id', 'bg_aviso');
    divAvisof1.setAttribute('onclick', 'ocultarAviso();');

    var divAvisof2 = document.createElement('div');
    divAvisof2.setAttribute('id', 'msg_aviso');
    divAvisof2.setAttribute('class', 'erro');

    divAviso.appendChild(divAvisof1);
    divAviso.appendChild(divAvisof2);

    document.querySelector('body').append(divAviso);
	var hash = (''+window.location.hash).replace("#erro", "").replace("#", "");
	if(hash.match(/\s/) || hash.match('%20')){
        var texto = decodeURI(hash);
        
        exibirAviso(texto, (window.location.hash.match('#erro')? 'erro' :'ok' ) );
        location.href="#";
    }

    // Gerar html carregando
    var divCarregando = document.createElement('div');
    divCarregando.setAttribute('id', 'telaCarregando');
    divCarregando.setAttribute('style', 'display:none;');
        var imgCarregando = document.createElement('img');
        imgCarregando.setAttribute('alt', 'Carregando');
        imgCarregando.setAttribute('title', 'Carregando');
        imgCarregando.setAttribute('src', '/arquivos/imagens/infinity-loader.gif');
    divCarregando.appendChild(imgCarregando);

    var txtCarregando = document.createElement('div');
    txtCarregando.setAttribute('class', 'txtCarregando');
    txtCarregando.innerHTML = "Aguarde...";
    divCarregando.appendChild(txtCarregando);

    document.querySelector('body').append(divCarregando);
});

function hasClass(ele,cls) {
    return !!ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}
  
function addClass(ele,cls) {
    if (!hasClass(ele,cls)) ele.className += " "+cls;
}
  
function removeClass(ele,cls) {
    if (hasClass(ele,cls)) {
      var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
      ele.className=ele.className.replace(reg,' ');
    }
}

function changeCampo(elemento){
   
    removeClass(elemento,"campo-valido");
    removeClass(elemento,"campo-invalido");
}

function campoValido(elemento){
    changeCampo(elemento);
    addClass(elemento,"campo-valido");
}

function campoInvalido(elemento){
    changeCampo(elemento);
    addClass(elemento,"campo-invalido");
}

function campoInfo(elemento,msg = "Campo inválido"){
    let firstChild = elemento.firstChild,
    html = '<div class="campoInfo info-invalido" id="campoInfo_'+elemento.name+'">'+msg+'</div>';

    elemento.insertBefore(html, firstChild);
}


function ocultarAviso(){
    document.querySelector('#msg_aviso').innerHTML = '';
    document.querySelector('#bg_aviso').style.opacity = 0;
        document.querySelector('#bg_aviso').style.display = 'none';
}

function exibirAviso(texto,tipo){
	if(texto == '') return ;
    if(!tipo)var tipo='erro';
    
    var spanAviso = document.createElement('span');
    spanAviso.setAttribute('class', 'aviso '+tipo);
    
        var a = document.createElement('a');
        a.setAttribute('class', 'botao');
        a.setAttribute('onclick', 'ocultarAviso();');
            var aSpanAvisof = document.createElement('span');
            aSpanAvisof.setAttribute('class', 'meio');
            aSpanAvisof.textContent = "Fechar";
        a.appendChild(aSpanAvisof);

        var spanAvisof = document.createElement('span');
        spanAvisof.setAttribute('class', 'text');
        spanAvisof.textContent = texto;

    spanAviso.appendChild(a);
    spanAviso.appendChild(spanAvisof);
	
	setTimeout(function(){
        document.querySelector('#bg_aviso').style.opacity = 0.6;
        document.querySelector('#bg_aviso').style.display = 'block';
                                        
        document.querySelector('#msg_aviso').append(spanAviso);
	}, 100);
}

function carregado(){
    document.querySelector('#telaCarregando').style.display = 'none';
}

function carregando(){
    document.querySelector('#telaCarregando').style.display = 'block';
}