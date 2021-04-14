<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;
use fecshop\queue\job\SendEmailJob;
use fecshop\elasticsearch\models\elasticSearch\Product;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class TestController extends AppfrontController
{
    //protected $_registerSuccessRedirectUrlKey = 'customer/account';

    public $enableCsrfValidation = false;

    /**
     * 
     */
    public function actionIndex()
    {
        //$filePath = Yii::getAlias('@fecshop/app/appfront/languages/zh-CN/appfront.php');
        
        $config = $this->getOrigin();
        //var_dump($config);
        foreach ($config as $k=>$v) {
            echo $v.'<br/>';
        }
        exit;
    }
    
    public function actionIndex2()
    {
        //$filePath = Yii::getAlias('@fecshop/app/appfront/languages/zh-CN/appfront.php');
        
        $config = $this->getOrigin();
        //var_dump($config);
        foreach ($config as $k=>$v) {
            echo $k.'<br/>';
        }
        exit;
    }
    
    public function getOrigin()
    {
        $filePath = Yii::getAlias('@fecshop/app/apphtml5/languages/zh-CN/apphtml5.php');
        
        $config = require($filePath);
        
        return $config;
    }
    
    public function actionGetarr()
    {
        
        $arr1 = $this->getOrigin();
        $arr2 = $this->getArr1();
        echo count($arr1).'<br/>';
        echo count($arr2).'<br/>';
        //var_dump($arr2);
        
        $i = 0;
        foreach ($arr1 as $k=>$v) {
            $arr1[$k] = $arr2[$i];
            $i++;
        }
        $this->echoArr($arr1);exit;
        // var_dump($arr1);exit;
    }
    
    public function echoArr($arr)
    {
        echo ' return [<br>';
        foreach ($arr as $k=>$v) {
            if (strstr($k, "'")) {
                $k = str_replace("'", "\'", $k);
            }
            if (strstr($v, "'")) {
                $v = str_replace("'", "\'", $v);
            }
            echo "&nbsp;&nbsp;&nbsp;&nbsp;'".$k."' => '".$v."',<br>";
        }
        echo '
            <br>
        ];
        ';
    }
    
    protected function getArr1()
    {
        $str = "
        Fecshop
Введите свой адрес электронной почты
Свяжитесь с нами
Корзина покупателя
Политика возврата
Политика конфиденциальности
О нас
Моя любимая
Мои обзоры
Мой заказ
Мой аккаунт
Карта сайта
Captcha не может быть пустым
Язык
Валюта
Добро пожаловать!
Выйти
Войти / Присоединиться бесплатно
мои заказы
Мои любимые
Мой обзор
Ключевое слово продуктов
индивидуальное меню
мое собственное меню 2
мое собственное меню 3
Дом
бестселлер
избранные продукты
более
Сортировать по
Сортировать
Фильтр
Горячий
Рассмотрение
Любимый
Новый
Снизу вверх
По убыванию
Уточнить по
очистить все
стиль
длина платья
Сексуальный и клуб
Войти или Создать аккаунт
новые клиенты
Создав учетную запись в нашем магазине, вы сможете быстрее проходить процесс оформления заказа, сохранять несколько адресов доставки, просматривать и отслеживать свои заказы в своей учетной записи и многое другое.
регистр
Авторизоваться
Электронная почта
Зарегистрированные клиенты
Если у вас есть учетная запись на нашем сайте, пожалуйста, войдите в систему.
Адрес электронной почты
Пароль
Капча
нажмите обновить
Войти
Забыли Ваш пароль?
пароль пользователя неверный
ОШИБКА, на ваш адрес электронной почты есть подписка, пожалуйста, не повторяйте подписку
адрес электронной почты информационного бюллетеня пуст
Неправильный формат адреса электронной почты!
Ваше электронное письмо с подпиской прошло успешно. Вы можете {urlB} нажать здесь, чтобы перейти на главную страницу {urlE}, Спасибо.
Завести аккаунт
Персональная информация
Имя
Фамилия
Подписаться на рассылку
Информация для входа
Подтвердить Пароль
Представлять на рассмотрение
Назад
Это поле обязательно для заполнения.
Пожалуйста, введите действительный адрес электронной почты. Например, johndoe@domain.com.
длина имени должна быть между
длина фамилии должна быть между
Пожалуйста, введите 6 или более символов. Начальные и конечные пробелы игнорируются.
Убедитесь, что ваши пароли совпадают.
Капча не правильная
Электронная почта не является действительным адресом электронной почты.
Мой Dashboard
Привет
На панели управления «Моя учетная запись» у вас есть возможность просмотреть моментальный снимок вашей недавней активности в учетной записи и обновить информацию о ней. Щелкните ссылку ниже, чтобы просмотреть или изменить информацию.
Контакты
Редактировать
Моя адресная книга
Вы можете управлять своим адресом
Адреса менеджеров
Вы можете просмотреть свой заказ
Вид
Панель управления учетной записью
Информация Об Учетной Записи
Адресная книга
Мои обзоры продуктов
Забыл пароль
Ваша электронная почта
Подтвердите свою личность, чтобы сбросить пароль
Отправить код авторизации
Сброс пароля
Сброс пароля успешно
Подтвердите свою личность, чтобы сбросить пароль. Если вы все еще не можете его найти, нажмите {logUrlB} центр поддержки {logUrlE}, чтобы получить помощь.
электронная почта не существует
Мы отправили сообщение на адрес электронной почты
Следуйте инструкциям в сообщении, чтобы сбросить пароль.
Не получили письмо от нас?
Проверьте папку с массовой или нежелательной электронной почтой.
нажмите здесь, чтобы повторить попытку
кликните сюда
Адрес электронной почты не существует, пожалуйста, {logUrlB} нажмите здесь {logUrlE}, чтобы ввести еще раз!
Выберите ваш новый пароль
Сброс учетной записи успешно, вы можете {logUrlB} нажать здесь {logUrlE}, чтобы войти.
Срок действия вашего токена сброса пароля истек. Вы можете {logUrlB} нажать здесь {logUrlE}, чтобы получить его
Редактировать аккаунт
Измени пароль
Текущий пароль
Новый пароль
Подтвердите новый пароль
изменить информацию об аккаунте успешно
Страна
Состояние
Город
улица1
улица # 2
Почтовый индекс
По умолчанию
Сохранить адрес
Вы должны заполнить все поля
Пожалуйста, выберите регион, штат или провинцию
Изменить адрес
Адрес клиента
Адрес
Операция
Удалить
По умолчанию
Добавить новый адрес
Изменить
Страница:
Изменение порядка
Посмотреть заказ
Заказ покупателя
Заказ #
Дата
Доставить
Весь заказ
Статус заказа
Заказ#
Дата заказа
в ожидании
подозреваемый_фрод
обработка
адреса доставки
Т:
Способ оплаты
способ доставки
Заказанные товары
наименование товара
Изображение продукта
Sku
Цена
Кол-во
Промежуточный итог
Стоимость доставки
Скидка
Общий итог
Информация о продукте
Ваш отзыв принят.
Ваш отзыв отклонен.
Ваш отзыв ожидает модерации ...
Любимая дата:
Имя
телефон
Комментарий
Контакты
Свяжитесь с нами Отправить успех
Мой цвет:
Мой размер:
Мой размер2:
Мой размер3:
Цвет:
цвет:
цвет
размер
Размер:
одноцветный
красный
белый
чернить
синий
зеленый
желтый
серый
цвет хаки
слоновая кость
бежевый
апельсин
голубой
леопард
камуфляж
серебро
розовый
фиолетовый
коричневый
золотой
многоцветный
бело-синий
белый черный
цена
Вы уже добавили в избранное этот продукт
Средний рейтинг
отзывы
Код продукта:
Кол-во:
Добавить в корзину
Добавить в избранное
Описание
Отзывы
Доставка и оплата
Покупатели, которые купили этот товар, также купили
масса
длинный
ширина
высокая
объемный вес
Способы оплаты:
FECSHOP.com принимает PayPal, кредитную карту, Western Union и банковский перевод в качестве безопасных способов оплаты:
Глобальный:
1. PayPal
1) Войдите в свою учетную запись или воспользуйтесь кредитной картой Express.
2) Введите данные своей карты, заказ будет отправлен на ваш адрес PayPal. И нажмите «Отправить».
3) Ваш платеж будет обработан, и квитанция будет отправлена ​​на ваш почтовый ящик.
1) Выберите адрес доставки ИЛИ создайте новый.
2) Введите данные своей карты и нажмите «Отправить».
2. Кредитная карта
Показатель
Твое имя
Содержание обзора не может быть пустым
Резюме вашего обзора
Содержание вашего обзора
Резюме не может быть пустым
Средний рейтинг :
Обзор продукта
От
Ваш комментарий ожидает модерации
Добавить отзыв
Просмотреть все отзывы
Оптовые цены:
Цена:
Резюме
Показывать на странице:
Результаты поиска для \"{searchText}\" не возвращают результатов
Ваша корзина пуста
Начни делать покупки прямо сейчас!
Пожалуйста, {urlB} войдите в систему {urlE}, чтобы просмотреть продукты, которые вы ранее добавили в корзину.
У вас нет товаров в вашем любимом.
Вы не отправили ни одного отзыва
Цена за единицу
Коды скидок
Введите код купона, если он у вас есть.
Отменить купон
Добавить купон
Приборная панель
ИЛИ ЖЕ
Приступить к оплате
Купон недоступен или срок его действия истек
ошибка добавления купона
Купон нельзя использовать, если сумма товара в корзине меньше {conditions} долларов.
Проверить
Добро пожаловать на кассу! Заполните поля ниже, чтобы завершить покупку.
Уже зарегистрирован? Нажмите здесь, чтобы войти
Коды купонов (необязательно)
Сделайте заказ сейчас
Подождите, идет обработка вашего заказа ...
Создайте учетную запись для последующего использования
Платежный адрес
улица
Новый адрес
Проверьте / Денежный перевод
товар: [{product_name}] нет в наличии
Оффлайн денежные платежи
Стандарт оплаты через веб-сайт PayPal
Вы будете перенаправлены на веб-сайт PayPal, когда разместите заказ.
Бесплатная доставка (7-20 рабочих дней)
Быстрая доставка (5-10 рабочих дней)
Просмотрите заказ
вы должны войти в свою учетную запись, прежде чем использовать купон
адрес электронной почты пуст, необходимо заполнить электронную почту
формат адреса электронной почты неверный
Этот адрес электронной почты зарегистрирован, вы должны заполнить еще один адрес электронной почты
Длина пароля должна быть больше или равна {passwdMinLength}.
Длина пароля должна быть меньше или равна {passwdMaxLength}.
Пароли несовместимы
Следовать
этот адрес электронной почты существует!
Нажмите здесь, если вас не перенаправят в течение 10 секунд ...
Вы будете перенаправлены на сайт PayPal через несколько секунд ...
Продолжить покупки
Ваш заказ получен, спасибо за покупку!
Ваш заказ №:
Вы получите электронное письмо с подтверждением заказа с подробной информацией о вашем заказе и ссылкой для отслеживания его выполнения.
Мы не смогли найти эту страницу
Пожалуйста, свяжитесь с нами, если вы считаете, что это ошибка сервера. Спасибо.
Верни меня домой
Средний рейтинг
на основе {review_count} отзывов клиентов
5 звезд
4 звезды
3 звезды
2 звезды
1 звезды
Напишите отзыв клиента
купон пуст
успех купона cacle
купон не существует
добавить успешный купон
вы действительно хотите удалить этот адрес?
Товара нет в наличии
марка
стандарт Alipay
стандарт PayPal
проверить деньги
PayPal Экспресс
стандарт wxpay
wxpay jsapi
wxpay h5
Марка
        ";
        $arr = explode(PHP_EOL, $str);
        $arr1 = [];
        foreach ($arr as $k => $v) {
            $k1 = trim($k);
            $v1 = trim($v);
            
            if (!$k1 || !$v1) {
                
                continue;
            }
            $arr1[] = $v1;
        }
        
        return $arr1;
    }
    /*
    public function actionTest(){
        $src_file = Yii::getAlias('@addons/fecshop_theme_furnilife.zip');
        $dest_dir = Yii::getAlias('@addons/');
        Yii::$service->helper->zipFile->unzip($src_file, $dest_dir, true, false);
    }
    */
    
    public function actionTest(){
        //$remoteUrl = 'http://addons.server.fecmall.com/';
        //$url = $remoteUrl . 'customer/addons/downloada?namespace=fectfurnilife';
        
        //$this->downFile($url,$path)
    }
    
    function downFile($url,$path){
        $arr=parse_url($url);
        $fileName=basename($arr['path']);
        $file=file_get_contents($url);
        file_put_contents($path.$fileName,$file);
    }
    
    
    
    
    
    
    
    
    
    
}
