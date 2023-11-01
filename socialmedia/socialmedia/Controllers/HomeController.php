<?php
	
	namespace socialmedia\Controllers;

	class HomeController{


		public function index(){

			if(isset($_GET['loggout'])){
				session_unset();
				session_destroy();

				\socialmedia\Utilidades::redirect(INCLUDE_PATH);
			}


			if(isset($_SESSION['login'])){
				//Renderiza a home do usuário.

				//Existe pedido de amizade?

				if(isset($_GET['recusarAmizade'])){
					$idEnviou = (int) $_GET['recusarAmizade'];
					\socialmedia\Models\UsuariosModel::atualizarPedidoAmizade($idEnviou,0);
					\socialmedia\Utilidades::alerta('Amizade Recusada :(');
					\socialmedia\Utilidades::redirect(INCLUDE_PATH);
				}else if(isset($_GET['aceitarAmizade'])){
					$idEnviou = (int) $_GET['aceitarAmizade'];
					if(\socialmedia\Models\UsuariosModel::atualizarPedidoAmizade($idEnviou,1)){
					\socialmedia\Utilidades::alerta('Amizade aceita!');
					\socialmedia\Utilidades::redirect(INCLUDE_PATH);
					}else{
					\socialmedia\Utilidades::alerta('Ops.. um erro ocorreu!');
					\socialmedia\Utilidades::redirect(INCLUDE_PATH);
					}
				}


				//Existe postagem no feed?


				if(isset($_POST['post_feed'])){

					if($_POST['post_content'] == ''){
						\socialmedia\Utilidades::alerta('Não permitimos posts vázios :(');
						\socialmedia\Utilidades::redirect(INCLUDE_PATH);
					}

					\socialmedia\Models\HomeModel::postFeed($_POST['post_content']);
					\socialmedia\Utilidades::alerta('Post feito com sucesso!');
					\socialmedia\Utilidades::redirect(INCLUDE_PATH);
				}


				\socialmedia\Views\MainView::render('home');
			}else{
				//Renderizar para criar conta.

				if(isset($_POST['login'])){
					$login = $_POST['email'];
					$senha = $_POST['senha'];

					

					//Verificar no banco de dados.

					$verifica = \socialmedia\MySql::connect()->prepare("SELECT * FROM usuarios WHERE email = ?");
					$verifica->execute(array($login));



					
					if($verifica->rowCount() == 0){
						//Não existe o usuário!
						\socialmedia\Utilidades::alerta('Não existe nenhum usuário com este e-mail...');
						\socialmedia\Utilidades::redirect(INCLUDE_PATH);
					}else{
						$dados = $verifica->fetch();
						$senhaBanco = $dados['senha'];
						if(\socialmedia\Bcrypt::check($senha,$senhaBanco)){
							//Usuário logado com sucesso
							
							$_SESSION['login'] = $dados['email'];
							$_SESSION['id'] = $dados['id'];
							$_SESSION['nome'] = explode(' ',$dados['nome'])[0];
							$_SESSION['img'] = $dados['img'];
							\socialmedia\Utilidades::alerta('Logado com sucesso!');
							\socialmedia\Utilidades::redirect(INCLUDE_PATH);
						}else{
							\socialmedia\Utilidades::alerta('Senha incorreta....');
							\socialmedia\Utilidades::redirect(INCLUDE_PATH);
						}
					}
					

				}

				\socialmedia\Views\MainView::render('login');
			}

		}

	}

?>