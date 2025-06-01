# EpayCo Prueba tecnica fullstack PHP React



Archivo compuesto por 3 carpetas

 - soap-server
 - wallet-rest
 - wallet-frontend

 # Soap server

-   Laravel 12
-   Mysql
-   ORM Doctrine
-   Arquitectura DDD El core del sistema esta en la carpeta  "SRC"
    - Src/Domain/: Se encuentran las entidades de doctrine "Client" y "Wallet" "Transaction" (esta ultima es una entidad debil solo tiene interface no servicio) con sus intefaces y sus servicios respectivos
    - Src/Application/UseCases: se encuentran los casos de uso cada paso en la prueba
        - Registro de cliente
        - Cargar saldo a la wallet
        - Iniciar compra
        - Confirmar compra
        - Obtener balance de wallet
        - Obtener transacciones de la wallet
    - Src/Infraestructure/Persistence/Doctrine: se encuentran los repositories de las entidades encargados de modificar la base de datos
    - Src/Interface/SOAP: Contiene los controladores de los servicios del soap.
        

Intalacion 
- Clonar repositorio y entrar a la carpeta soap-server
- Correr composer install
- Correr php artisan migrate
- Correr php artisan serve para levantar servidor
- Configurar env con credenciales de correo para envios de token y confonfirmacion de compra 
- agregar varaiable FRONTEND_URL en env FRONTEND_URL = http://localhost:5175
- Se puede verificar los test con el comando php artisan test

# Wallet-Rest

-   Laravel Framework Lumen 10
-   Servicios
    - Registro de cliente 
    - Carga de saldo billetera
    - Iniciar compra
    - Confirmar pago
    - Obtner saldo
    - Obtener transacciones        

Intalacion 
- Clonar repositorio y entrar a la carpeta wallet-rest
- Correr composer install
- Correr  php -S localhost:8010 -t public para levantar servidor



# Wallet-Frontend

- React 19.1.0
- Vite
- Interfaz con menu

Instalacion
- Clonar repositorio y entrar a la carpeta wallet-frontend
- correr npm install para las dependencias
- crear archivo .env y agregar variable de entorno VITE_API_BASE_URL=http://localhost:8010/
    - favor de cambiar el puerto si es que el proyecto de rest esta corriendo en uno diferente al 8010



# Notas:

Se agrega colleccion de postman para servicios SOAP  y REST

## Authors

- Jorge Cortes Lopez [https://www.linkedin.com/in/jorhcl/]

