
# Sistema de Alquiler de Bicicletas
## Desarrolladores
- Pedraza Laboriano, Jonnathan Jesús - 20231505
- Valenzuela Villalobos, Eduardo -
- Vilchez Alejo, Jorge -
- Ventura Pezantes, Josúe Elías -
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

El proyecto está construido utilizando las siguientes tecnologías:

- **Lenguaje de Programación:** Python (Flask/Django para el backend)
- **Base de Datos:** MySQL/PostgreSQL
- **Frontend:** HTML, CSS, JavaScript (React.js o Vue.js)
- **Autenticación:** JWT (JSON Web Tokens)
- **Control de Versiones:** Git, GitHub

## Estructura del Proyecto

El repositorio está organizado de la siguiente manera:

```
Sistema_Alquiler_Bicis/
│
├── backend/                # Código del servidor y la lógica de negocio
│   ├── models/             # Modelos de base de datos
│   ├── controllers/        # Lógica de control de las rutas
│   ├── routes/             # Rutas de la API
│   ├── config/             # Configuraciones de la aplicación
│   └── app.py              # Punto de entrada del servidor
│
├── frontend/               # Interfaz de usuario
│   ├── components/         # Componentes reutilizables
│   ├── pages/              # Páginas de la aplicación
│   └── index.html          # Página principal
│
├── database/               # Scripts para la creación de la base de datos
│   └── schema.sql          # Esquema de la base de datos
│
├── README.md               # Este archivo
└── requirements.txt        # Dependencias del proyecto
```

## Instrucciones de Instalación

### Prerrequisitos

Antes de comenzar, asegúrate de tener instalado:

- Python 3.8 o superior
- MySQL o PostgreSQL (según la base de datos elegida)
- Node.js (para el frontend si usas React o Vue)

### Instalación del Backend

1. Clona el repositorio:
   ```bash
   git clone https://github.com/Jorge895/Sistema_Alquiler_Bicis.git
   cd Sistema_Alquiler_Bicis
   ```

2. Instala las dependencias del backend:
   ```bash
   pip install -r backend/requirements.txt
   ```

3. Configura la base de datos:
   - Crea una base de datos en MySQL o PostgreSQL.
   - Ejecuta los scripts de creación de base de datos desde el archivo `database/schema.sql`.

4. Inicia el servidor:
   ```bash
   cd backend
   python app.py
   ```

### Instalación del Frontend

1. Dirígete al directorio del frontend:
   ```bash
   cd frontend
   ```

2. Instala las dependencias de Node.js:
   ```bash
   npm install
   ```

3. Inicia el servidor frontend:
   ```bash
   npm start
   ```

## Contribuciones

Las contribuciones son bienvenidas. Si deseas contribuir a este proyecto, sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una nueva rama (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza tus cambios y haz commit de ellos (`git commit -am 'Añadir nueva funcionalidad'`).
4. Haz push a tu rama (`git push origin feature/nueva-funcionalidad`).
5. Abre un pull request.

## Contacto

Si tienes alguna pregunta o sugerencia, no dudes en ponerte en contacto con el equipo a través de los siguientes medios:

- Correos: 20231505@lamolina.edu.pe, 
- GitHub: [https://github.com/Jorge895/Sistema_Alquiler_Bicis](https://github.com/Jorge895/Sistema_Alquiler_Bicis)
