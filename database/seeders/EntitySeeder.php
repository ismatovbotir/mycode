<?php
// database/seeders/EntitySeeder.php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Entity;
use Illuminate\Database\Seeder;

class EntitySeeder extends Seeder
{
    public function run(): void
    {
        $entities = [
            ['type' => 'supply', 'translations' => ['uz' => 'Qabul qilish', 'en' => 'Supply', 'ru' => 'Приход'], 'messages' => ['uz' => 'Yangi qabul', 'en' => 'New supply', 'ru' => 'Новый приход']],
            ['type' => 'demand', 'translations' => ['uz' => 'Sotuv', 'en' => 'Demand', 'ru' => 'Продажа'], 'messages' => ['uz' => 'Yangi sotuv', 'en' => 'New demand', 'ru' => 'Новая продажа']],
            ['type' => 'customerorder', 'translations' => ['uz' => 'Mijoz buyurtmasi', 'en' => 'Customer Order', 'ru' => 'Заказ клиента'], 'messages' => ['uz' => 'Yangi buyurtma', 'en' => 'New order', 'ru' => 'Новый заказ']],
            ['type' => 'purchaseorder', 'translations' => ['uz' => 'Sotib olish buyurtmasi', 'en' => 'Purchase Order', 'ru' => 'Заказ поставщику'], 'messages' => ['uz' => 'Yangi sotib olish', 'en' => 'New purchase', 'ru' => 'Новая закупка']],
            ['type' => 'salesreturn', 'translations' => ['uz' => 'Sotuv qaytarish', 'en' => 'Sales Return', 'ru' => 'Возврат продажи'], 'messages' => ['uz' => 'Sotuv qaytarildi', 'en' => 'Sale returned', 'ru' => 'Продажа возвращена']],
            ['type' => 'purchasereturn', 'translations' => ['uz' => 'Sotib olish qaytarish', 'en' => 'Purchase Return', 'ru' => 'Возврат закупки'], 'messages' => ['uz' => 'Sotib olish qaytarildi', 'en' => 'Purchase returned', 'ru' => 'Закупка возвращена']],
            ['type' => 'invoiceout', 'translations' => ['uz' => 'Sotuv fakturasi', 'en' => 'Sales Invoice', 'ru' => 'Счет продажи'], 'messages' => ['uz' => 'Yangi faktura', 'en' => 'New invoice', 'ru' => 'Новый счет']],
            ['type' => 'invoicein', 'translations' => ['uz' => 'Sotib olish fakturasi', 'en' => 'Purchase Invoice', 'ru' => 'Счет поставщика'], 'messages' => ['uz' => 'Yangi faktura qabul qilindi', 'en' => 'Invoice received', 'ru' => 'Счет получен']],
            ['type' => 'factureout', 'translations' => ['uz' => 'Sotuv fakturasi (Faktura)', 'en' => 'Sales Facture', 'ru' => 'Фактура продажи'], 'messages' => ['uz' => 'Yangi faktura', 'en' => 'New facture', 'ru' => 'Новая фактура']],
            ['type' => 'facturein', 'translations' => ['uz' => 'Sotib olish fakturasi (Faktura)', 'en' => 'Purchase Facture', 'ru' => 'Фактура поставщика'], 'messages' => ['uz' => 'Yangi faktura qabul qilindi', 'en' => 'Facture received', 'ru' => 'Фактура получена']],
            ['type' => 'paymentin', 'translations' => ['uz' => 'Kiruvchi toʻlov', 'en' => 'Incoming Payment', 'ru' => 'Входящий платеж'], 'messages' => ['uz' => 'Toʻlov qabul qilindi', 'en' => 'Payment received', 'ru' => 'Платеж получен']],
            ['type' => 'paymentout', 'translations' => ['uz' => 'Chiquvchi toʻlov', 'en' => 'Outgoing Payment', 'ru' => 'Исходящий платеж'], 'messages' => ['uz' => 'Toʻlov amalga oshirildi', 'en' => 'Payment sent', 'ru' => 'Платеж отправлен']],
            ['type' => 'move', 'translations' => ['uz' => 'Qora harakati', 'en' => 'Stock Movement', 'ru' => 'Перемещение товара'], 'messages' => ['uz' => 'Yangi harakati', 'en' => 'New movement', 'ru' => 'Новое перемещение']],
            ['type' => 'loss', 'translations' => ['uz' => 'Ziyonlik', 'en' => 'Loss', 'ru' => 'Убыток'], 'messages' => ['uz' => 'Yangi ziyonlik', 'en' => 'New loss', 'ru' => 'Новый убыток']],
            ['type' => 'processing', 'translations' => ['uz' => 'Ishlov berish', 'en' => 'Processing', 'ru' => 'Обработка'], 'messages' => ['uz' => 'Yangi ishlov berish', 'en' => 'New processing', 'ru' => 'Новая обработка']],
            ['type' => 'retaildemand', 'translations' => ['uz' => 'Chakana sotuv', 'en' => 'Retail Demand', 'ru' => 'Розница'], 'messages' => ['uz' => 'Yangi chakana sotuv', 'en' => 'New retail sale', 'ru' => 'Новая розница']],
            ['type' => 'retailshift', 'translations' => ['uz' => 'Chakana smena', 'en' => 'Retail Shift', 'ru' => 'Розничная смена'], 'messages' => ['uz' => 'Yangi smena', 'en' => 'New shift', 'ru' => 'Новая смена']],
            ['type' => 'retaildrawercashin', 'translations' => ['uz' => 'Kassa kashni pul kiritish', 'en' => 'Drawer Cash In', 'ru' => 'Касса приход'], 'messages' => ['uz' => 'Pul kiritildi', 'en' => 'Money added', 'ru' => 'Деньги внесены']],
            ['type' => 'retaildrawercashout', 'translations' => ['uz' => 'Kassa kashni pul chiqarish', 'en' => 'Drawer Cash Out', 'ru' => 'Касса расход'], 'messages' => ['uz' => 'Pul chiqarildi', 'en' => 'Money withdrawn', 'ru' => 'Деньги изъяты']],
            ['type' => 'retailsalesreturn', 'translations' => ['uz' => 'Chakana sotuv qaytarish', 'en' => 'Retail Sales Return', 'ru' => 'Возврат розницы'], 'messages' => ['uz' => 'Qaytarish qabul qilindi', 'en' => 'Return accepted', 'ru' => 'Возврат принят']],
            ['type' => 'counterparty', 'translations' => ['uz' => 'Qarshi tomon', 'en' => 'Counterparty', 'ru' => 'Контрагент'], 'messages' => ['uz' => 'Yangi qarshi tomon', 'en' => 'New counterparty', 'ru' => 'Новый контрагент']],
            ['type' => 'product', 'translations' => ['uz' => 'Mahsulot', 'en' => 'Product', 'ru' => 'Товар'], 'messages' => ['uz' => 'Yangi mahsulot', 'en' => 'New product', 'ru' => 'Новый товар']],
            ['type' => 'variant', 'translations' => ['uz' => 'Mahsulot varianti', 'en' => 'Product Variant', 'ru' => 'Вариант товара'], 'messages' => ['uz' => 'Yangi variant', 'en' => 'New variant', 'ru' => 'Новый вариант']],
            ['type' => 'productfolder', 'translations' => ['uz' => 'Mahsulot papkasi', 'en' => 'Product Folder', 'ru' => 'Категория товара'], 'messages' => ['uz' => 'Yangi papka', 'en' => 'New folder', 'ru' => 'Новая категория']],
            ['type' => 'assortment', 'translations' => ['uz' => 'Assortiment', 'en' => 'Assortment', 'ru' => 'Ассортимент'], 'messages' => ['uz' => 'Yangi assortiment', 'en' => 'New assortment', 'ru' => 'Новый ассортимент']],
            ['type' => 'store', 'translations' => ['uz' => 'Omborxona', 'en' => 'Store', 'ru' => 'Склад'], 'messages' => ['uz' => 'Yangi omborxona', 'en' => 'New store', 'ru' => 'Новый склад']],
            ['type' => 'organization', 'translations' => ['uz' => 'Tashkilot', 'en' => 'Organization', 'ru' => 'Организация'], 'messages' => ['uz' => 'Yangi tashkilot', 'en' => 'New organization', 'ru' => 'Новая организация']],
            ['type' => 'employee', 'translations' => ['uz' => 'Ishchi', 'en' => 'Employee', 'ru' => 'Сотрудник'], 'messages' => ['uz' => 'Yangi ishchi', 'en' => 'New employee', 'ru' => 'Новый сотрудник']],
            ['type' => 'contract', 'translations' => ['uz' => 'Shartnoma', 'en' => 'Contract', 'ru' => 'Договор'], 'messages' => ['uz' => 'Yangi shartnoma', 'en' => 'New contract', 'ru' => 'Новый договор']],
            ['type' => 'project', 'translations' => ['uz' => 'Loyiha', 'en' => 'Project', 'ru' => 'Проект'], 'messages' => ['uz' => 'Yangi loyiha', 'en' => 'New project', 'ru' => 'Новый проект']],
            ['type' => 'enter', 'translations' => ['uz' => 'Kirish (Eski)', 'en' => 'Enter (Legacy)', 'ru' => 'Ввод (Старое)'], 'messages' => ['uz' => 'Yangi kirish', 'en' => 'New entry', 'ru' => 'Новый ввод']],
            ['type' => 'processingorder', 'translations' => ['uz' => 'Ishlov berish buyurtmasi', 'en' => 'Processing Order', 'ru' => 'Заказ на обработку'], 'messages' => ['uz' => 'Yangi buyurtma', 'en' => 'New order', 'ru' => 'Новый заказ']],
            ['type' => 'processingplan', 'translations' => ['uz' => 'Ishlov berish rejasi', 'en' => 'Processing Plan', 'ru' => 'План обработки'], 'messages' => ['uz' => 'Yangi reja', 'en' => 'New plan', 'ru' => 'Новый план']],
            ['type' => 'processingplanfolder', 'translations' => ['uz' => 'Ishlov berish rejasi papkasi', 'en' => 'Processing Plan Folder', 'ru' => 'Папка плана обработки'], 'messages' => ['uz' => 'Yangi papka', 'en' => 'New folder', 'ru' => 'Новая папка']],
            ['type' => 'productiontask', 'translations' => ['uz' => 'Ishlab chiqarish vazifasi', 'en' => 'Production Task', 'ru' => 'Производственная задача'], 'messages' => ['uz' => 'Yangi vazifa', 'en' => 'New task', 'ru' => 'Новая задача']],
            ['type' => 'productionstagecompletion', 'translations' => ['uz' => 'Ishlab chiqarish bosqichining tugatilishi', 'en' => 'Production Stage Completion', 'ru' => 'Завершение этапа производства'], 'messages' => ['uz' => 'Bosqich tugatildi', 'en' => 'Stage completed', 'ru' => 'Этап завершен']],
            ['type' => 'emissionorder', 'translations' => ['uz' => 'Emissiyon buyurtmasi', 'en' => 'Emission Order', 'ru' => 'Заказ эмиссии'], 'messages' => ['uz' => 'Yangi buyurtma', 'en' => 'New order', 'ru' => 'Новый заказ']],
            ['type' => 'internalorder', 'translations' => ['uz' => 'Ichki buyurtma', 'en' => 'Internal Order', 'ru' => 'Внутренний заказ'], 'messages' => ['uz' => 'Yangi buyurtma', 'en' => 'New order', 'ru' => 'Новый заказ']],
            ['type' => 'prepayment', 'translations' => ['uz' => 'Avans toʻlov', 'en' => 'Prepayment', 'ru' => 'Авансовый платеж'], 'messages' => ['uz' => 'Yangi avans', 'en' => 'New prepayment', 'ru' => 'Новый авансовый платеж']],
            ['type' => 'prepaymentreturn', 'translations' => ['uz' => 'Avans qaytarish', 'en' => 'Prepayment Return', 'ru' => 'Возврат авансового платежа'], 'messages' => ['uz' => 'Avans qaytarildi', 'en' => 'Prepayment returned', 'ru' => 'Авансовый платеж возвращен']],
            ['type' => 'crptorder', 'translations' => ['uz' => 'CRPT buyurtmasi', 'en' => 'CRPT Order', 'ru' => 'Заказ CRPT'], 'messages' => ['uz' => 'Yangi buyurtma', 'en' => 'New order', 'ru' => 'Новый заказ']],
            ['type' => 'commissionreportin', 'translations' => ['uz' => 'Komissiya hisobi (kirish)', 'en' => 'Commission Report In', 'ru' => 'Комиссионный отчет (входящий)'], 'messages' => ['uz' => 'Yangi hisobi qabul qilindi', 'en' => 'Report received', 'ru' => 'Отчет получен']],
            ['type' => 'commissionreportout', 'translations' => ['uz' => 'Komissiya hisobi (chiqish)', 'en' => 'Commission Report Out', 'ru' => 'Комиссионный отчет (исходящий)'], 'messages' => ['uz' => 'Yangi hisobi chiqarildi', 'en' => 'Report sent', 'ru' => 'Отчет составлен']],
            ['type' => 'pricelist', 'translations' => ['uz' => 'Narxlar roʻyxati', 'en' => 'Price List', 'ru' => 'Прайс-лист'], 'messages' => ['uz' => 'Yangi narxlar', 'en' => 'New prices', 'ru' => 'Новый прайс-лист']],
            ['type' => 'consignment', 'translations' => ['uz' => 'Konsignatsiya', 'en' => 'Consignment', 'ru' => 'Консигнация'], 'messages' => ['uz' => 'Yangi konsignatsiya', 'en' => 'New consignment', 'ru' => 'Новая консигнация']],
            ['type' => 'inventory', 'translations' => ['uz' => 'Inventarizatsiya', 'en' => 'Inventory', 'ru' => 'Инвентаризация'], 'messages' => ['uz' => 'Yangi inventarizatsiya', 'en' => 'New inventory', 'ru' => 'Новая инвентаризация']],
            ['type' => 'bonusprogram', 'translations' => ['uz' => 'Bonus dasturi', 'en' => 'Bonus Program', 'ru' => 'Программа бонусов'], 'messages' => ['uz' => 'Yangi bonus dasturi', 'en' => 'New program', 'ru' => 'Новая программа бонусов']],
            ['type' => 'bonustransaction', 'translations' => ['uz' => 'Bonus tranzaksiyasi', 'en' => 'Bonus Transaction', 'ru' => 'Бонусная операция'], 'messages' => ['uz' => 'Yangi bonus tranzaksiyasi', 'en' => 'New transaction', 'ru' => 'Новая бонусная операция']],
            ['type' => 'thing', 'translations' => ['uz' => 'Narsa', 'en' => 'Thing', 'ru' => 'Объект'], 'messages' => ['uz' => 'Yangi narsa', 'en' => 'New thing', 'ru' => 'Новый объект']],
            ['type' => 'counterpartyadjustment', 'translations' => ['uz' => 'Qarshi tomonni koʻchirish', 'en' => 'Counterparty Adjustment', 'ru' => 'Корректировка контрагента'], 'messages' => ['uz' => 'Yangi koʻchirish', 'en' => 'New adjustment', 'ru' => 'Новая корректировка']],
            ['type' => 'payroll', 'translations' => ['uz' => 'Oylik pul', 'en' => 'Payroll', 'ru' => 'Расчетный лист'], 'messages' => ['uz' => 'Yangi pul', 'en' => 'New payroll', 'ru' => 'Новый расчетный лист']],
            ['type' => 'cashin', 'translations' => ['uz' => 'Pul kiritish', 'en' => 'Cash In', 'ru' => 'Приход денег'], 'messages' => ['uz' => 'Pul kiritildi', 'en' => 'Cash received', 'ru' => 'Деньги внесены']],
            ['type' => 'cashout', 'translations' => ['uz' => 'Pul chiqarish', 'en' => 'Cash Out', 'ru' => 'Расход денег'], 'messages' => ['uz' => 'Pul chiqarildi', 'en' => 'Cash withdrawn', 'ru' => 'Деньги изъяты']],
            ['type' => 'retireorder', 'translations' => ['uz' => 'Oʻchirib qoʻyish buyurtmasi', 'en' => 'Retire Order', 'ru' => 'Заказ на списание'], 'messages' => ['uz' => 'Yangi buyurtma', 'en' => 'New order', 'ru' => 'Новый заказ']],
            ['type' => 'application', 'translations' => ['uz' => 'Ilova', 'en' => 'Application', 'ru' => 'Приложение'], 'messages' => ['uz' => 'Yangi ilova', 'en' => 'New application', 'ru' => 'Новое приложение']],
            ['type' => 'companysettings', 'translations' => ['uz' => 'Kompaniya sozlamalari', 'en' => 'Company Settings', 'ru' => 'Параметры компании'], 'messages' => ['uz' => 'Sozlamalar oʻzgartirildi', 'en' => 'Settings changed', 'ru' => 'Параметры изменены']],
        ];

        foreach ($entities as $entity) {
            Entity::updateOrCreate(
                ['type' => $entity['type']],
                [
                    'translations' => $entity['translations'],
                    'messages' => $entity['messages'],
                ]
            );
        }
    }
}
