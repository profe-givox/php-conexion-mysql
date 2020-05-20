<?php
require_once 'Conexion.php'; /*importa Conexion.php*/
require_once '../modelo/Usuario.php'; /*importa el modelo */
class UsuarioDao
{
    
	private $conexion; /*Crea una variable conexion*/
        
    private function conectar(){
        try{
			$this->conexion = Conexion::abrirConexion(); /*inicializa la variable conexion, llamando el metodo abrirConexion(); de la clase Conexion por medio de una instancia*/
		}
		catch(Exception $e)
		{
			die($e->getMessage()); /*Si la conexion no se establece se cortara el flujo enviando un mensaje con el error*/
		}
    }
    
    
   /*Metodo que obtiene todos los usuarios de la base de datos, retorna una lista de objetos */
	public function obtenerTodos()
	{
		try
		{
            $this->conectar();
            
			$lista = array(); /*Se declara una variable de tipo  arreglo que almacenará los registros obtenidos de la BD*/
			$sentenciaSQL = $this->conexion->prepare("SELECT id_usuario,nombre,usuario,clave FROM USUARIOS"); /*Se arma la sentencia sql para seleccionar todos los registros de la base de datos*/
			
			$sentenciaSQL->execute();/*Se ejecuta la sentencia sql, retorna un cursor con todos los elementos*/
            
            /*Se recorre el cursor para obtener los datos*/
			foreach($sentenciaSQL->fetchAll(PDO::FETCH_OBJ) as $fila)
			{
				$obj = new Usuario();
                $obj->get_id = $fila->id_usuario;
                $obj->get_nombre = $fila->nombre;
	            $obj->get_usuario = $fila->usuario;
	            $obj->get_clave = $fila->clave;
	           
				$lista[] = $obj;
			}
            
			return $lista;
		}
		catch(Exception $e){
			echo $e->getMessage();
			return null;
		}
		finally
		{
            Conexion::cerrarConexion();
        }
	}
    
    /*Metodo que obtiene un registro de la base de datos, retorna un objeto */
	public function obtenerUno($id)
 	{
		try
 		{ 
            $this->conectar();
            
 			$registro = null; /*Se declara una variable  que almacenará el registro obtenido de la BD*/
            
 			$sentenciaSQL = $this->conexion->prepare("SELECT id_usuario,nombre,usuario,clave FROM USUARIOS WHERE id=?"); /*Se arma la sentencia sql para seleccionar todos los registros de la base de datos*/
 			$sentenciaSQL->execute([$id]);/*Se ejecuta la sentencia sql, retorna un cursor con todos los elementos*/
            
             /*Obtiene los datos*/
 			$fila=$sentenciaSQL->fetch(PDO::FETCH_OBJ);
			
             $registro = new Usuario();
             $registro->get_id = $fila->id_usuario;
             $registro->get_nombre = $fila->nombre;
             $registro->get_usuario = $fila->usuario;
             $registro->get_clave = $fila->clave;
           
                
		return $registro;
 		}
 		catch(Exception $e){
             echo $e->getMessage();
             return null;
	 }finally{
           Conexion::cerrarConexion();
      }
 	}


 		public function iniciarseccion($usuario,$clave)
 	{
		try
 		{ 
            $this->conectar();
            
 			$registro = null; /*Se declara una variable  que almacenará el registro obtenido de la BD*/
            
 			$sentenciaSQL = $this->conexion->prepare("SELECT USUARIO, CLAVE, NOMBRE FROM USUARIOS WHERE USUARIO=? AND CLAVE=MD5(?)"); /*Se arma la sentencia sql para seleccionar todos los registros de la base de datos*/
 			$sentenciaSQL->execute([$usuario,$clave]);/*Se ejecuta la sentencia sql, retorna un cursor con todos los elementos*/
            
             /*Obtiene los datos*/
 			$fila=$sentenciaSQL->fetch(PDO::FETCH_OBJ);
			
             $registro = new Usuario();

             if($fila!=null){
             $registro->USUARIO = $fila->USUARIO;
             $registro->CLAVE = $fila->CLAVE;
             $registro->NOMBRE = $fila->NOMBRE;
            
             }else{
             $registro->USUARIO = "";
             $registro->CLAVE = "";
             $registro->NOMBRE = "";
             }
          
                
		return $registro;
 		}
 		catch(Exception $e){
             echo $e->getMessage();
             return null;
	 }finally{
           Conexion::cerrarConexion();
      }
 	}
    
   



    //Elimina el Usuario con el id indicado como parámetro
      public function eliminar($id)
	{
		try 
		{
			$this->conectar();
            
           $sentenciaSQL = $this->conexion->prepare("DELETE FROM Usuario WHERE id_usuario = ?");			          
            
			$sentenciaSQL->execute(array($id));
           return true;
 		} catch (Exception $e) 
		{
             return false;
		}finally{
            Conexion::cerrarConexion();
       }
        
	}


	//Función para editar al Usuario de acuerdo al objeto recibido como parámetro
	public function editar(Usuario $obj)
	{
		try 
		{
			$sql = "UPDATE Usuarios SET 
                    nombre = ?,
                    usuario = ?,
                    clave = ?
 				    WHERE id_usuario = ?";
             $this->conectar();
            
             $sentenciaSQL = $this->conexion->prepare($sql);			          
			$sentenciaSQL->execute(

				array(	$obj->nombre,
					    $obj->usuario,
						$obj->clave)
 					);
             return true;
 		} catch (Exception $e){
 			echo $e->getMessage();
 			return false;
 		}finally{
          Conexion::cerrarConexion();
         }
	}


// Agrega un nuevo token de acuerdo al objeto recibido como parámetro
	public function agregar(Usuario $obj)
	{
        $ultimo=0;
 		try 
		{

            $sql = "INSERT INTO USUARIOS (nombre,usuario,clave) values(?, ?,MD5(?))";
            
             $this->conectar();
             $this->conexion->prepare($sql)
                 ->execute(
                array($obj->get_nombre(),
                	$obj->get_usuario(),
            	    $obj->get_clave()));

            $ultimo=$this->conexion->lastInsertId();

          
            return $obj;
 		} catch (Exception $e) 
		{
            $obj->set_usuario(null);
 			return $obj;
 		}finally{
            
            /*
            En caso de que se necesite manejar transacciones, no deberá desconectarse mientras la transacción deba persistir
            */
             Conexion::cerrarConexion();
        }
 	}


 }
