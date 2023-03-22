# Начало работы

## 1. Заполнение переменных окружения
```
cp .env.example .env
```
## 2. Сборка контейнеров
```
docker-compose up -d --build
```
## 3. Установка зависимостей
```
docker exec hospital_web composer install --ignore-platform-reqs
docker exec hospital_web npm install
```
## 4. Сборка фронтенда
```
docker exec hospital_web npm run build
```
