## 🛠 Технологический стек
*   **Backend**: PHP 8.4 (FPM) + Yii 2.0.55
*   **Database**: PostgreSQL 17
*   **Web-server**: Nginx 1.29 (Alpine)
*   **Окружение**: Docker Compose + Makefile
*   **Тестирование**: PHPUNIT

---

## 🚀 Быстрый старт (Docker)

### 1. Подготовка окружения
Убедитесь, что у вас установлены `Docker` и утилита `make`.
Склонируйте репозиторий и перейдите в папку проекта. Создайте файл настроек окружения из шаблона:

```bash
cp .env.example .env
```
*(При необходимости измените порты или доступы к БД внутри файла `.env`)*

### 2. Сборка и инициализация проекта
Запустите команду, которая автоматически соберет Docker-образы и установит все Composer зависимости:

```bash
# Запуск через Makefile
make init

# Запуск через docker-compose
docker compose -f docker/docker-compose.yml --env-file .env build
```

### 3. Запуск контейнеров
Запустите контейнеры в фоновом режиме:

```bash
# Запуск через Makefile
make up

# Запуск через docker-compose
docker compose -f docker/docker-compose.yml --env-file .env up -d
```
После запуска приложение станет доступно в браузере по адресу: **`http://localhost:8078`** (или по порту, который вы указали в `APP_PORT` в `.env`).

### 4. БД: Применение миграций и наполнение фикстурами(тестовыми данными)
Создайте необходимую структуру таблиц БД:
```bash
# Запуск через Makefile
make migrate-up

# Запуск через docker-compose
docker compose -f docker/docker-compose.yml exec app-php php yii migrate --interactive=0
```

Наполните базу данных тестовыми данными:
```bash
# Запуск через Makefile
make fixtures-load

# Запуск через docker-compose
docker compose -f docker/docker-compose.yml exec app-php php yii fixture/load "*" --interactive=0
```

---

## 🔐 Тестовые доступы (Login)
Для управления книгами и авторами выполните вход в систему (кнопка **Login** в верхнем меню):

*   **Администратор:** Логин `admin` / Пароль `admin12Ts72`
*   **Демо-пользователь:** Логин `demo` / Пароль `demo12Ts72`

---

## ⚙️ Список доступных команд (Makefile)

В корне проекта настроен `Makefile` для удобного управления Docker-контейнерами:

*   `make init` — Сборка образов и первичная установка зависимостей.
*   `make build` — Пересборка Docker-образов.
*   `make up` — Запуск веб-окружения.
*   `make down` — Остановка контейнеров.
*   `make clear` — Полная очистка контейнеров вместе с Docker-volumes (очищает базу данных).
*   `make shell` — Подключение к bash-консоли PHP-контейнера.
*   `make composer-install` — Установка пакетов через Composer внутри контейнера.
*   `make composer-update` — Обновление пакетов проекта.
*   `make migrate-up` — Применение миграций БД.
*   `make migrate-refresh` — Полный откат и повторный запуск всех миграций БД.
*   `make fixtures-load` — Сброс и повторное заполнение БД тестовыми данными (авторы, книги, подписки).
*   `make queue-run` — Запуск обработки очереди БД.
*   `make queue-listen` — Запуск непрерывного фонового процесса обработки очереди БД.
*   `make phpunit-test` — Запуск тестов phpunit.

---

## 🧪 Тестирование и Статический анализ

В проекте настроена среда автоматического тестирования Codeception, а также инструменты контроля качества кода (CodeSniffer, PHPStan).

Запуск тестов внутри PHP-контейнера (`make shell`):
```bash
# Запуск тестов
composer phpunit

# Запуск статического анализатора (PHPStan)
composer static

# Проверка кодовой базы на соответствие стандартам Yii2 (CodeSniffer)
composer cs
```
