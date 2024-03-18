#!/bin/bash

# Genera un ID único
APP_ID=$(openssl rand -hex 16)
echo "APP_ID: $APP_ID"
# Actualiza el archivo .env con el nuevo valor de APP_ID
sed -i "s/SU_APP_ID=.*/SU_APP_ID=$APP_ID/" .env