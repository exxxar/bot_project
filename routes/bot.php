<?php


use App\Conversations\AdminConversation;
use App\Conversations\AnswerQuestionConversation;
use App\Conversations\ConfirmOrderConversation;
use App\Conversations\ConfirmPhotoProjectConversation;
use App\Conversations\LotusCampOrderConversation;
use App\Conversations\LotusDanceOrderConversation;
use App\Conversations\MainConversation;
use App\Conversations\ModelFormConversation;
use App\Conversations\PollsFormConversation;
use App\Conversations\QuestionConversation;
use App\Conversations\StartDataConversation;
use Illuminate\Support\Facades\Log;

$this->hears("/start ([0-9a-zA-Z=]+)", StartDataConversation::class . '::start');
$this->hears("/start|.*Продолжить позже|.*Главное меню|.*Попробовать опять", MainConversation::class . "::start");
$this->hears("/current_profile", MainConversation::class . "::currentProfile");
$this->hears("/product_list ([0-9]+)|.*Фирменная продукция", MainConversation::class . "::brandedGoods");
$this->hears("/lma_courses_list ([0-9]+)|.*Курсы LMA", MainConversation::class . "::lmaCourses");
$this->hears("/lkc_courses_list ([0-9]+)|.*Курсы LKC", MainConversation::class . "::lkcCourses");
$this->hears("/equipment_rent_list ([0-9]+)|.*Аренда помещений . оборудования", MainConversation::class . "::equipmentRent");
$this->hears("/add_to_cart ([0-9]+)", MainConversation::class . "::addProductToCart");
$this->hears("/more_info ([0-9]+)", MainConversation::class . "::moreInfo");
$this->hears("/.Посмотреть мои товары", MainConversation::class . "::showProductInCart");
$this->hears("/.Фотопроекты на месяц", MainConversation::class . "::monthPhotoprojects");
$this->hears("/.*Корзина.([0-9]+).", MainConversation::class . "::cart");
$this->hears("/.*Очистить корзину", MainConversation::class . "::clearCart");
$this->hears("/remove_product_from_cart ([0-9]+)", MainConversation::class . "::removeFromCart");
$this->hears("/add_poll", PollsFormConversation::class . "::start");
$this->hears("/remove_poll ([0-9]+)", PollsFormConversation::class . "::remove");
$this->hears("/show_poll ([0-9]+)", PollsFormConversation::class . "::show");
$this->hears("/send_poll ([0-9]+) ([0-9]+)", PollsFormConversation::class . "::sendPoll");
$this->hears("/admin_panel|.*Панель администратора", AdminConversation::class . "::start");
$this->hears("/all_profile_list ([0-9]+)|.*Управление анкетами", AdminConversation::class . "::profiles");
$this->hears("/all_polls_list ([0-9]+)|.*Управление опросами", AdminConversation::class . "::polls");
$this->hears("/all_question_list ([0-9]+)|.*Управление вопросами", AdminConversation::class . "::questions");
$this->hears("/all_products_list ([0-9]+)|.*Управление товарами", AdminConversation::class . "::products");
$this->hears("/current_bot_statistic|.*Статистика по боту", AdminConversation::class . "::statistic");
$this->hears("/answer_this_questions ([0-9]+)", AnswerQuestionConversation::class . "::start");
$this->hears("/i_want_to_photo_project ([0-9]+)", ConfirmPhotoProjectConversation::class . "::start");
$this->hears("/.*Записаться в Лагерь", LotusCampOrderConversation::class . "::start");
$this->hears("/.*Хочу танцевать", LotusDanceOrderConversation::class . "::start");
//$this->hears("/all_profile_list ([0-9]+)", AdminConversation::class."::profilesPerPage");
$this->hears("/.*Каналы администраторов", AdminConversation::class . "::channels");
$this->hears("/.*Lotus Model Agency", MainConversation::class . "::lmaMenu");
$this->hears("/.*Lotus Photostudio", MainConversation::class . "::lpMenu");
$this->hears("/.*Lotus Camp", MainConversation::class . "::lcMenu");
$this->hears("/.*Lotus Dance", MainConversation::class . "::ldMenu");
$this->hears("/.*Lotus Kids", MainConversation::class . "::lkMenu");
$this->hears("/.*Combo Photoprojekt", MainConversation::class . "::cpMenu");
$this->hears("/ask_question ([a-zA-Z]+)|.*Задать вопрос ([a-zA-Z]+)", QuestionConversation::class . "::start");
$this->hears("/.*Перейти в канал ([a-zA-Z]+)", MainConversation::class . "::goToChannel");
$this->hears("/.*F.A.Q.", MainConversation::class . "::askQuestion");
$this->hears("/.*Оформить заказ", ConfirmOrderConversation::class . "::start");
$this->hears("/edit_current_prof|.*Анкета модели", ModelFormConversation::class . "::start");
$this->hears("/user_profile|.*Мой профиль", MainConversation::class . "::profile");


$this->fallback(function ($bot) {
    $bot->reply("Fallback");
});



$this->ask("question_name", QuestionConversation::class . "::name");
$this->ask("question_text", QuestionConversation::class . "::text");
$this->ask("answer_response", AnswerQuestionConversation::class . "::response");
$this->ask("confirm_order_name", ConfirmOrderConversation::class . "::name");
$this->ask("confirm_order_phone", ConfirmOrderConversation::class . "::phone");
$this->ask("confirm_order_comment", ConfirmOrderConversation::class . "::comment");
$this->ask("mf_full_name", ModelFormConversation::class . "::name");
$this->ask("mf_phone", ModelFormConversation::class . "::phone");
$this->ask("mf_sex", ModelFormConversation::class . "::sex");
$this->ask("mf_birth_day", ModelFormConversation::class . "::birthDay");
$this->ask("mf_birth_month", ModelFormConversation::class . "::birthMonth");
$this->ask("mf_select_city", ModelFormConversation::class . "::selectCity");
$this->ask("mf_ask_city", ModelFormConversation::class . "::askCity");
$this->ask("mf_age", ModelFormConversation::class . "::age");
$this->ask("mf_height", ModelFormConversation::class . "::height");
$this->ask("mf_question_1", ModelFormConversation::class . "::question1");
$this->ask("mf_question_2", ModelFormConversation::class . "::question2");
$this->ask("poll_question", PollsFormConversation::class . "::question");
$this->ask("poll_is_anonymous", PollsFormConversation::class . "::changeAnonymous");
$this->ask("poll_allows_multiple_answers", PollsFormConversation::class . "::changeAllowsMultipleAnswers");
$this->ask("poll_option", PollsFormConversation::class . "::option");
$this->ask("poll_remove", PollsFormConversation::class . "::removeAccept");
$this->ask("admin_ask_for_action", StartDataConversation::class . "::askForAction");
$this->ask("admin_action_handler", StartDataConversation::class . "::actionHandler");
$this->ask("admin_action_admin_up", StartDataConversation::class . "::adminUp");
$this->ask("admin_action_admin_down", StartDataConversation::class . "::adminDown");
$this->ask("admin_action_set_sale", StartDataConversation::class . "::setSale");
$this->ask("admin_action_set_count_photo", StartDataConversation::class . "::setCountPhoto");
$this->ask("photo_project_name", ConfirmPhotoProjectConversation::class . "::name");
$this->ask("photo_project_phone", ConfirmPhotoProjectConversation::class . "::phone");
$this->ask("photo_project_comment", ConfirmPhotoProjectConversation::class . "::comment");
$this->ask("lotus_camp_order_type", LotusCampOrderConversation::class . "::type");
$this->ask("lotus_camp_order_name", LotusCampOrderConversation::class . "::name");
$this->ask("lotus_camp_order_age", LotusCampOrderConversation::class . "::age");
$this->ask("lotus_camp_order_phone", LotusCampOrderConversation::class . "::phone");
$this->ask("lotus_camp_order_comment", LotusCampOrderConversation::class . "::comment");
$this->ask("lotus_dance_order_type", LotusDanceOrderConversation::class . "::type");
$this->ask("lotus_dance_order_name", LotusDanceOrderConversation::class . "::name");
$this->ask("lotus_dance_order_age", LotusDanceOrderConversation::class . "::age");
$this->ask("lotus_dance_order_phone", LotusDanceOrderConversation::class . "::phone");
$this->ask("lotus_dance_order_comment", LotusDanceOrderConversation::class . "::comment");

