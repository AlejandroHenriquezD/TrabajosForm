![](./login/cu.png)

# Introducción

La aplicación surge por la necesidad de digitalizar el proceso de realizar un pedido a serigrafía por lo tanto el objetivo sera realizar una app para gestionar toda la información necesaria a la hora de hacer los pedidos y un formulario para hacer los pedidos, asi como una gestion de los pedidos enviados para que serigrafia los pueda controlar.


# Manual de Usuario 

## Serigrafía 

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