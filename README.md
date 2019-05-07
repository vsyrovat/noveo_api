# Демка по управлению группами и пользователями
Сценарии можно посмотреть [здесь](features/main.feature).

Приложение построено на базе SF 4.2 с использованием domain-driven-design принципов.

Выделена доменная часть: [сущности](src/Domain/Entity), [write-команды](src/Domain/Command), [read-запросы](src/Domain/Query). Валидация описана частично в аннотациях к сущностям, частично - в [отдельном неймспейсе](src/Domain/Validation). С внешним миром домен взаимодействует через [веб-морду](src/EntryPoints/Http).

## Запуск приложения в dev и test-окружении
1. Прочитайте `docker-compose.yml`, если ваш id пользователя/группы отличается от 1000 - отредактируйте. В линуксах проверяется командой `id`.
1. Запустите `./start-infra.sh` - поднимется постгрес-база.
1. У вас же есть в системе php 7.1 или 7.2?
1. Выполните `bin/console doctrine:migrations:migrate`
1. Выполните `bin/console server:run`
1. Откройте в браузере http://localhost:8000/api/doc

## Запуск тестов
`./test.sh`, при поднятой базе.

Тесты включают в себя проверку корректности swagger-документации и Behat-тесты.

## Остановка и удаление
1. `./stop-infra.sh`
1. Папку с кодом можно просто удалить.
