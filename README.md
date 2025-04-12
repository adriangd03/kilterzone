# Kilterzone

## Description
La página web será un portal comunitario para los fans de Kilterboard, donde los usuarios podrán 
compartir y crear rutas de escalada, así como comunicarse entre ellos y hacer mucho más. 

La web tendrá la funcionalidad de inicio de sesión, registro e inicio con Oauth. Además, los usuarios podrán 
recuperar su contraseña si se han registrado anteriormente. 

Los usuarios podrán crear rutas seleccionando el tipo de pared de Kilterboard y las piezas que 
se utilizarán. Una vez creada la ruta, se publicará. 
También podrán interactuar con las rutas de los otros usuarios, comentándolas, dándolos like 
o marcándolas como hecho. Cuando marquen una ruta como hecha o le den like, se guardará en su lista de rutas guardadas.

Los usuarios podrán comunicarse entre ellos con un sistema de mensajería privado. También 
podrán ver los perfiles de los otros usuarios para ver las rutas que han creado y su "biografía". 

Una kilterboard es una pared de escalada montable que tiene una serie de modelos que 
siguen el mismo modelo de piezas haciéndolas compatibles aunque cambie el tamaño. 
Los modelos se dividen entre los de “original layout”: (12x12, 8x12, 7x10, 16x12) y los de 
“hombre wall layout”: (10x12 Expansion, 10x10 Expansion, 8x12 Expansion, 7x10 Hoja Ride, 
7x10 Mainline, 7x10 Auxiliary).

![image](https://github.com/user-attachments/assets/9ca409bd-36fd-4929-90f6-61670162ad31)


## REQUERIMIENTOS TÉCNICOS
### FrontEnd
Para el frontend, utilizaría HTML, CSS y JavaScript para la creación de las páginas web y
la experiencia de usuario. También usaría Bootstrap y JQuery para acelerar el proceso de
desarrollo y proporcionar un diseño responsiu.
### BackEnd
Para el backend, utilizaría Laravel, que es un framework de PHP. Por la programación de la
base de datos, usaría mySQL. Por la implementación de websockets he utilizar el
paquete de laravel websockets BeyondCode.


## Features
- **Portal Comunitario**: Una plataforma dedicada para los entusiastas de Kilterboard donde pueden compartir y crear rutas de escalada, fomentando un sentido de comunidad entre los usuarios.
- **Autenticación de Usuarios**: Inicio de sesión seguro, registro e integración con OAuth para un acceso sin complicaciones. Incluye recuperación de contraseña para usuarios registrados.
- **Creación y Compartición de Rutas**: Los usuarios pueden diseñar rutas de escalada seleccionando tipos de pared de Kilterboard y presas.
- **Funciones Interactivas**: Interactúa con las rutas de otros usuarios comentándolas, dándoles "me gusta" o marcándolas como completadas.
- **Gestión de Rutas**: Guarda rutas para verlas más tarde.
- **Perfiles de Usuario**: Visualiza los perfiles de otros usuarios para ver las rutas que han creado y sus biografías personales.
- **Mensajería Privada**: Comunícate con otros usuarios a través de un sistema de mensajería privada.
- **Diseño Responsivo**: Construido con HTML, CSS, JavaScript, Bootstrap y JQuery para garantizar una experiencia amigable y responsiva en todos los dispositivos.
- **Base Técnica**: Impulsado por Laravel para las operaciones del backend, MySQL para la gestión de bases de datos y Laravel Websockets para interacciones en tiempo real.

### Qué Lo Hace Único
- **Diseñado para Fans de Kilterboard**: Específicamente creado para la comunidad de escalada de Kilterboard, con características que satisfacen sus necesidades únicas.
- **Interacción Completa con Rutas**: Más allá de solo crear rutas, los usuarios pueden interactuar profundamente con las rutas de otros, añadiendo una capa de interacción social.
- **Comunicación en Tiempo Real**: La integración de websockets permite mensajería instantánea y actualizaciones en tiempo real, creando una experiencia dinámica para el usuario.

## Mejoras a futuro
- Posibilidad de mantener las rutas en privado en su creación para poder hacer modificaciones y cambios o compartirla sin publicarla.
- Añadir la búsqueda y filtraje de rutas.
- Posibiliadad de ver las rutas que han escalado otros usuarios.
- Añadir la busqueda de usuarios.
- Mejorar diseño del home y recomendaciones de rutas.
- Añadir bloqueo de usuasrios.
- Añadir un usuario administrador.
- Añadir un filtro para evitar nombres y palabras violentas, etc...
- Añadir la compartición de rutas a través del chat.
