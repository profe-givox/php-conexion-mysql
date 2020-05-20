<?php
	
	class Usuario
	{
		//Definiendo atributos
		private $id;
		private $nombre;
		private $usuario;
		private $clave;
		


		function __construct1(){}

		function __construct2($nombre,$usuario,$clave)
		{
			$this->nombre=$nombre;
			$this->usuario=$usuario;
			$this->clave=$clave;
			
		}

         public function set_nombre($nombre){
         $this->nombre=$nombre;
	     }

	     public function get_nombre(){
         return $this->nombre;
	     }

		 public function set_id($id){
         $this->id=$id;
	     }

	     public function get_id(){
         return $this->id;
	     }

	     public function set_usuario($usuario){
         $this->usuario=$usuario;
	     }

	     public function get_usuario(){
         return $this->usuario;
	     }
     

	     public function set_clave($clave){
         $this->clave=$clave;
	     }

	     public function get_clave(){
         return $this->clave;
	     }

	   


		
	}
?>
