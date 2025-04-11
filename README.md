# gestor-tareas
Se trata de una aplicación web que permita a los usuarios registrarse, iniciar sesión y gestionar una lista de tareas personales. Cada usuario podrá crear, ver, modificar y eliminar sus propias tareas.

Comandos Docker

cd "ruta_De_la_carpeta"
docker-compose up -d (Construir e iniciar los contenedores)
docker-compose up --build (Instala también lo necesario)
docker ps (ver los contenedores en ejecución)

docker-compose down (Para detener y eliminar los contenedores)


docker-compose.yml
Es el archivo de configuración para Docker del proyecto
Define puertos para la web y la base de datos
Establece credenciales de acceso para la base de datos


(Es necesario haber insertado anteriormente los comandos de docker)
Para ejecutar la web hay que iniciar el servidor apache
del xampp, y en el navegador insertar:
http://localhost:8000/

Es el puerto 8000 ya que está así configurado.