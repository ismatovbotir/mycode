<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;

class MoySkladTokenTutorial extends Component
{
    public int $currentStep = 1;
    public string $language = 'ru';

    public function mount(): void
    {
        $this->language = auth()->user()?->lang ?? 'ru';
    }

    public function nextStep(): void
    {
        if ($this->currentStep < 5) {
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep(int $step): void
    {
        if ($step >= 1 && $step <= 5) {
            $this->currentStep = $step;
        }
    }

    public function setLanguage(string $lang): void
    {
        if (in_array($lang, ['uz', 'kk', 'kz', 'tj', 'ru'])) {
            $this->language = $lang;
        }
    }

    public function getStepData(): array
    {
        $steps = [
            1 => [
                'ru' => [
                    'title' => 'Шаг 1: Откройте МойСклад',
                    'description' => 'Зайдите на сайт МойСклад и авторизуйтесь в своем аккаунте. Используйте ваши учетные данные.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.01.png',
                ],
                'uz' => [
                    'title' => 'Qadam 1: MoySklad\'ni oching',
                    'description' => 'MoySklad saytiga o\'ting va o\'z akkauntingizga kiring. O\'z autentifikatsiya ma\'lumotlaringizdan foydalaning.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.01.png',
                ],
                'kk' => [
                    'title' => 'Қадам 1: МойСклад ашыңыз',
                    'description' => 'МойСклад сайтына барып, өз есептік жазбаңызға кіріңіз. Өз ластық мәліметтеріңізді пайдаланыңыз.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.01.png',
                ],
                'kz' => [
                    'title' => 'Қадам 1: МойСклад ашыңыз',
                    'description' => 'МойСклад сайтына барып, өз есептік жазбаңызға кіріңіз. Өз ластық мәліметтеріңізді пайдаланыңыз.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.01.png',
                ],
                'tj' => [
                    'title' => 'Қадами 1: МойСклад-ро кушоед',
                    'description' => 'Ба сайти МойСклад рафта, ба ҳисоби худ ворид шавед. Маълумоти ба худ расидаи худро истифода баред.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.01.png',
                ],
            ],
            2 => [
                'ru' => [
                    'title' => 'Шаг 2: Перейдите в меню Токены',
                    'description' => 'Нажмите на меню в левой части экрана и найдите раздел "Токены" (может быть в разделе "Настройки").',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.06.png',
                ],
                'uz' => [
                    'title' => 'Qadam 2: Tokenlar menyusiga o\'tish',
                    'description' => 'Ekranning chap tomonidagi menyuni bosing va "Tokenlar" bo\'limini toping (u "Sozlamalar" bo\'limida bo\'lishi mumkin).',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.06.png',
                ],
                'kk' => [
                    'title' => 'Қадам 2: Токендер мәзіріне өтіңіз',
                    'description' => 'Экран сол жағындағы мәзірді басыңыз және "Токендер" бөлімін табыңыз (ол "Параметрлер" бөлімінде болуы мүмкін).',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.06.png',
                ],
                'kz' => [
                    'title' => 'Қадам 2: Токендер мәзіріне өтіңіз',
                    'description' => 'Экран сол жағындағы мәзірді басыңыз және "Токендер" бөлімін табыңыз (ол "Параметрлер" бөлімінде болуы мүмкін).',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.06.png',
                ],
                'tj' => [
                    'title' => 'Қадами 2: Ба мени Токенҳо рафта',
                    'description' => 'Менюи сол тарафи экранро зер занед ва "Токенҳо" -ро ёбед (дар "Танзимот" метавон буд).',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.06.png',
                ],
            ],
            3 => [
                'ru' => [
                    'title' => 'Шаг 3: Перейдите на вкладку Токены',
                    'description' => 'В левом меню найдите "Токены" и нажмите на нее. Откроется список всех ваших токенов.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.14.png',
                ],
                'uz' => [
                    'title' => 'Qadam 3: Tokenlar tabiga o\'tish',
                    'description' => '"Tokenlar" ni chap menyuda toping va bosing. Barcha tokenlaringizning ro\'yxati ochiladi.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.14.png',
                ],
                'kk' => [
                    'title' => 'Қадам 3: Токендер табына өтіңіз',
                    'description' => 'Сол мәзірден "Токендер" табын табыңыз және басыңыз. Сіздің барлық токендеріңіздің тізімі ашылады.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.14.png',
                ],
                'kz' => [
                    'title' => 'Қадам 3: Токендер табына өтіңіз',
                    'description' => 'Сол мәзірден "Токендер" табын табыңыз және басыңыз. Сіздің барлық токендеріңіздің тізімі ашылады.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.14.png',
                ],
                'tj' => [
                    'title' => 'Қадами 3: Ба табари Токенҳо рафта',
                    'description' => 'Дар менюи чап "Токенҳо" -ро ёбед ва зер занед. Фахристи ҳамаи токенҳои шумо кушода мешавад.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.14.png',
                ],
            ],
            4 => [
                'ru' => [
                    'title' => 'Шаг 4: Создайте новый токен',
                    'description' => 'Нажмите кнопку "Создать токен" (или "+"). Откроется диалоговое окно для создания нового API-токена.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.26.png',
                ],
                'uz' => [
                    'title' => 'Qadam 4: Yangi token yarating',
                    'description' => '"Yangi token yaratish" tugmasini bosing (yoki "+"). Yangi API-token yaratish uchun dialogs oynasi ochiladi.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.26.png',
                ],
                'kk' => [
                    'title' => 'Қадам 4: Жаңа токен құрыңыз',
                    'description' => '"Жаңа токен құру" батырмасын басыңыз (немесе "+"). Жаңа API-токен құру үшін диалог терезесі ашылады.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.26.png',
                ],
                'kz' => [
                    'title' => 'Қадам 4: Жаңа токен құрыңыз',
                    'description' => '"Жаңа токен құру" батырмасын басыңыз (немесе "+"). Жаңа API-токен құру үшін диалог терезесі ашылады.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.26.png',
                ],
                'tj' => [
                    'title' => 'Қадами 4: Токени нав эҷод кунед',
                    'description' => '"Токени нав эҷод кунед" батни зер занед (ё "+"). Дарозачи гуфтугӯ барои эҷоди токени API-и нав кушода мешавад.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.26.png',
                ],
            ],
            5 => [
                'ru' => [
                    'title' => 'Шаг 5: Скопируйте токен',
                    'description' => '⚠️ ВАЖНО: Токен можно копировать только ОДИН РАЗ! Сразу же скопируйте весь токен (нажмите на иконку копирования). Сохраните его в безопасном месте. Вставьте скопированный токен в поле ниже.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.29.png',
                ],
                'uz' => [
                    'title' => 'Qadam 5: Tokenni nusxa oling',
                    'description' => '⚠️ MUHIM: Tokenni faqat BIR MARTA nusxa olish mumkin! Darhol butun tokenni nusxa oling (nusxa qilish ikonkasini bosing). Uni xavfsiz joyda saqlang. Nusxa olingan tokenni quyidagi maydonga qo\'ying.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.29.png',
                ],
                'kk' => [
                    'title' => 'Қадам 5: Токенді көшіріңіз',
                    'description' => '⚠️ МАҢЫЗДЫ: Токенді тек БІР РЕТ көшіруге болады! Одан әрі бүкіл токенді көшіріңіз (көшіру белгісін басыңыз). Оны қауіпсіз жерде сақтаңыз. Көшірілген токенді төмендегі өрістеге орналастырыңыз.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.29.png',
                ],
                'kz' => [
                    'title' => 'Қадам 5: Токенді көшіріңіз',
                    'description' => '⚠️ МАҢЫЗДЫ: Токенді тек БІР РЕТ көшіруге болады! Одан әрі бүкіл токенді көшіріңіз (көшіру белгісін басыңыз). Оны қауіпсіз жерде сақтаңыз. Көшірілген токенді төмендегі өрістеге орналастырыңыз.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.29.png',
                ],
                'tj' => [
                    'title' => 'Қадами 5: Токенро нусха кунед',
                    'description' => '⚠️ МӮҲИМ: Токенро танҳо БІР МАРТА нусха кардан мумкин! Дарухам ҳамаи токенро нусха кунед (нишонаи нусхакуниро зер занед). Онро дар ҷои бехатар нигоҳ дорид. Токени нусхакардашударо дар қаторҳои зер қўл созед.',
                    'image' => '/images/moysklad-tutorial/Screenshot 2026-06-01 at 21.35.29.png',
                ],
            ],
        ];

        return $steps[$this->currentStep][$this->language] ?? $steps[$this->currentStep]['ru'];
    }

    public function render()
    {
        return view('livewire.moysklad-token-tutorial', [
            'stepData' => $this->getStepData(),
            'totalSteps' => 5,
        ]);
    }
}
