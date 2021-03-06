# How To Install
- `composer install`
- Записать реквизиты соединения с СУБД в файле 
`common/config/main-local.php`
- `php yii migrate`
- Настроить маршрутизацию запросов к скриптам `frontend/web/index.php`
 и `backend/web/index.php` (создать записи DNS и настроить 
 виртуальные хосты на веб сервере)

# How To Adjust
- Открыть в браузере сайт для `frontend/web/index.php`
- Зарегистрироваться
- В директории `frontend/runtime/mail` найти письмо со ссылкой для 
активации учётной записи
- Найти в письме ссылку и перейти по ней, учётная запись будет 
активирована
- Открыть в браузере сайт для `backend/web/index.php`
- Залогиниться, автоматически должна открыться страница `apple/index`
- Нажать кнопку `Раздать карты`, будут сгенерированы яблоки

# How To Use
Кнопка `Раздать карты` создаёт новый набор яблок.

Кнопка `Выкинуть гнилые` обновляет статус яблок и удаляет гнилые.

Кнопка `Сорвать` делает яблоко "опавшим" и доступным для поедания и 
гниения.

Кнопка `Откусить` увеличивает показатель "Израсходовано" на заданную 
величину ("Израсходовано" измеряется в процентах).

# Отзыв на задание
По заданию сначала говориться что надо сделать класс, и даётся 
use case для тестирования класса, а потом описывается интерфейс
пользователя.

И вот не понятно, что надо было сделать ? Класс и тесты ? Или
интерфейс пользователя и API, что бы из этого интерфейса дёргать ?

При этом ладно бы CRUD был, но нет, у в задании своя логика над 
которой надо хорошо подумать, как своевременно гнилые яблоки 
выкидывать, вообще не понятно, не создавать же задание в планировщике.

На мой вкус, для тестовго задания объём слишком большой.

В итоге получилось и не туда и не сюда. И ни класса, и ни интерфейса.

Класс у меня получился такой что делаем `app\Domain\Manager` и через
его методы работаем с нашим яблоком (если менеджеру не дать яблока, 
то он сам себе его сгенерирует).

Вариант использования из задания реализовать можно.

# Тестовое задание

Установить advanced шаблон Yii2 фреймворка, в backend-приложении 
реализовать следующий закрытый функционал (доступ в backend-приложение
должен производиться только по паролю, сложного разделения прав не 
требуется):

Написать класс/объект Apple с хранением яблок в БД MySql следуя ООП 
парадигме.

Функции
- упасть
- съесть ($percent - процент откушенной части)
- удалить (когда полностью съедено)

Переменные
- цвет (устанавливается при создании объекта случайным)
- дата появления (устанавливается при создании объекта случайным unixTmeStamp)
- дата падения (устанавливается при падении объекта с дерева)
- статус (на дереве / упало)
- сколько съели (%)
- другие необходимые переменные, для определения состояния.

Состояния
- висит на дереве
- упало/лежит на земле
- гнилое яблоко

Условия
- Пока висит на дереве - испортиться не может.
- Когда висит на дереве - съесть не получится.
- После лежания 5 часов - портится.
- Когда испорчено - съесть не получится.
- Когда съедено - удаляется из массива яблок.

Пример результирующего скрипта:
```
$apple = new Apple('green');

echo $apple->color; // green

$apple->eat(50); // Бросить исключение - Съесть нельзя, яблоко на дереве
echo $apple->size; // 1 - decimal

$apple->fallToGround(); // упасть на землю
$apple->eat(25); // откусить четверть яблока
echo $apple->size; // 0,75
```

На странице в приложении должны быть отображены все яблоки, которые 
на этой же странице можно сгенерировать в случайном кол-ве 
соответствующей кнопкой.

Рядом с каждым яблоком должны быть реализованы кнопки или формы 
соответствующие функциям (упасть, съесть  процент…) в задании.

Задача не имеет каких-либо ограничений и требований. Все подходы к 
ее решению определяют способность выбора правильного алгоритма при 
проектировании системы и умение предусмотреть любые возможности 
развития алгоритма. Задание должно быть выложено в репозиторий на 
gitHub, с сохранением истории коммитов. Креативность только 
приветствуется.
