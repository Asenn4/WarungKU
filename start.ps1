# Detect IP LAN otomatis
$ip = (Get-NetIPAddress -AddressFamily IPv4 `
       | Where-Object { $_.IPAddress -like "192.168.*" } `
       | Select-Object -First 1 -ExpandProperty IPAddress)

Write-Host "IP terdeteksi: $ip"

# Update .env (APP_URL dan VITE_DEV_SERVER_URL)
(Get-Content ".env") `
    -replace "APP_URL=.*", "APP_URL=http://$ip`:8000" `
    -replace "VITE_DEV_SERVER_URL=.*", "VITE_DEV_SERVER_URL=http://$ip`:5173" `
    | Set-Content ".env"

Write-Host "Config .env di-update!"

# Clear cache Laravel
php artisan optimize:clear

# Jalankan Laravel
Start-Process powershell -ArgumentList "php artisan serve --host=0.0.0.0 --port=8000"

# Jalankan Vite
npm run dev -- --host $ip --port 5173
