#### при анализе выяснилось что города имеют свою логику только в файлах `offers*_*.xml`
- импорт сперва создает таблицу `products` и функции для филда `usage`
- функция SPLIT_STRING чтобы программа смог расколотить string
- функция checkAndFillOldValue чтобы прикрепить все что не обнаружена в прошлом
- Создает все динамичные колонки `price_`, `quantity_` для городов
- Делает импорт всех продуктов из файлов `import*_*.xml`
- Делает импорт из файлов `offers*_*.xml` группируя запросы для конкретного города

#### Данные для работы: `data.tar.gz`
#### Скорость - 80 - 83 секунд

### Создал фронтовую часть

- таблицу с scroll pagination и search