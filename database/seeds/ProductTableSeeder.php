<?php

use App\Enums\ProductTypeEnum;
use App\Product;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{

    protected $courses = [

        [
            "title" => "Мужской курс \"LONDОN\"",
            "price" =>2500,
            'type'=>ProductTypeEnum::LMACourses,
            "image" => "https://sun9-38.userapi.com/c850728/v850728456/c8164/0NrWXJh-07o.jpg",
            "description" =>
                'курс "LONDОN" для парней от 14 до 25 лет
-курс длится 3 месяца
-в курс входят 7 дисциплин
-занятия проходят 2-3 раза в неделю
-длительность 1-ого занятия 2 часа
-стоимость обучения 2500 руб./мес.
-на протяжении всего обучения (по желанию) Вас привлекают к участию в различных мероприятиях (в зависимости от требований заказчика)
-по окончанию обучения модель получает диплом на 2-х языках и именную карту со скидками от партнеров агенства
-выпускной проходит в виде fashion показа
-по Вашему желанию менеджер Lotus Model Agency подбирает наиболее подходящий контракт для работы за границей
'
        ],
        [
            "title" => "Курс \"Paris\"",
            "price" =>4000,
            'type'=>ProductTypeEnum::LMACourses,
            "image" => "https://sun9-51.userapi.com/c846322/v846322097/1b3561/ZYbm1mFqf5g.jpg",
            "description" =>
                'Курс "Paris" для девушек от 14 до 25 лет
-курс длится 3 месяца
-в курс входят 15 предметов
-занятия проходят 5 раз в неделю
-длительность 1-ого занятия 2 часа
-стоимость обучения 4000 руб./мес.
-на протяжении всего обучения (по желанию) Вас привлекают к участию в различных мероприятиях (в зависимости от требований заказчика)
-по окончанию обучения модель получает диплом на 2-х языках
-выпускной проходит в виде fashion показа
-по Вашему желанию менеджер Lotus Model Agency подбирает наиболее подходящий контракт для работы за границей
'
        ],
        [
            'type'=>ProductTypeEnum::LMACourses,
            "title" => "Курс \"Moscow\"",
            "price" => 2500,
            "image" => "https://sun9-43.userapi.com/c846121/v846121097/1ab209/gP6gO_HXELo.jpg",
            "description" => 'Курс "Moscow" (базовый курс) для девушек от 14 до 25 лет
-курс длится 3 месяца
-занятия проходят 2 раза в неделю
-длительность 1-ого занятия 2 часа
-стоимость обучения 2500 руб./мес.
-на протяжении всего обучения (по желанию) Вас привлекают к участию в различных мероприятиях (в зависимости от требований заказчика)
-по окончанию обучения модель получает диплом на 2-х языках
-выпускной проходит в виде fashion показа
-по Вашему желанию менеджер Lotus Model Agency подбирает наиболее подходящий контракт для работы за границей
'
        ],
        [
            'type'=>ProductTypeEnum::LMACourses,
            "title" => "Курс \"INDIVIDUAL\"",
            "price" => 2500,
            "image" => "https://sun9-30.userapi.com/c850236/v850236097/f7ed4/zFdK31DYVqw.jpg",
            "description" => '-количество занятий на ваш выбор из перечня - не менее 3
-длительность 1-ого занятия 2 часа
-на протяжении всего обучения (по желанию) Вас привлекают к участию в различных мероприятиях (в зависимости от требований заказчика)
-по окончанию обучения модель получает диплом на 2-х языках
-выпускной проходит в виде fashion показа
-по Вашему желанию менеджер Lotus Model Agency подбирает наиболее подходящий контракт для работы за границей
'
        ],
        [
            'type'=>ProductTypeEnum::LMACourses,
            "title" => "Курс \"Singapore\"",
            "price" => 3000,
            "image" => "https://sun9-68.userapi.com/c852128/v852128580/1df45f/n2nVNUq90e4.jpg",
            "description" => 'Курс "Singapore" для девушек от 13 до 25 лет
-курс длится 9 месяцев
-в курс входят 18 дисциплин
-занятия проходят 5 раза в неделю
-длительность 1-ого занятия 2 часа
-стоимость обучения 3000 руб./мес.
-на протяжении всего обучения (по желанию) Вас привлекают к участию в различных мероприятиях (в зависимости от требований заказчика)
-по окончанию обучения модель получает диплом на 2-х языках
-выпускной проходит в виде fashion показа
-по Вашему желанию менеджер Lotus Model Agency подбирает наиболее подходящий контракт для работы за границей
'
        ],

        [
            'type'=>ProductTypeEnum::LKCCourses,
            "title" => "Курс \"New York\"",
            "price" => 3000,
            "image" => "https://sun9-64.userapi.com/c850024/v850024936/13cbbf/VqqB5dn_LWQ.jpg",
            "description" => '
            1. Искусство дефиле – один из основных предметов модельной школы. Главное в дефиле – особая техника движения на подиуме или сцене. Модель изучает различные стили дефиле, модельные постановки, точки поворота, разнообразные техники движения в разной одежде: вечерних нарядах, белье, верхней одежде, учатся в совершенстве владеть своим телом.
2. Ритмопластика – это танцевальная гимнастика с оздоровительной направленностью, основным средством которой являются комплексы гимнастических упражнений, различные по своему характеру, выполняемые под ритмическую музыку, оформленные танцевальными движениями.
3. Актерское мастерство - важный предмет в программе обучения курса, основными задачами которого являются: раскрытие творческих способностей у обучающихся, развитие воображения, внимания, концентрации, внутреннее раскрепощение, постановка голоса, основы актерского мастерства.
4. Психология - помогает разобраться в «хитросплетениях» детской души. Зная особенности психологии дети смогут разбираться в поведении других людей, построить крепкие взаимоотношения с членами семьи, друзьями.
5. Сценическая речь - навыки сценической речи являются частью ораторского искусства, как и актерское мастерство, сценическая речь помогает в повседневной жизни красиво и понятно излагать, доносить мысль до слушателя.
6. Рисование – в основе занятий лежит наблюдение и изучение окружающей действительности, формирование у детей целостного, живого представления о предметах и явлениях.
7. Этикет – свод правил, которые помогут ребенку научиться красиво вести себя. Узнав эти правила и практикуя их ежедневно, ребёнок сможет легко и просто строить общение с родными, друзьями и даже незнакомыми людьми, овладеет правилами хорошего тона и будет знать, как разговаривать по телефону, красиво вести себя за столом, в театре, в гостях.
8. Фото класс – искусство взаимодействия внутреннего состояния и тела.
С ребенком будет работать преподаватель фотопозирования и фотограф. Будет отдан весь исходный материал, отработаны ошибки ребенка. Ребенок научится позировать в разных стилях, овладеет мимикой, выражением эмоций. Научиться быть раскрепощенным и естественным перед камерой.
9. Фотопозирование – профессиональная модельная сьемка проходит на разную тематику в разных локациях, в разных образах. Для модели будет создано портфолио которое поможет ей показать широкий спектр способностей и раскрыть как можно больше ее выгодных сторон.
            '
        ],
        [
            'type'=>ProductTypeEnum::LKCCourses,
            "title" => "Курс \"Milan\"",
            "price" => 3000,
            "image" => "https://sun9-66.userapi.com/c849332/v849332936/140529/sDaRmUhzEcI.jpg",
            "description" =>
                '1. Искусство дефиле – один из основных предметов модельной школы. Главное в дефиле – особая техника движения на подиуме или сцене. Модель изучает различные стили дефиле, модельные постановки, точки поворота, разнообразные техники движения в разной одежде: вечерних нарядах, белье, верхней одежде, учатся в совершенстве владеть своим телом.
2. Хореография – одно из самых популярных направлений современных молодёжных танцевальных культур. Благодаря занятиям по хип-хопу у детей развивается чувство ритма, общая физическая подготовка, пластика, координация, осанка: одно занятие сравнимо с часом занятий спортом.
3. Актерское мастерство - важный предмет в программе обучения курса, основными задачами которого являются: раскрытие творческих способностей у обучающихся, развитие воображения, внимания, концентрации, внутреннее раскрепощение, постановка голоса, основы актерского мастерства.
4. Сценическая речь - навыки сценической речи являются частью ораторского искусства, как и актерское мастерство, сценическая речь помогает в повседневной жизни красиво и понятно излагать, доносить мысль до слушателя.
5. Работа в кадре – основная задача научить правильно вести себя в кадре, побороть стеснительность перед камерами и научиться импровизации в кадре.
6. Психология - помогает разобраться в «хитросплетениях» детской души. Зная особенности психологии дети смогут разбираться в поведении других людей, построить крепкие взаимоотношения с членами семьи, друзьями.
7. Уверенность в себе – изучение предмета поможет ребенку в любой момент, в любой ситуации управлять своим состоянием, восприятием, внутренним согласием. Поможет научиться ребенку позитивно мыслить, достигать поставленных целей и преодолевать свои страхи.
8. Модельная этика/этикет - предмет, посвященный основным требованиям к модели, этике в работе моделью, правилам поведения на фотосессии/кастинге, работе с модельными агентствами. Этикет – свод правил, которые помогут ребенку научиться красиво вести себя. Узнав эти правила и практикуя их ежедневно, ребёнок сможет легко и просто строить общение с родными, друзьями и даже незнакомыми людьми, овладеет правилами хорошего тона и будет знать, как разговаривать по телефону, красиво вести себя за столом, в театре, в гостях.
9. Фото класс - искусство взаимодействия внутреннего состояния и тела.
С ребенком будет работать преподаватель фотопозирования и фотограф. Будет отдан весь исходный материал, отработаны ошибки ребенка. Ребенок научится позировать в разных стилях, овладеет мимикой, выражением эмоций. Научиться быть раскрепощенным и естественным перед камерой.
10. Фотопозирование – профессиональная модельная сьемка проходит на разную тематику в разных локациях, в разных образах. Для модели будет создано портфолио которое поможет ей показать широкий спектр способностей и раскрыть как можно больше ее выгодных сторон.
11. Рисование – в основе занятий лежит наблюдение и изучение окружающей действительности, формирование у детей целостного, живого представления о предметах и явлениях.
            '
        ],
        [
            'type'=>ProductTypeEnum::LKCCourses,
            "title" => "Курс \"Monaco\"",
            "price" => 3000,
            "image" => "https://sun9-13.userapi.com/c848520/v848520936/13f95c/zJ0QAerNsfc.jpg",
            "description" => '
            1. Искусство дефиле – один из основных предметов модельной школы. Главное в дефиле – особая техника движения на подиуме или сцене. Модель изучает различные стили дефиле, модельные постановки, точки поворота, разнообразные техники движения в разной одежде: вечерних нарядах, белье, верхней одежде, учатся в совершенстве владеть своим телом.
2. Хип-хоп – одно из самых популярных направлений современных молодёжных танцевальных культур. Благодаря занятиям по хип-хопу у детей развивается чувство ритма, общая физическая подготовка, пластика, координация, осанка: одно занятие сравнимо с часом занятий спортом.
3. Актерское мастерство - важный предмет в программе обучения курса, основными задачами которого являются: раскрытие творческих способностей у обучающихся, развитие воображения, внимания, концентрации, внутреннее раскрепощение, постановка голоса, основы актерского мастерства.
4. Сценическая речь - навыки сценической речи являются частью ораторского искусства, как и актерское мастерство, сценическая речь помогает в повседневной жизни красиво и понятно излагать, доносить мысль до слушателя.
5. Работа в кадре – основная задача научить правильно вести себя в кадре, побороть стеснительность перед камерами и научиться импровизации в кадре.
6. Психология - помогает разобраться в «хитросплетениях» детской души. Зная особенности психологии дети смогут разбираться в поведении других людей, построить крепкие взаимоотношения с членами семьи, друзьями.
7. Уверенность в себе – изучение предмета поможет ребенку в любой момент, в любой ситуации управлять своим состоянием, восприятием, внутренним согласием. Поможет научиться ребенку позитивно мыслить, достигать поставленных целей и преодолевать свои страхи.
8. Модельная этика/этикет - предмет, посвященный основным требованиям к модели, этике в работе моделью, правилам поведения на фотосессии/кастинге, работе с модельными агентствами. Этикет – свод правил, которые помогут ребенку научиться красиво вести себя. Узнав эти правила и практикуя их ежедневно, ребёнок сможет легко и просто строить общение с родными, друзьями и даже незнакомыми людьми, овладеет правилами хорошего тона и будет знать, как разговаривать по телефону, красиво вести себя за столом, в театре, в гостях.
9. Фото класс - искусство взаимодействия внутреннего состояния и тела.
С ребенком будет работать преподаватель фотопозирования и фотограф. Будет отдан весь исходный материал, отработаны ошибки ребенка. Ребенок научится позировать в разных стилях, овладеет мимикой, выражением эмоций. Научиться быть раскрепощенным и естественным перед камерой.
10. Фотопозирование – профессиональная модельная съемка проходит на разную тематику в разных локациях, в разных образах. Для модели будет создано портфолио которое поможет ей показать широкий спектр способностей и раскрыть как можно больше ее выгодных сторон.
11. Рисование – в основе занятий лежит наблюдение и изучение окружающей действительности, формирование у детей целостного, живого представления о предметах и явлениях.
            '
        ],
        [
            'type'=>ProductTypeEnum::LKCCourses,
            "title" => "Курс \"Venice\"",
            "price" => 3500,
            "image" => "https://sun9-4.userapi.com/c846417/v846417936/1b7854/fe8GrN9m5eg.jpg",
            "description" => '
            1. Искусство дефиле (11) – один из основных предметов модельной школы. Главное в дефиле – особая техника движения на подиуме или сцене. Модель изучает различные стили дефиле, модельные постановки, точки поворота, разнообразные техники движения в разной одежде: вечерних нарядах, белье, верхней одежде, учатся в совершенстве владеть своим телом.
2. Хореография (9) – особенностью данного стиля является движения, имитирующие ходьбу моделей по подиуму, позирование для фешн – съемок; к тому же, в нем приветствуется специальная мимика фотомоделей. Занятия по стилю Vogue, прежде всего, тренируют мышцы рук и ног.
3. Актерское мастерство (6) - важный предмет в программе обучения курса, основными задачами которого являются: раскрытие творческих способностей у обучающихся, развитие воображения, внимания, концентрации, внутреннее раскрепощение, постановка голоса, основы актерского мастерства.
4. Сценическая речь (4) - навыки сценической речи являются частью ораторского искусства, как и актерское мастерство, сценическая речь помогает в повседневной жизни красиво и понятно излагать, доносить мысль до слушателя.
5. Работа в кадре (5) – основная задача научить правильно вести себя в кадре, побороть стеснительность перед камерами и научиться импровизации в кадре.
6. Психология (3) - помогает разобраться в «хитросплетениях» детской души. Зная особенности психологии дети смогут разбираться в поведении других людей, построить крепкие взаимоотношения с членами семьи, друзьями.
7. Уверенность в себе(2) – изучение предмета поможет ребенку в любой момент, в любой ситуации управлять своим состоянием, восприятием, внутренним согласием. Поможет научиться ребенку позитивно мыслить, достигать поставленных целей и преодолевать свои страхи.
8. Модельная этика/этикет(3) - предмет, посвященный основным требованиям к модели, этике в работе моделью, правилам поведения на фотосессии/кастинге, работе с модельными агентствами. Этикет – свод правил, которые помогут ребенку научиться красиво вести себя. Узнав эти правила и практикуя их ежедневно, ребёнок сможет легко и просто строить общение с родными, друзьями и даже незнакомыми людьми, овладеет правилами хорошего тона и будет знать, как разговаривать по телефону, красиво вести себя за столом, в театре, в гостях.
9. Фото класс(3) – искусство взаимодействия внутреннего состояния и тела.
С ребенком будет работать преподаватель фотопозирования и фотограф. Будет отдан весь исходный материал, отработаны ошибки ребенка. Ребенок научится позировать в разных стилях, овладеет мимикой, выражением эмоций. Научиться быть раскрепощенным и естественным перед камерой.
10. Фотопозирование (3) – профессиональная модельная сьемка проходит на разную тематику в разных локациях, в разных образах. Для модели будет создано портфолио которое поможет ей показать широкий спектр способностей и раскрыть как можно больше ее выгодных сторон.
11. Рисование (2) – в основе занятий лежит наблюдение и изучение окружающей действительности, формирование у детей целостного, живого представления о предметах и явлениях.
            '
        ],
        [
            'type'=>ProductTypeEnum::LKCCourses,
            "title" => "Курс \"San Marino\"",
            "price" =>3500,
            "image" => "https://sun9-9.userapi.com/c852028/v852028794/d7a14/jUDztQbsYCQ.jpg",
            "description" => '
            1. Искусство дефиле(12) – один из основных предметов модельной школы. Главное в дефиле – особая техника движения на подиуме или сцене. Модель изучает различные стили дефиле, модельные постановки, точки поворота, разнообразные техники движения в разной одежде: вечерних нарядах, белье, верхней одежде, учатся в совершенстве владеть своим телом.
2. Ритмопластика(10) – это танцевальная гимнастика с оздоровительной направленностью, основным средством которой являются комплексы гимнастических упражнений, различные по своему характеру, выполняемые под ритмическую музыку, оформленные танцевальными движениями.
3. Актерское мастерство(7) - важный предмет в программе обучения курса, основными задачами которого являются: раскрытие творческих способностей у обучающихся, развитие воображения, внимания, концентрации, внутреннее раскрепощение, постановка голоса, основы актерского мастерства.
4. Психология(6) - помогает разобраться в «хитросплетениях» детской души. Зная особенности психологии дети смогут разбираться в поведении других людей, построить крепкие взаимоотношения с членами семьи, друзьями.
5. Сценическая речь(4) - навыки сценической речи являются частью ораторского искусства, как и актерское мастерство, сценическая речь помогает в повседневной жизни красиво и понятно излагать, доносить мысль до слушателя.
6. Рисование(3) – в основе занятий лежит наблюдение и изучение окружающей действительности, формирование у детей целостного, живого представления о предметах и явлениях.
7. Этикет(3) – свод правил, которые помогут ребенку научиться красиво вести себя. Узнав эти правила и практикуя их ежедневно, ребёнок сможет легко и просто строить общение с родными, друзьями и даже незнакомыми людьми, овладеет правилами хорошего тона и будет знать, как разговаривать по телефону, красиво вести себя за столом, в театре, в гостях.
8. Фото класс(3) – искусство взаимодействия внутреннего состояния и тела.
С ребенком будет работать преподаватель фотопозирования и фотограф. Будет отдан весь исходный материал, отработаны ошибки ребенка. Ребенок научится позировать в разных стилях, овладеет мимикой, выражением эмоций. Научиться быть раскрепощенным и естественным перед камерой.
9. Фотопозирование(3) – профессиональная модельная сьемка проходит на разную тематику в разных локациях, в разных образах. Для модели будет создано портфолио которое поможет ей показать широкий спектр способностей и раскрыть как можно больше ее выгодных сторон
            '
        ],
        [
            'type'=>ProductTypeEnum::LKCCourses,
            "title" => "СЕРТИФИКАТ 3500",
            "price" => 3500,
            "image" => "https://sun9-71.userapi.com/c841639/v841639509/421b5/21tqEvLrjfo.jpg",
            "description" => '
            Детская школа моделей, сочетает в себе модельную школу и модельное агентство, наша цель – гармоничное развитие личности и реализация потенциала ребенка.
            '
        ],

    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        Product::truncate();

        Product::create([
            "title" => "Фирменная ручка",
            "image" => "https://psv4.userapi.com/c856332/u88945061/docs/d10/2084a64c7bc4/vgSIYey5sEc.jpg?extra=C2f_iuWjS7l10cDTxoH-EVtcwXmbBke0m6RoiFT9delY9__1HKlujNwGhAeU_wIo4T-xqKaBXgVaYDqBpfqOpYRwuUUp_F7S_MtbDEMpE3jm8KRE33YipfyjuEmwbjmjzyRAtNokpb2mRI2M0ZiogOHH",
            "price" => 50.0,
            "position" => 1,
            "description" => "
Фирменная ручка от лучшей модельной школы Lotus Kids точно займет место \"любимой вещи\", которую ты с удовольствием будешь использовать каждый день!\n
Наша фирменная ручка станет твоим неотъемлемым помощником и модным аксессуаром. С ней ты точно станешь самым стильным в школе)\n
Стань частью большой и дружной семьи Lotus Kids!\n
#лучшиеснами",
            "type" => ProductTypeEnum::Items,
        ]);


        Product::create([
            "title" => "Фирменная чашка (черная)",
            "image" => "https://psv4.userapi.com/c856332/u88945061/docs/d9/3e29b8ca84f8/chashka_chernaya.png?extra=T3V4FldLhVhPX_T0m-8JP5K_kZYGO6rlpttzz894yL4IMusapjX0GXsNo0U7iDTM1zOaaPN9jb58GpSm97preenf9kZ3gsz49JX4BoA9XmaLxubEW6feDvQjSj4VMvXm7XR7wQURIJc9dXnj8XBiCStH",
            "price" => 350,
            "position" => 2,
            "description" => "
Наша фирменная чашка точно займет место \"любимой вещи\", к которой привыкают и любят, и пользоваться которой ты будешь долго и с удовольствием!\n
Каждое чаепитие станет намного приятнее и ярче с нашей фирменной именной чашкой и подарит отличное настроение на весь последующий день!\n
Стань частью большой и дружной семьи Lotus Kids!\n
#лучшиеснами
            ",
            "type" => ProductTypeEnum::Items,
        ]);

        Product::create([
            "title" => "Фирменная чашка (белая)",
            "image" => "https://psv4.userapi.com/c856332/u88945061/docs/d13/6aee68d8483c/chashka_kids_tknsq.png?extra=R78w-KV0eMk9FdMzrb0LulmXQgxXymfKQ-8QgobwIRuWDw2kYXXC7gZYW2nNHIRpVYSE6uKHh7ZLwbuHSC_o0uE0wmficEfgo_6V4kvYCO6R18iFVJZLGFHdK7KSXr4oGVBrovoDo_KAOFU2li8iDzSW",
            "price" => 350,
            "position" => 2,
            "description" => "
Наша фирменная чашка точно займет место \"любимой вещи\", к которой привыкают и любят, и пользоваться которой ты будешь долго и с удовольствием!\n
Каждое чаепитие станет намного приятнее и ярче с нашей фирменной именной чашкой и подарит отличное настроение на весь последующий день!\n
Стань частью большой и дружной семьи Lotus Kids!\n
#лучшиеснами
            ",
            "type" => ProductTypeEnum::Items,
        ]);;

        Product::create([
            "title" => "Фирменная футболка (белая)",
            "image" => "https://psv4.userapi.com/c856332/u88945061/docs/d18/d0db3fc58a8f/deti_gotovo.jpg?extra=GIoOChFzDtZQIL-dLpeYMqI-2G4fzEp-sM7yANGTesebS5mKYdND11GrgRAv9aoc3KtEt5DnVS3dzUkwqkNFDgdI3iukW4qrbVAqxIlU31rEw8IAIqUBgBYI75gTJu6-abucoU9lLXV3Q-cSL-kavpRa",
            "price" => 700,
            "position" => 2,
            "description" => "
Спеши заказать фирменную именную футболку от любимой модельной школы Lotus Kids.\n
Будь самым стильным не только на занятиях, но и в повседневной жизни:\n
- пошив по индивидуальным параметрам;\n
- качественный износостойкий материал;\n
- стильное дополнение к твоему образу.\n
Стань частью большой и дружной семьи Lotus Kids!\n
#лучшиеснами
            ",
            "type" => ProductTypeEnum::Items,
        ]);


        Product::create([
            "title" => "Фирменная футболка (черная)",
            "image" => "https://psv4.userapi.com/c856332/u88945061/docs/d17/31e3fcab82a7/deti_chernaya.jpg?extra=iAQYJts06Qdp1SSHIEaJItBvW1bEUMI_lLPgakWsDQ4Y22bObUrU-DRZJkm2QmPyPgxmvKFAdGt8CpmR5ensMNew0bhtBDdP0qR8Q5O1Lt6Csh9RfeKsKS2Xps8TaViQM2Yid0OXBhgip1NxRU341RSX",
            "price" => 700,
            "position" => 2,
            "description" => "
Спеши заказать фирменную именную футболку от любимой модельной школы Lotus Kids.\n
Будь самым стильным не только на занятиях, но и в повседневной жизни:\n
- пошив по индивидуальным параметрам;\n
- качественный износостойкий материал;\n
- стильное дополнение к твоему образу.\n
Стань частью большой и дружной семьи Lotus Kids!\n
#лучшиеснами
            ",
            "type" => ProductTypeEnum::Items,
        ]);


        foreach ($this->courses as $item) {
            Product::create([
                'title'=>$item["title"],
                'price'=>$item["price"],
                'image'=>$item["image"],
                'description'=>$item["description"],
                'type'=>$item["type"],
            ]);
        }
    }
}
