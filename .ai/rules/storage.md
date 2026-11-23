---
paths:
  - 'storage/**'
---

# Storage

## public/storage debe ser un junction en Windows (no una carpeta copia)
Las fotos de perfil se guardan en storage/app/public/photos y se sirven por /storage. En Windows public/storage no puede ser una copia real de la carpeta (las subidas nuevas no aparecen y la imagen se rompe mostrando solo el alt/nombre y el avatar por defecto). Si el enlace se pierde, recrearlo con: Remove-Item -Recurse public\storage; cmd /c mklink /J public\storage "<ruta>\storage\app\public". `php artisan storage:link` suele fallar sin permisos de administrador.
