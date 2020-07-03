#Catalog API

##Задание
Спроектировать БД и написать импорт загрузки представленных ниже данных
Реализовать API по предоставленному ниже ТЗ

##Сущности каталога
Каталог, содержащий два вида объектов
 * Категория
 * Товар

###Категория (category)
Древовидная структура с неограниченной вложенностью. Каждая категория может принадлежать только одной родительской категории
 * ID (id - int)
 * Название (title - string, 240 символов)

###Товар (product)
Представляет собой объект со следующими полями
 * ID (id - int)
 * Название (title - string, 240 символов)
 * Краткое описание (short_description - string, 240 символов)
 * Ссылка на картинку товара (image_url - string, 240 символов)
 * Количество товаров в наличии (amount - int)
 * Цена (price - double, точность 12,2)
 * Название производителя (producer - string, 240 символов)

Товар может быть привязан к нескольким категориям
 
##Методы API каталога
Взаимодействие с пользователем происходит посредством REST HTTP запросов к API серверу. Все ответы представляют собой JSON объекты.

##Сервер реализует следующие методы:
Получение списка категорий (GET /api/v1/categories), возвращает массив объектов модели категорий в поле data
Получение товара по ID (GET /api/v1/products/{id}), возвращает модель товара в поле data
Получение списка товаров с сортировкой, фильтрацией и функцией “бесконечной подгрузки” (GET /api/v1/products/) возвращает массив объектов модели товара в поле дата


###Примеры моделей сущностей
####Категория
```json
{
        "id": 1,
        "title": "Категория 1",
        "parent_id": null
}
```

parent_id - ИД родительской категории либо null если это “корневая” категория

####Товар
```json
{
    "id": 1,
    "title": "Товар 1",
    "short_description": "Описание товара 1",
    "image_url": "https:\/\/picsum.photos\/200\/300",
    "price": 400.21,
    "amount": 12,
    "producer": "Производитель 1",
    "categories": [
        {
            "id": 1,
            "title": "Категория 1",
            "parent_id": null
        },
        {
            "id": 5,
            "title": "Категория 2.1",
            "parent_id": 2
        }
    ]
}
```

####Примеры результатов методов
GET /api/v1/categories
```json
{
"data": [
{
        "id": 1,
        "title": "Категория 1",
        "parent_id": null
},
{
        "id": 2,
        "title": "Категория 2",
        "parent_id": null
},
{
        "id": 3,
        "title": "Категория 1.1",
        "parent_id": 1
}
]
}
```

GET /api/v1/products/1
```json
{
"data": 
{
    "id": 1,
    "title": "Товар 1",
    "short_description": "Описание товара 1",
    "image_url": "https:\/\/picsum.photos\/200\/300",
    "price": 400.21,
    "amount": 12,
    "producer": "Производитель 1",
    "categories": [
        {
            "id": 1,
            "title": "Категория 1",
            "parent_id": null
        },
        {
            "id": 5,
            "title": "Категория 2.1",
            "parent_id": 2
        }
    ]
}
}
```

GET /api/v1/products?maxItems=2
```json
{
"data": [
{
    "id": 1,
    "title": "Товар 1",
    "short_description": "Описание товара 1",
    "image_url": "https:\/\/picsum.photos\/200\/300",
    "price": 400.21,
    "amount": 12,
    "producer": "Производитель 1",
    "categories": [
        {
            "id": 1,
            "title": "Категория 1",
            "parent_id": null
        },
        {
            "id": 5,
            "title": "Категория 2.1",
            "parent_id": 2
        }
    ]
},
{
    "id": 2,
    "title": "Товар 1",
    "short_description": "Описание товара 1",
    "image_url": "https:\/\/picsum.photos\/200\/300",
    "price": 41.21,
    "amount": 1,
    "producer": "Производитель 2",
    "categories": [
        {
            "id": 2,
            "title": "Категория 2",
            "parent_id": null
        },
        {
            "id": 5,
            "title": "Категория 2.1",
            "parent_id": 2
        }
    ]
}
]
}
```


##Фильтрация товаров
Параметры фильтрации задаются через URL-параметр filter с указанием типа фильтрации и значения фильтрации

###Список фильтров
Получение списка товаров по вхождению подстроки в названии (filter[title])
Пример: `GET /api/v1/products/?filter[title]=substr`
Возвращает список товаров у которых в названии содержится substr


Получение товаров по названию производителя/производителей (filter[producer][])
Пример: `GET /api/v1/products/?filter[producer][]=title1&filter[producer][]=title2`
Возвращает список товаров, у которых название производителя либо title1 либо title2

Получение товаров по категории (filter[categoryId])
Пример: `GET /api/v1/products/?filter[categoryId]=5`
Возвращает список товаров, которые принадлежат категории с ID=5, без подкатегорий

Получение товаров по категории и ее подкатегориям (filter[parentCategoryId])
Пример: `GET /api/v1/products/?filter[parentCategoryId]=5`
Возвращает список товаров, которые принадлежат категории с ID=5 и всем её подкатегориям. Товары в списке не должны дублироваться если принадлежат разным категориям

###Сортировка 
Сортировка задается параметром sort. В задании должна быть реализована сортировка по названию и по цене. Сортировка по умолчанию - цена по убыванию. Порядок сортировки по убыванию задается знаком минус перед полем сортировки
Примеры
Сортировка по цене товара в порядке возрастания
`GET /api/v1/products/?sort=price`

Сортировка по названию товара в порядке убывания
`GET /api/v1/products/?sort=-title`
Контроль за количеством товаров в результате выдачи и реализация “бесконечной подгрузки”
Выборка части списка товаров контролируется параметрами startFrom и maxItems. По-умолчанию startFrom=0 и maxItems=10
Примеры
Получение первых 10 товаров
`GET /api/v1/products/`
Получение 15 товаров, начиная с тридцать первого
`GET /api/v1/products/?startFrom=31&maxItems=15`

###Совмещение параметров
Все параметры метода получения загрузки могут быть совмещены. Например вот такой запрос

```
GET /api/v1/products/?filter[title]=нова&filter[producer][]=Нефтехим&filter[categoryId]=5&startFrom=31&maxItems
=15&sort=-title
```

должен вернуть максимально 15 товаров от производителя с названием “Нефтехим” в категории с ID=5, содержащих в названии подстроку “нова” начиная с 31 позиции с сортировкой в порядке убывания названия товара

Ситуация, когда по какой-то причине один и тот же тип фильтра задан не один раз не рассматривается (GET /api/v1/products/?filter[title]=123&filter[title]=456)

##Исходные данные
Ниже представлены исходные данные для заполнения БД в формате json.
Данные представляют из себя результат выполнения методов `GET /api/v1/categories и GET /api/v1/products` в максимальном объеме
 * [категории](install/categories.json)
 * [товары](install/products.json)