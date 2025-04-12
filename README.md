# Kilterzone

## Description
La página web será un portal comunitario para los fans de Kilterboard, donde los usuarios podrán 
compartir y crear rutas de escalada, así como comunicarse entre ellos y hacer mucho más. La web 
tendrá la funcionalidad de inicio de sesión, registro e inicio con Oauth. Además, los usuarios podrán 
recuperar su contraseña si se han registrado anteriormente. 
Los usuarios podrán crear rutas seleccionando el tipo de pared de Kilterboard y las piezas que 
se utilizarán. Una vez creada la ruta, los usuarios la podrán mantener en privado o publicar
la porque otras personas la puedan ver, también podrán compartirlas de manera privada. 
También podrán interactuar con las rutas de los otros usuarios, comentándolas, dándolos like 
o marcándolas como hecho. Cuando marquen una ruta como hecho, podrán añadir la dificultad y los 
intentos que los ha costado. Además, los usuarios podrán guardar las rutas para verlas más tarde. 
La web dispondrá de un buscador para buscar rutas por nombre, dificultad y popularidad, y los 
usuarios podrán comunicarse entre ellos con un sistema de mensajería privado. También 
podrán ver los perfiles de los otros usuarios para ver las rutas que han creado, las que los 
han gustado, las que han hecho y su "biografía". 
Una kilterboard se una pared de escalada montable que té una serie de modelos que 
siguen el mismo modelo de piezas haciéndolas compatibles aunque cambia el tamaño. 
Los modelos se dividen entre los de “original layout”: (12x12, 8x12, 7x10, 16x12) y los de 
“hombre wall layout”: (10x12 Expansion, 10x10 Expansion, 8x12 Expansion, 7x10 Hoja Ride, 
7x10 Mainline, 7x10 Auxiliary).
## REQUERIMIENTOS TÉCNICOS
### FrontEnd
Para el frontend, utilizaría HTML, CSS y JavaScript para la creación de las páginas web y
la experiencia de usuario. También usaría Bootstrap y JQuery para acelerar el proceso de
desarrollo y proporcionar un diseño responsiu.
### BackEnd
Para el backend, utilizaría Laravel, que es un framework de PHP. Por la programación de la
base de datos, usaría mySQL. Por la implementación de websockets he utilizar el
paquete de laravel websockets BeyondCode.

## Uso
### Gestión de usuarios
#### Registro
- Para el registro de usuarios, tienes la opción de registrarte desde la misma página de Home cuando no has iniciado sesión.
![image](https://github.com/user-attachments/assets/5ae54f65-2acb-4bb8-8db5-972c19beb8f7)
- Una vez dentro puedes rellenar tu datos y registrarte
![image](https://github.com/user-attachments/assets/3afac3ca-3a18-44cb-9fe6-984d0556f4be)
- Si el usuario comete errores en el inicio de sesión se le indicará a través de alertas bajo los campos erróneos.
![image](https://github.com/user-attachments/assets/00d19779-18d6-4319-8eb4-1c3c479a35fc)
- 


#### Inicio de sesión
#### OAuth
#### Recuperar contraseña
### Funciones de comunicación
#### Amistades entre usuarios
#### Chat entre usuarios
### Creación de rutas
#### Crear ruta
#### Interactuar con la ruta


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
