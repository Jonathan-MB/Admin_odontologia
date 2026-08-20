# Centro Odontológico Peñadent

Sistema interno para el manejo diario de la clínica: pacientes, historia clínica,
agenda y facturación. Lo usa el personal de recepción y los odontólogos en las
dos sedes.

---

## Qué hace

Todo gira alrededor del paciente. Se busca por documento o por nombre, y desde
ahí se llega a las tres cosas que se hacen a diario:

**La historia clínica.** Un odontograma con los 32 dientes permanentes y los 20
temporales. Cada diente guarda su propia evolución, y hay dos campos generales
aparte: uno para la evolución del paciente y otro para cotizaciones, que se
pueden imprimir.

**La agenda.** Un calendario mensual que marca los días con citas. Al abrir un
día se ven las citas agrupadas por doctor, con la hora, el documento y el
teléfono del paciente. Desde ahí mismo se agenda, se reagenda o se cancela, y
se puede saltar a la ficha del paciente si hay que consultarlo o llamarlo.

**La facturación.** Se registra el abono, el saldo pendiente, quién atendió y
con qué método se pagó. Cada factura lleva un consecutivo propio por sede y se
imprime en el acto. Al cerrar el día se ve cuánto generó cada odontólogo y
cuánto entró por cada método de pago.

## Alcance

Cada sede trabaja por separado: al entrar se elige una, y a partir de ahí solo
se ven sus citas, sus facturas y su consecutivo. Un mismo paciente puede
atenderse en las dos.

Hay dos roles. El **colaborador** hace el trabajo de recepción: pacientes,
historias, agenda y facturación. El **administrador** además gestiona usuarios,
odontólogos y ve el cierre diario.

Lo que el sistema **no** hace: no factura electrónicamente ante la DIAN, no
maneja inventario ni nómina, no envía recordatorios automáticos y no tiene
portal para el paciente. Es una herramienta de mostrador, no un ERP.

---

## Requisitos

- PHP 8.2 o superior
- MySQL 5.7 o superior (MariaDB sirve)
- Composer

## Instalación

```bash
git clone https://github.com/Jonathan-MB/Admin_odontologia.git
cd Admin_odontologia
composer install
cp .env.example .env
php artisan key:generate
```

Crea la base de datos y ajusta `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD`
en el `.env`. Después:

```bash
php artisan migrate --seed
php artisan serve
```

### Usuarios de prueba

El seeder crea dos usuarios para probar los dos roles:

| Correo | Contraseña | Rol |
|---|---|---|
| `admin@demo.test` | `admin12345` | Administrador |
| `recepcion@demo.test` | `recepcion12345` | Colaborador |

Entra con cada uno y compara: el colaborador no ve Usuarios, Especialistas ni
Total Diario, y tampoco entra escribiendo la URL directamente.

Estos usuarios **no se crean en producción**: ahí las cuentas reales se dan de
alta desde la aplicación, en Configuración → Usuarios.

---

## Estructura

En desarrollo es un Laravel normal, con `public/` adentro. En el hosting la
carpeta pública va aparte:

```
adminodontologiapenadent.com/
├── public_html/          <- contenido de laravel/public/
└── laravel/              <- el resto, fuera del docroot
```

`public/index.php` detecta solo dónde está montado, así que el mismo archivo
sirve en los dos lados.

Al desplegar, el contenido de `laravel/public/` va al **raíz** de
`public_html/`, no a `public_html/public/`.

---

## Zonas delicadas

Cosas que conviene saber antes de tocar el código.

### La impresión de recibos

La factura y la cotización no se imprimen desde la página: se clona el bloque
oculto, se arma un HTML aparte y se abre en una pestaña nueva que se manda a
imprimir. Está hecho así para que el papel salga exactamente igual siempre,
sin que lo afecte el resto de la pantalla.

Esa ventana hereda **todas** las hojas de estilo de la página, no solo
`facturaImprimir.css`. Por eso todo el CSS de pantalla vive dentro de
`@media screen`: al imprimir, el navegador lo ignora por completo.

Si vas a tocar estilos, respeta esa separación. Y prueba imprimiendo, no solo
mirando la pantalla.

### Las citas y su espejo

Las citas viven en la tabla `citas`, con su fecha, su sede y su doctor. Pero
`clientes.fecha_cita` se conserva como espejo de la próxima cita, porque es lo
que leen la facturación y el recibo impreso.

Ese espejo se mantiene solo: cualquier cambio en una cita recalcula la columna.
No la escribas a mano.

Regla del negocio: un paciente tiene **una sola cita pendiente a la vez**.
Reagendar mueve la que existe, no crea otra.

### La sede vive en la sesión

Al iniciar sesión se elige sede y queda guardada en la sesión. El middleware
`verificar.sede` bloquea todo lo demás hasta que se elija. El consecutivo de
factura, la agenda y el cierre diario dependen de ese dato.

### Nombres que importan

Hay puntos donde el nombre **es** la funcionalidad, y cambiarlo rompe cosas sin
dar ningún error:

- Los dientes `Evolucion` y `Cotizacion` no son dientes: son los dos campos de
  texto general de la historia. Se guardaron así para no cambiar la estructura.
  Las vistas los buscan por ese nombre exacto.
- Los archivos de `public/` se sirven con el nombre literal. Windows no
  distingue mayúsculas, Linux sí: `Configuracion.css` y `configuracion.css` son
  dos archivos distintos en el servidor.

---

## Pruebas

```bash
php artisan test
```

Cubren el acceso por rol, la búsqueda por documento y por nombre, el guardado y
la numeración de facturas, los métodos de pago, la agenda con sus citas por sede
y que los seeders no expongan datos reales.

---

## Comandos útiles

```bash
php artisan migrate:status                        # qué migraciones faltan
php artisan db:seed --class=MetodoPagoSeeder      # solo los métodos de pago
php artisan optimize:clear                        # limpiar cachés
```

En producción, después de cada despliegue:

```bash
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

Esas cachés guardan rutas absolutas, así que se generan **en el servidor**.
Nunca subas `bootstrap/cache/` desde tu máquina.
