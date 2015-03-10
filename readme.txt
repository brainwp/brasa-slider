=== Plugin Name ===
Contributors: matheusgimenez
Donate link: http://codeispoetry.info/donate
Tags: slider, brasa, post slider
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 3.9.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a Slider with images, posts and pages to your pages with an easy-to-use shortcode ([brasa_slider name="slider_name"]) or directly on the template using: echo do_shortcode('[brasa_slider name="slider_name"]');


== Description ==
After installing the plugin go to your dashboard and create a new slider. You have the possibility to search through your posts and pages in order to add them or add an image.

You can add an excerpt and the categories of the post/page by placing something like this on your functions.php:

function slider_resumo($str){
  	global $brasa_slider_id, $brasa_slider_item_id;
	
    
  if(get_the_title($brasa_slider_id) != 'home'){
	return $str;
	}
 else{

    	$the_post = get_post($brasa_slider_item_id);
		$category = get_the_category($brasa_slider_item_id); 
		$ultimo = end($category);
		foreach ($category as $categoria){
			$categoria_html .= '<h3><a href="'.get_category_link($categoria->term_id ).'"> '.$categoria->cat_name;
			if ($categoria != $ultimo){
				$categoria_html .=", ";
			}
			$categoria_html .='</a></h3>';
		}
		
		$permalink = get_permalink( $brasa_slider_item_id);
		$str .= "<div class='excerpt-slider'><h2>".$the_post->post_title."</h2>".$categoria_html."<p>".$the_post->post_excerpt."</p></div><a href=".$permalink."' class='bt-readmore'>Leia Mais</a>";
		return $str;
	}
}
add_filter('brasa_slider_loop_after_image','slider_resumo');


You can also change the image size using the brasa_slider_img_size with a previously created image size:

function size($size){
	$size="thumb-destaques"; ///change thumb_destaque with your image size
	return $size;
}
add_filter('brasa_slider_img_size','size');


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
PT-BR:
Adicione um slider com imagens, posts ou páginas em suas páginas com um simples shortcode ([brasa_slider name="nome_do_slider"]) r ou diretamente no template da página usando: echo do_shortcode('[brasa_slider name="nome_do_slider"]');


== Descrição ==
Após instalar o plugin va ao Painel do seu Site e crie um novo slider. Você tem a possibilidade de buscar entre seus posts e páginas para adiciona-los ou adicionar uma imagem.
Você pode adicionar o resumo e as categorias do post/página adicionando a seguinte função no functions.php:.php:

function slider_resumo($str){
  	global $brasa_slider_id, $brasa_slider_item_id;
	
    
  if(get_the_title($brasa_slider_id) != 'home'){
	return $str;
	}
 else{
		
    	$the_post = get_post($brasa_slider_item_id);
		$category = get_the_category($brasa_slider_item_id); 
		$ultimo = end($category);
		foreach ($category as $categoria){
			$categoria_html .= '<h3><a href="'.get_category_link($categoria->term_id ).'"> '.$categoria->cat_name;
			if ($categoria != $ultimo){
				$categoria_html .=", ";
			}
			$categoria_html .='</a></h3>';
		}
		
		$permalink = get_permalink( $brasa_slider_item_id);
		$str .= "<div class='excerpt-slider'><h2>".$the_post->post_title."</h2>".$categoria_html."<p>".$the_post->post_excerpt."</p></div><a href=".$permalink."' class='bt-readmore'>Leia Mais</a>";
		return $str;
	}
}
add_filter('brasa_slider_loop_after_image','slider_resumo');

Você também pode mudar o tamanho das imagens utilizando um tamanho previamente criado  (add_image_size):

function size($size){
	$size="thumb-destaques"; ///change thumb_destaque with your image size
	return $size;
}
add_filter('brasa_slider_img_size','size');