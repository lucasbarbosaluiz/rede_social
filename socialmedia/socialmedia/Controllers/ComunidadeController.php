<?php
	
	namespace socialmedia\Controllers;

	class ComunidadeController{


		public function index(){
			if(isset($_SESSION['login'])){

				if(isset($_GET['solicitarAmizade'])){
					$idPara = (int) $_GET['solicitarAmizade'];
					if(\socialmedia\Models\UsuariosModel::solicitarAmizade($idPara)){
						\socialmedia\Utilidades::alerta('Amizade solicitada com sucesso!');
						\socialmedia\Utilidades::redirect(INCLUDE_PATH.'comunidade');
					}else{
						\socialmedia\Utilidades::alerta('Ocorreu um erro ao solicitar a amizade...');
						\socialmedia\Utilidades::redirect(INCLUDE_PATH.'comunidade');
					}
				}

			\socialmedia\Views\MainView::render('comunidade');
			}else{
				\socialmedia\Utilidades::redirect(INCLUDE_PATH);
			}
			
		}

	}

?>