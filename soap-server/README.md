# EpayCo Prueba tecnica fullstack PHP React


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


## Authors

- Jorge Cortes Lopez [https://www.linkedin.com/in/jorhcl/]

