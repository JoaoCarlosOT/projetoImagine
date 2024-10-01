<?php
	// Impede o acesso direto a este arquivo
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	// Classe de imagens
	class Imgno_imagem {
	
        public function otimizar($caminho, $width = 0, $height = 0, $transparente = false, $cortar = true, $qualidade = 80, $tipo_corte = 'cc'){
            $root = $_SERVER['DOCUMENT_ROOT'];
            $ori_caminho = $caminho;
            if(!$caminho) return ($caminho);
            $c = '';
            $f = '';
            $cc = strlen($caminho);
            $nkey = 0;
            for($i = $cc; $i >= 0; $i--){

                if(isset($caminho[$i]) && $caminho[$i] == '/'){
                    $nkey = $i;
                    break;
                }
            }
           
            $nkey++;
            for($i = 0; $i < $nkey; $i++){ 
                $c .= $caminho[$i];
            }
            for($i = $nkey; $i < $cc; $i++){ 
                $f .= $caminho[$i];
            }
            $caminho = $c;
            $foto = $f;

            if(!$nkey) return ($caminho.$foto);
    
            if($caminho[0] != '/') $caminho = '/'.$caminho;
            if(!file_exists($caminho . $foto)) $foto = ''.urldecode($foto);
            if(!file_exists($root . $caminho . $foto)) return ($ori_caminho);
            $info = pathinfo($caminho . $foto);
            $ext = strtolower($info['extension']);
            $jr_caminho = $root . $caminho;
            $size = getimagesize($jr_caminho . $foto);
            if(!$info) return ($caminho.$foto);
            $t = 'n';
            $c = 'n'; 
            
            $new_caminho = '/cache'.$caminho;
            $new_jr_caminho = $root . $new_caminho;
            
            $o_w = $size[0];
            $o_h = $size[1];
            $metodo = 0;
            if($cortar && $width && $height){ 
                // $c = 'y';
                $c = $tipo_corte;
                $metodo = 1;
            }
            if(!$width && $height) $width = ($height*$o_w)/$o_h;
            if($width && !$height) $height = ($width*$o_h)/$o_w;
            if(!$width && !$height){
                $width = $o_w;
                $height = $o_h;
            }
            if($o_h < $height && $o_w < $width){
                if(number_format($width/$o_w,3) == number_format($height/$o_h,3)){
                    $width = $o_w;
                    $height = $o_h;
                }
            }
            
            $formato = 'jpg';
            if($transparente) $formato = 'png';
            if($transparente) $t = 'y';
            $nome = $info['filename'].'_'.$width.$t.$qualidade.$c.$height;
            $nome_ext = $nome.'.'.$formato;
    
            if(image_type_to_mime_type($size[2]) == 'image/gif') return ($caminho.$foto);
    
            if(file_exists($new_jr_caminho .$nome_ext)){
                if(filemtime($root.$caminho.$foto) <= filemtime($root.$new_caminho.$nome_ext)) return ($new_caminho.$nome_ext);
            }
            if(!file_exists($new_jr_caminho)){
                $n = explode('/',$new_jr_caminho);
                $np = '';
                foreach($n as $d){
                    $np .= '/'.$d;
                    if(!file_exists($np)){
                        mkdir($np, 0755);
                    }
                }
            }
            
            switch(image_type_to_mime_type($size[2])){
                case 'image/jpeg':
                case 'image/jpg': { $img = imagecreatefromjpeg( $jr_caminho . $foto); break; }
                case 'image/png': { $img = imagecreatefrompng( $jr_caminho . $foto); break; }
                case 'image/gif': { $img = imagecreatefromgif($jr_caminho . $foto); break; }
                default: return false; // 'Formato de imagem inváldo: '. $extensao;
            }
            
            // Verifica se há a necessidade de redimensionar a imagem
            if(($width == $o_w) && ($height == $o_h) && !$transparente && $qualidade == 100) return ($caminho.$foto);
            
            // Cria uma nova imagem( temporária ) e preenche com a cor branca ou com transparência
            
            $tmp_img = imagecreatetruecolor($width, $height);
            if($transparente){
                imagealphablending($tmp_img, false);
                imagefilledrectangle($tmp_img, 0, 0, $width, $height, imagecolorallocatealpha($tmp_img, 255, 255, 255, 127));
                imagealphablending($tmp_img, true);
            } else {
                imagefill($tmp_img, 0, 0, imagecolorallocate($tmp_img, 255, 255, 255));
            }
            
            // Copia a imagem original sobre a nova imagem, seguindo as dimensões adequadas
            $dim = $this->calcular_dimensoes($o_w, $o_h, $width, $height, $metodo);
    
            if($tipo_corte == 'cr'){
                $dim[2] = 0;
            }elseif($tipo_corte == 'cl'){
                $dim[2] = 2*$dim[2];
            }		
    
            imagecopyresampled($tmp_img, $img, $dim[0], $dim[1], $dim[2], $dim[3], $dim[4], $dim[5], $dim[6], $dim[7]);
            
            // Caso seja solicitada ima imagem com transparência, a extensão será sempre png
            if($transparente){
                imagealphablending($tmp_img, false);
                imagesavealpha($tmp_img, true);
                if($qualidade == 100) $qualidade = 0;
                else $qualidade = (100 - $qualidade)/10;
                imagepng($tmp_img, "{$new_jr_caminho}{$nome_ext}",$qualidade);
            } else {
                // Caso contrário, será jpg ou jpeg
                imagejpeg($tmp_img, "{$new_jr_caminho}{$nome_ext}",$qualidade);
            }
            
            // Destrói a imagem temporária
            imagedestroy($tmp_img);
            
            return ($new_caminho.$nome_ext);
        }

        public function otimizarFacebook($image, $nome_dir, $width = 0, $height = 0, $transparente = 0, $cortar = false, $resolucao = 80){            
                
            if(!$image) return '';
            
            $nome_dir = pathinfo($nome_dir);
            $novo_nome = $nome_dir['basename'];
            $diretorio = $nome_dir['dirname'].'/';
            
            $size = getimagesize($image);
            
            if(!$size) return '';

            if(!file_exists($diretorio)){
                mkdir($diretorio, 0777, true);
            }
            
            $width_ori = $size[0];
            $height_ori = $size[1];

            $metodo = 0;
            
            if($cortar && $width && $height){ 
                $metodo = 1;
            }
            if(!$width && $height) $width = ($height*$width_ori)/$height_ori;
            if($width && !$height) $height = ($width*$height_ori)/$width_ori;
            if(!$width && !$height){
                $width = $width_ori;
                $height = $height_ori;
            }
            if($height_ori < $height && $width_ori < $width){
                if(number_format($width/$width_ori,3) == number_format($height/$height_ori,3)){
                    $width = $width_ori;
                    $height = $height_ori;
                }
            }

            switch ($size['mime']) {
                case 'image/jpeg':
                case 'image/jpg':
                    
                    $imagem_temporaria = imagecreatefromjpeg($image);
                    $imagem_redimensionada = imagecreatetruecolor($width, $height);
                    
                    if($transparente){
                        
                        imagealphablending($imagem_redimensionada, false);
                        imagefilledrectangle($imagem_redimensionada, 0, 0, $width, $height, imagecolorallocatealpha($imagem_redimensionada, 255, 255, 255, 127));
                        imagealphablending($imagem_redimensionada, true);

                    }else{
                        imagefill($imagem_redimensionada, 0, 0, imagecolorallocate($imagem_redimensionada,255,255,255));
                    }
                    
                    $dim = $this->calcular_dimensoes($size[0], $size[1], $width, $height, $metodo);

                    imagecopyresampled($imagem_redimensionada, $imagem_temporaria, $dim[0], $dim[1], $dim[2], $dim[3], $dim[4], $dim[5], $dim[6], $dim[7]);
                    
                    

                    //imagecolortransparent($imagem_temporaria, imagecolorallocate($imagem_temporaria,255,255,255));


                    imagejpeg($imagem_redimensionada, $diretorio.'/'.$novo_nome, $resolucao);
                    imagedestroy($imagem_redimensionada);
                break;
                case 'image/png':
                case 'image/x-png';
                    
                    $imagem_temporaria = imagecreatefrompng($image);
                    $imagem_redimensionada = imagecreatetruecolor($width, $height);
                         
                    if($transparente){
                        
                        imagealphablending($imagem_redimensionada, false);
                        imagefilledrectangle($imagem_redimensionada, 0, 0, $width, $height, imagecolorallocatealpha($imagem_redimensionada, 255, 255, 255, 127));
                        imagealphablending($imagem_redimensionada, true);

                    }else{
                        imagefill($imagem_redimensionada, 0, 0, imagecolorallocate($imagem_redimensionada,255,255,255));
                    }

                    $dim = $this->calcular_dimensoes($size[0], $size[1], $width, $height, $metodo);

                    imagecopyresampled($imagem_redimensionada, $imagem_temporaria, $dim[0], $dim[1], $dim[2], $dim[3], $dim[4], $dim[5], $dim[6], $dim[7]);
                    
                    imagepng($imagem_redimensionada, $diretorio.'/'.$novo_nome);
                    imagedestroy($imagem_redimensionada);
                break;
                case 'image/gif':
                    $imagem_temporaria = imagecreatefromgif($image);
                    $imagem_redimensionada = imagecreatetruecolor($width, $height);

                    if($transparente){
                        
                        imagealphablending($imagem_redimensionada, false);
                        imagefilledrectangle($imagem_redimensionada, 0, 0, $width, $height, imagecolorallocatealpha($imagem_redimensionada, 255, 255, 255, 127));
                        imagealphablending($imagem_redimensionada, true);

                    }else{
                        imagefill($imagem_redimensionada, 0, 0, imagecolorallocate($imagem_redimensionada,255,255,255));
                    }

                    $dim = $this->calcular_dimensoes($size[0], $size[1], $width, $height, $metodo);

                    imagecopyresampled($imagem_redimensionada, $imagem_temporaria, $dim[0], $dim[1], $dim[2], $dim[3], $dim[4], $dim[5], $dim[6], $dim[7]);
                    imagegif($imagem_redimensionada, $diretorio.'/'.$novo_nome);
                    imagedestroy($imagem_redimensionada);
                break;
                default: return $image; 
            }

            return $novo_nome;
        }
        
        // Calcula as dimensões que devem ser utilizadas na criação da imagem
        private function calcular_dimensoes($src_w, $src_h, $dst_w, $dst_h, $method){
            // Razões entre as dimensões
            $div_w = $src_w / $dst_w;
            $div_h = $src_h / $dst_h;
            
            if($method == 0){
                // Centraliza a imagem original, sem redimensionar
                if(0 && ($src_w <= $dst_w) && ($src_h <= $dst_h)){
                    $dst_x = ($dst_w - $src_w) / 2;
                    $dst_y = ($dst_h - $src_h) / 2;
                }
                // Centraliza a imagem original após ampliar o máximo possível
                else if($div_w > $div_h){
                    $resize_w = $dst_w; // $src_w * $dst_w / $src_w;
                    $resize_h =			   $src_h * $dst_w / $src_w;
                    $dst_x = 0;
                    $dst_y = (int)(($dst_h - $resize_h) / 2);
                } else {
                    $resize_h = $dst_h; // $src_h * $dst_h / $src_h;
                    $resize_w =			   $src_w * $dst_h / $src_h;
                    $dst_y = 0;
                    $dst_x = (int)(($dst_w - $resize_w) / 2);
                }
                
                // Copia e redimensiona a imagem antiga no lugar da nova
                return array($dst_x, $dst_y, 0, 0, $dst_w - (2 * $dst_x), $dst_h - (2 * $dst_y), $src_w, $src_h);
            } elseif($method == 1){ // Corta
                if($div_w > $div_h){
                    $temp_h = $src_h;
                    $temp_w = $src_h * $dst_w / $dst_h;
                    $src_y = 0;
                    $src_x = (int)(($src_w - $temp_w) / 2);
                } else {
                    $temp_w = $src_w;
                    $temp_h = $src_w * $dst_h / $dst_w;
                    $src_x = 0;
                    $src_y = (int)(($src_h - $temp_h) / 2);
                }
                
                // Copia e redimensiona a imagem antiga no lugar da nova
                return array(0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w - (2 * $src_x), $src_h - (2 * $src_y));
            }
            // Estica
            else return array(0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        }
	}
?>