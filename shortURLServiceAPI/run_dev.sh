PORT=8000
if [ -z "$1" ]; then
    echo "No port specified, using default port $PORT. To specify a port, run 'run_dev.sh <port>'."
else
    PORT=$1
fi
./update_app_id.sh
php artisan serve --port=$PORT