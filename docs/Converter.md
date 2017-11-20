## Converter

### Описание

Этот пакет предоставляет инструменты для форматирования содержимого `\rollun\datastore\DataStore`. 
Класс Converter считывает данные из datastore, фильтрует нужные поля с помощью `Zend\FilterInterface` и записывает данные в другой или тот же datastore.
Источник и пункт назначения данных, а также нужные имена полей и фильтры обозначаются конфигурационным файлом.
### Начало работы
##### Предварительные требования
Для работы пакета нужно использовать [rollun-skeleton](https://github.com/rollun-com/rollun-skeleton). 
Также для корректной работы потребуется [rollun-datastore](https://github.com/rollun-com/rollun-datastore) и [rollun-installer](https://github.com/rollun-com/rollun-installer).
##### Установка
Для установки пакета нужно выполнить команду `composer lib install` из папки проекта. 
В появившкмся меню нужно выбрать `rollun\BinaryParser\Installer\ConverterInstaller`.

### Настройка

Класс ConverterAbstractfactory автоматически генерирует объекты класса Converter, используя предоставленные пользователем файлы конфигурации.

Пример файла конфигурации :
```
return [
    ConverterAbstractFactory::KEY => [
        "CoolConverter" => [
            ConverterAbstractFactory::KEY_ORIGIN_DS => "datastore1",
            ConverterAbstractFactory::KEY_DESTINATION_DS => "datastore2",
            ConverterAbstractFactory::KEY_QUERY => "select(MSP)",
            ConverterAbstractFactory::KEY_FILTERS => [
                "MSP" => ["ConverterAbstractFactory::KEY_FILTER_CLASS_NAME" => PriceFixer::class, ConverterAbstractFactory::KEY_FILTER_PARAMS => ["paramName" => "value"]],
            ]
        ]
    ],
]
```
*CoolConverter* - имя, под которым ваш сервис будет фигурировать в системе;
* *ConverterAbstractFactory::KEY_ORIGIN_DS* => имя datastore, который является источник данных;
* *ConverterAbstractFactory::KEY_DESTINATION_DS* => имя datastore, который является пунктом прибытия данных (необязательное поле; по умолчанию информация будет записана в исходный datastore);
* *ConverterAbstractFactory::KEY_QUERY* =>  строка, представляющая [Rql запрос](https://github.com/avz-cmf/zaboy-dojo/blob/master/doc/RQL.md) (необязательное поле; по умолчанию будут выбраны все поля);
* *ConverterAbstractFactory::KEY_FILTERS* => массив, содержащий имена полей и фильтров, которые нужно применить к этим полям
    * *MSP* - имя поля, которое нужно изменить;
    * *ConverterAbstractFactory::KEY_FILTER_CLASS_NAME* => имя фильтра, которым нужно изменить поле (в данном примере - PriceFixer::class);
    * *ConverterAbstractFactory::KEY_FILTER_PARAMS* => набор параметров, которые принимает filterName (необязательное поле; значение по умолчанию - `[]`);

### Использование

Для использования Converter запросите его из контейнера и вызовите его метод __invoke:
```
$converter = $container->get("CoolConverter");
$converter();
``` 
