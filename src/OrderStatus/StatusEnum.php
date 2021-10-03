<?php

namespace Codeman\Admin\OrderStatus;

class StatusEnum
{
    const STATUSES = [
        "CANCELED_BY_YOU" => "Отменен вами",
        "CANCELED_BY_US" => "Отменен нами",
        "PAID" => "Оплачен",
        "NOT_PAID" => "Неоплачен",
        "SHIPPED" => "Отгружен",
        "SHIPPED_PICKUP" => "Отгружен-Самовывоз",
        "DELIVERED" => "Доставлен",
        "DELIVERED_PICKUP" => "Доставлен-Самовывоз",
        "RETURNED" => "Возвращены",
        "RETURNED_PARTIALLY" => "Возвращены частично",
        "STORE_SHOPPING" => "Покупки в магазине",
        "CONFIRMED" => "Подтвержден",
    ];

    const SUBSCRIPTION_STATUSES = [
        "SUBSCRIBED_PRODUCT" => "Сообщить о поступлении",
    ];

    const USER_ACTION_STATUSES = [
        'NEW_REGISTRATION_ADMIN' => 'Новая регистрация(Admin)',
        'NEW_REGISTRATION_USER' => 'Новая регистрация(User)',
        'UPDATE_PASSWORD' => 'Изменения пароля',
        'UPDATE_EMAIL' => 'Изменения адрес электронной почты',
    ];

    const ORDER_STATUS_LABEL_CLASSES = [
        "CANCELED_BY_YOU" => 'label label-danger',
        "CANCELED_BY_US" => 'label label-danger',
        "PAID" => 'label label-success',
        "NOT_PAID" => 'label label-warning',
        "SHIPPED" => 'label label-info',
        "SHIPPED_PICKUP" => 'label label-info',
        "DELIVERED" => 'label label-success',
        "DELIVERED_PICKUP" => 'label label-info',
        "RETURNED" => 'label label-danger',
        "RETURNED_PARTIALLY" => 'label label-warning',
        "STORE_SHOPPING" => 'label label-info',
        "CONFIRMED" => 'label label-info',
    ];

    const ORDER_STATUS_TEXT_COLOR = [
        "CANCELED_BY_YOU" => 'text-danger',
        "CANCELED_BY_US" => 'text-danger',
        "PAID" => 'text-success',
        "NOT_PAID" => 'text-danger',
        "SHIPPED" => 'text-success',
        "SHIPPED_PICKUP" => 'text-success',
        "DELIVERED" => 'text-success',
        "DELIVERED_PICKUP" => 'text-success',
        "RETURNED" => 'text-danger',
        "RETURNED_PARTIALLY" => 'text-warning',
        "STORE_SHOPPING" => 'text-info',
        "CONFIRMED" => 'text-warning',
    ];

    const ORDER_ACTION_BUTTONS = [

        'change' => [
            'text' => 'Изменить',
            'href' => '/account/profile/edit_order/order/',
            'class_name' => 'orange hover-color-white'
        ],
        'repeat' => [
            'text' => 'Повторить',
            'href' => 'profile/repeat/order/',
            'class_name' => 'repeat hover-color-white'
        ],
        'info' => [
            'text' => 'Информация',
            'href' => 'profile/info/order/',
            'class_name' => 'grey  hover-color-white order_info'
        ],
        'track' => [
            'text' => 'Отслеживание',
            'href' => '/account/tracking/',
            'class_name' => 'blue hover-color-white'
        ],
        'cancel' => [
            'text' => 'Отменить',
            'href' => 'javascript:void(0)',
            'class_name' => 'red hover-color-white order_cancel'
        ],
        'return' => [
            'text' => 'Возврат',
            'href' => 'profile/make_return/order/',
            'id' => 'order_return',
            'class_name' => 'red hover-color-white return_order',
        ],
        'pay' => [
            'text' => 'Оплатить',
            'href' => 'javascript:void(0)',
            'class_name' => 'green hover-color-white order_make_payment'
        ],
    ];

    const ORDER_STATUS_BUTTONS = [
        "CANCELED_BY_YOU" => [
            self::ORDER_ACTION_BUTTONS['change'],
            self::ORDER_ACTION_BUTTONS['repeat'],
        ],
        "CANCELED_BY_US" => [
            self::ORDER_ACTION_BUTTONS['change'],
            self::ORDER_ACTION_BUTTONS['info'],
            self::ORDER_ACTION_BUTTONS['repeat'],
        ],
        "PAID" => [
            self::ORDER_ACTION_BUTTONS['track'],
            self::ORDER_ACTION_BUTTONS['cancel'],
        ],
        "NOT_PAID" => [
            self::ORDER_ACTION_BUTTONS['pay'],
            self::ORDER_ACTION_BUTTONS['cancel'],
        ],
        "CONFIRMED"  => [
            self::ORDER_ACTION_BUTTONS['pay'],
            self::ORDER_ACTION_BUTTONS['cancel'],
        ],
        "SHIPPED" => [
            self::ORDER_ACTION_BUTTONS['track'],
        ],
        "SHIPPED_PICKUP" => [
            self::ORDER_ACTION_BUTTONS['info'],
        ],
        "DELIVERED" => [
            self::ORDER_ACTION_BUTTONS['track'],
            self::ORDER_ACTION_BUTTONS['return'],
        ],
        "DELIVERED_PICKUP" => [
            self::ORDER_ACTION_BUTTONS['info'],
        ],
        "RETURNED" => [
            self::ORDER_ACTION_BUTTONS['track'],
        ],
        "RETURNED_PARTIALLY" => [
            self::ORDER_ACTION_BUTTONS['track'],
            self::ORDER_ACTION_BUTTONS['return'],
        ],
        "STORE_SHOPPING" => [

        ],

    ];


}
