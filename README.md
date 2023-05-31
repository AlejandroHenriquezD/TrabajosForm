![](./login/cu.png)

# Introducción

La aplicación surge por la necesidad de digitalizar el proceso de realizar un pedido a serigrafía por lo tanto el objetivo sera realizar una app para gestionar toda la información necesaria a la hora de hacer los pedidos y un formulario para hacer los pedidos, asi como una gestion de los pedidos enviados para que serigrafia los pueda controlar.


# Manual de Usuario 

Una vez iniciada la aplicación lo primero que se mostrará será una pantalla de login en la cual podremos elegir si queremos entrar sin iniciar sesión o con un usuario y una contraseña. Entremos de la forma que entremos lo primero que veremos será la tabla de clientes de la que hablaremos más tarde.

![Imagen](./screenshoots/pantallalogin.png)

Esta es la pantalla de login de la aplicación.

## Serigrafía 

Serigrafía entrará con usuario y contraseña ya que tendrá unos permisos que no conviene que otros usuarios tengan.

Nada más entrar, a la derecha de la pantalla tendremos un menú que nos permitirá desplazarnos entre las diferentes secciones de la aplicación.

![Imagen](./screenshoots/menulateral.png)

La primera pantalla que nos aparece al entrar es en la que se muestran los clientes. Nada más llegar no nos aparecerá ningún resultado, pero podremos filtrar dichos clientes según su CIF/NIF, su código de cliente o su razón social.

![Imagen](./screenshoots/menuclientes.png)

Cuando encontremos el cliente deseado podemos hacer click en él y entrar para ver sus todos sus datos.

![Imagen](./screenshoots/datoscliente.png)

En este menú podremos ver, además de los datos del cliente, sus logotipos, bocetos y ordenes de trabajo. También podremos editar la información del usuario y añadir logotipos y bocetos.

En la pantalla de editar cliente podremos modificar cualquier dato salvo el CIF/NIF.

![Imagen](./screenshoots/editarcliente.png)

Al hacer click en el botón "Subir logo" se abrirá un selector de archivos en el que podremos elegir la imagen que queremos subir.

![Imagen](./screenshoots/subirlogo.png)

Una vez subido el logo podremos editar su estado de "activo" a "obsoleto" y viceversa y añadir una imagen vectorizada

![Imagen](./screenshoots/tablalogos.png)

Siguiendo el mismo procedimiento podemos subir un boceto el cuál debe tener formato pdf. Una vez subido tendremos la opción de ver dicho pdf y añadir una versión firmada del mismo boceto.

![Imagen](./screenshoots/subirboceto.png)

En cuanto a la tabla de ordenes de trabajo, la información se introduce en otra sección. Volveremos aquí cuando se inserten datos para comprobarlo.

![Imagen](./screenshoots/tablaordenesserigrafia.png)

En la opción "Nuevo Trabajo" del menú lateral podremos elegir un pedido y generar una orden de trabajo eligiendo para cada artículo el tipo de artículo que es, la posición en la que queremos el logo, el tipo de trabajo que queremos realizar y el logotipo que deseamos. En la parte inferior hay una caja de texto en la que podremos agregar observaciones extra al pedido. En el caso de seleccionar un logo nulo deberemos obligatoriamente especificar lo que deseamos trabajar.

Arriba a la derecha hay un menú que nos indica si falta algún dato por concretar en el formulario. Debajo de este, hay otro menú que nos permite seleccionar un boceto para asociarlo a esta orden de trabajo y previsualizarlo. Sin embargo, no es obligatorio ya que como veremos lo podremos asociar más tarde.

El usuario de serigrafía no puede enviar ordenes de trabajo, pero si puede usarlo para hacer pruebas.

![Imagen](./screenshoots/formularioseri.png)

En la sección "Posiciones", podremos crear nuevas posiones en las que podremos 

![Imagen](./screenshoots/tablaposiciones.png)



## Tienda


# Estructura del código

Para explicar la estructuración del codigo iremos hablando en profundidad de lo que contiene cada carpeta

## BDReal
En esta carpeta se encuentran todas las peticiones que se realizan a la base de datos principal de Central Uniformes y algunos archivos adicionales con el objetivo de testear posibles errores

![screenshots](FOTO CARPETA BDReal)

## CRUDS
En esta carpeta podemos ver toda la parte en la que se realizan los mantenimientos / gestión de todas las entidades de nuestra base de datos, cabe destacar que algunos archivos que deberian estar en esta carpeta han sido movidos a la carpeta raiz con motivos de facilitar el envio de imagenes y pdfs a la base de datos

![screenshots](FOTO CARPETA CRUDS)

## FPDF
Esta carpeta es una libreria / extensión de php la cual nos permite generar documentos pdf personalizados, los cuales usamos a la hora de generar una orden de trabajo de un pedido. En cuanto a su estructura gran parte es prescindible ya que en su mayoria se trata de documentación y tutoriales.

![screenshots](FOTO CARPETA fpdf)

## Frontend
Aqui encontraremos el otro pilar de nuestra aplicación junto con 'CRUDS' en esta carpeta se encuentra todo lo relacionado con el formulario. La estructura del formulario esta divida en varios archivos para facilitar su gestión y entendimiento.

![screenshots](FOTO CARPETA Frontend)

## Login
En esta carpeta se encuentra todo lo relacionado con la sesión ya que al existir dos posibles usuarios en nuestra app (Tienda o Serigrafia) estos tendran diferentes funciones habilitadas o no para simplificar su uso lo mas que podamos haciendo asi que cada rol solo pueda ver / interactuar con aquello que deberia poder ver / hacer

![screenshots](FOTO CARPETA Login)

## Uploads
Por ultimo la carpeta uploads la cual no esta en el repositorio debido a que es la que contiene los ficheros que se van subiendo a la apliación, asi como los logos, bocetos, ordenes de trabajo etc. Por ello es importante que usar la aplicación creemos en la raiz del proyecto una carpeta de nombre 'uploads'


## Carpeta Raíz
Los archivos que estan en la carpeta raiz son los que se usan para añadir archivos a la base de datos