## TuckerRockyParser
Преобразовывает таблицы товаров TuckerRocky (файлы itemmstrnew, itemmstrnewa, itemmstrnewb) в человекочитаемые DataStore.
#### Использование
Для начала работы создайте новый объект класса `TuckerRockyParser`, передав ему в параметры конструктора полный путь к файлу itemmstrnew и имя DataStore.

Пример:
```
$filename = "path/to/itemmstrnew";
$datastore = $container->get("myDatastore");
$parser = new TuckerRockyParser($filename, $datastore);
$parser();
```
###### Известные проблемы
Текущая схема парсинга покрывает не все возможные значения в списке, поэтому в полях `newSegment` и `colorPattern` может появлятся значение `"PARSER_UNDEFINED"`