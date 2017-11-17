##Converter
###Описание
Этот пакет предоставляет инструменты для форматирования содержимого `\rollun\datastore\DataStore`. 
Класс Converter считывает данные из datastore, фильтрует нужные поля с помощью `Zend\FilterInterface` и записывает данные в другой (необязательно) datastore.
Источник и пункт назначения данных, а также нужные имена полей и фильтры обозначаются конфигурационным файлом.
###Начало работы
#####Предварительные требования
Для работы пакета нужно использовать [rollun-skeleton](https://github.com/rollun-com/rollun-skeleton). 
Также для корректной работы потребуется [rollun-datastore](https://github.com/rollun-com/rollun-datastore) и [rollun-installer](https://github.com/rollun-com/rollun-installer).
#####Установка
Для установки пакета нужно выполнить команду `composer lib install` из папки проекта. 
В появившкмся меню нужно выбрать `rollun\BinaryParser\Installer\ConverterInstaller`.

###Настройка
Класс ConverterAbstractfactory автоматически генерирует объекты класса Converter, используя предоставленные пользователем файлы конфигурации.

Пример файла конфигурации :
```
return [
    ConverterAbstractFactory::KEY => [
        "CoolConverter" => [
            ConverterAbstractFactory::KEY_ORIGIN_DS => "datastore1",
            ConverterAbstractFactory::KEY_DESTINATION_DS => "datastore2",
            ConverterAbstractFactory::KEY_QUERY => new RqlQuery(),
            ConverterAbstractFactory::KEY_FILTERS => [
                "MSP" => ["filterName" => PriceFixer::class, "filterParams" => []],
            ]
        ]
    ],
]
```
*CoolConverter* - имя, под которым ваш сервис будет фигурировать в системе;

*datastore1* - Источник данных;

*datastore2* -  Пункт прибытия данных (необязательное поле; значение по умолчанию - datastore1);

*RqlQuery* -  объект класса `Xiag\Rql\Parser\Query`, представляющий Rql запрос (необязательное поле; значение по умолчанию - `RqlQuery()`);

*ConverterAbstractFactory::KEY_FILTERS* - массив, содержащий имена полей и фильтров, которые нужно применить к этим полям 

*MSP* - имя поля из datastore1, которое нужно изменить;

*filterName* - имя фильтра, которым нужно изменить поле;

*filterParams* - набор параметров, которые принимает filterName (необязательное поле; значение по умолчанию - `[]`);

###Использование

Для использования Converter запросите его из контейнера и вызовите его метод __invoke:
```
$converter = $container->get("CoolConverter");
$converter();

``` 
