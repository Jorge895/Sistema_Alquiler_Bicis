
# Sistema de Alquiler de Bicicletas
## Desarrolladores
- Pedraza Laboriano, Jonnathan Jesús - 20231505
- Valenzuela Villalobos, Eduardo -20231512
- Vilchez Alejo, Jorge -20231514
- Ventura Pezantes, Josúe Elías -20231513
## Descripción General

El proyecto "Sistema de Alquiler de Bicicletas" tiene como objetivo crear una plataforma para la gestión eficiente del alquiler de bicicletas en una ciudad o área específica. La aplicación está diseñada para facilitar tanto el proceso de alquiler de bicicletas como la administración de la disponibilidad de las mismas. A través de este sistema, los usuarios podrán alquilar bicicletas de manera sencilla, mientras que los administradores podrán gestionar el inventario, el historial de alquileres y los pagos.

Este sistema utiliza una base de datos relacional para almacenar información crucial sobre los usuarios, bicicletas, transacciones de alquiler y horarios, garantizando que todos los datos se manejen de manera eficiente y segura.

## Características Principales

1. **Registro y Autenticación de Usuarios**
   - Los usuarios pueden crear una cuenta en el sistema, donde se almacenará su información personal (nombre, dirección, teléfono, etc.).
   - Los usuarios pueden iniciar sesión y acceder a sus perfiles, donde se guardan sus historiales de alquileres.

2. **Gestión de Bicicletas**
   - El sistema permite a los administradores agregar, actualizar y eliminar bicicletas del inventario.
   - Cada bicicleta tiene un identificador único y detalles como modelo, ubicación, estado (disponible, en mantenimiento, etc.).

3. **Alquiler de Bicicletas**
   - Los usuarios pueden ver las bicicletas disponibles y alquilar una por un período específico.
   - El sistema calcula el costo del alquiler en función de la duración y el tipo de bicicleta seleccionada.

4. **Historial de Alquileres**
   - Los usuarios pueden consultar su historial de alquileres, incluyendo las bicicletas alquiladas, fechas de alquiler y pagos realizados.
   - Los administradores pueden revisar el historial de todos los alquileres realizados y generar reportes.

5. **Pagos**
   - El sistema facilita el pago en línea a través de métodos como tarjetas de crédito o sistemas de pago digital.
   - El sistema genera facturas y recibos detallados para los usuarios.

6. **Mantenimiento de Bicicletas**
   - Los administradores pueden marcar las bicicletas como "en mantenimiento", lo que las hace no disponibles para el alquiler hasta que se complete la reparación.

7. **Gestión de Disponibilidad**
   - El sistema permite definir la disponibilidad de las bicicletas en función de la ubicación y los horarios de apertura y cierre.

## Tecnologías Utilizadas

Por el momento, el proyecto está construido utilizando las siguientes tecnologías:

- **Base de Datos:** MySQL/PostgreSQL
- **Frontend:** PHP 
- **Autenticación:** JWT (JSON Web Tokens)
- **Control de Versiones:** Git, GitHub

## Estructura del Proyecto

El repositorio está organizado de la siguiente manera:

```
# Estructura de Archivos del Repositorio

```plaintext
Sistema_Alquiler_Bicis
│
├── imagenes/                   # Carpeta que contiene las imágenes
│
├── Principal.php               # Página principal de la aplicación
├── README.md                   # Documento de documentación del repositorio
├── añadir_registro.php         # Página para añadir registros
├── borrar_registro.php         # Página para eliminar registros
├── codigo_msql.txt             # Archivo de configuración de la base de datos
├── conexion.php                # Archivo que gestiona la conexión a la base de datos
├── modificar_inventario.php    # Página para modificar el inventario de bicicletas
├── registrar_entrega.php       # Página para registrar la entrega de bicicletas
├── reportes_de_ingresos.php    # Página para generar reportes de ingresos
├── revisar_bd.php              # Archivo que revisa la base de datos
└── tendencias_uso.php          # Página para analizar tendencias de uso
ments.txt        # Dependencias del proyecto
```
## Contribuciones

Las contribuciones son bienvenidas. Si deseas contribuir a este proyecto, sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una nueva rama (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza tus cambios y haz commit de ellos (`git commit -am 'Añadir nueva funcionalidad'`).
4. Haz push a tu rama (`git push origin feature/nueva-funcionalidad`).
5. Abre un pull request.

## Contacto
M
Si tienes alguna pregunta o sugerencia, no dudes en ponerte en contacto con el equipo a través de los siguientes medios:

- Correos: 20231505@lamolina.edu.pe,
- Correos: 20231513@lamolina.edu.pe,
- Correos: 20231514@lamolina.edu.pe,
- Correos: 20231505@lamolina.edu.pe,
- GitHub: [https://github.com/Jorge895/Sistema_Alquiler_Bicis](https://github.com/Jorge895/Sistema_Alquiler_Bicis)
