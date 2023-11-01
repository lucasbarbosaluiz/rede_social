<?php
	
	namespace socialmedia\Controllers;

	class RegistrarController{


		public function index(){

			if(isset($_POST['registrar'])){
				$nome = $_POST['nome'];
				$email = $_POST['email'];
				$senha = $_POST['senha'];

				if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
					\socialmedia\Utilidades::alerta('E-mail Inválido.');
					\socialmedia\Utilidades::redirect(INCLUDE_PATH.'registrar');
				}else if(strlen($senha) < 6){
					\socialmedia\Utilidades::alerta('Sua senha é muito curta.');
					\socialmedia\Utilidades::redirect(INCLUDE_PATH.'registrar');
				}else if(\socialmedia\Models\UsuariosModel::emailExists($email)){
					\socialmedia\Utilidades::alerta('Este e-mail já existe no banco de dados!');
					\socialmedia\Utilidades::redirect(INCLUDE_PATH.'registrar');
				}else{
					//Registrar usuário.
					$senha = \socialmedia\Bcrypt::hash($senha);
					$registro = \socialmedia\MySql::connect()->prepare("INSERT INTO usuarios VALUES (null,?,?,?,'')");
					$registro->execute(array($nome,$email,$senha));

					\socialmedia\Utilidades::alerta('Registrado com sucesso!');
					\socialmedia\Utilidades::redirect(INCLUDE_PATH);
				}


			}
			
			\socialmedia\Views\MainView::render('registrar');
			

		}

	}

?>