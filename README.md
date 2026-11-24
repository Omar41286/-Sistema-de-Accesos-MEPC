# -Sistema-de-Accesos-MEPC
Aplicación web empresarial orientada al control seguro de entradas, salidas y trazabilidad de productos e inventarios mediante la generación y escaneo dinámico de códigos QR.

## Características principales
 **Generación de Códigos QR:** Creación automática de códigos únicos por producto y usuario mediante integración con librerías especializadas.
 **Escaneo en Tiempo Real:** Validación de accesos y movimientos de stock mediante lector integrado.
 **Paneles de Control:** Vistas independientes y seguras para administradores y usuarios generales.
 **Reportes Automatizados:** Consulta de registros históricos de inventario y accesos.

## Tecnologías utilizadas
 **Lenguaje:** PHP.
 **Base de datos:** SQL Server / MySQL (respaldo incluido en `copia_DB_SafeEntry.sql`).
 **Librerías:** phpqrcode / TCPDF para manejo e impresión de códigos.

## Instalación y configuración
1. Restaura la base de datos utilizando el archivo `copia_DB_SafeEntry.sql`.
2. Configura la conexión a la base de datos en `conexion_php_sqlS.php`.
3. Ejecuta el proyecto en tu servidor local compatible con PHP.
