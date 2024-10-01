<style>
.boletim_interno {
    background: #38b0e4;
}

.boletim_interno .textos {
    font-size: 20px;
    color: #fff;
    /* font-weight: bold; */
    margin: 4px 28px;
}

.boletim_interno .input_e_btn {
    display: flex;
    flex-wrap: wrap;
	justify-content: center;
	position: relative;
}

.boletim_interno .inputbox.bol_inf_txt {
    width: 430px;
    border-radius: 4px;
}

.boletim_interno .btn {
    padding: 9px 22px;
    font-size: 17px;
    position: absolute;
    right: -4px;
    background: #000;
}

@media( max-width: 767px){
	.boletim_interno .btn {
    	right: 9px;
	}
	.boletim_interno .inputbox.bol_inf_txt {
		width: 100%;
	}

	.boletim_interno .input_e_btn {
		width: 100%;
		padding: 0 10px;
		position: relative;
	}
}
</style>
<form name="formBoletim" class="relative dtable porc100 boletim_interno" id="boletim_form" action="" method="post">
	<div style="display: flex;justify-content: center;flex-wrap: wrap;padding: 15px 0;">
		<div class="textos">
			<div class="txt1">Be the first to know about offers! </div>
		</div>
		<div class="input_e_btn">
			<input type="text" name="email" class="inputbox bol_inf_txt" placeholder="Email">
			<span title="Boletim Informativo" class="btn maozinha" onclick="salvar_bi();">register</span>
		</div>
	</div>
</form>

<script>
    function salvar_bi(){
        var f = document.formBoletim;

        if(!validarEmail(f.email.value)){
            return exibirAviso('informe o email corretamente');
        }
        carregando();

        jQuery.ajax({
            url: '/site/Site_controller_paginas/cadastrar_boletim',
            type: 'POST',
            dataType: 'json',
            data: {
                email: f.email.value
            },
            error: function() {},
            success: function(res) {
                exibirAviso('Cadastrado com sucesso','ok');

                carregado();
            }
        });        
    }
</script>