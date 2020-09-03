<?php


use App\Conversations\AdminConversation;
use App\Conversations\AnswerQuestionConversation;
use App\Conversations\ConfirmOrderConversation;
use App\Conversations\MainConversation;
use App\Conversations\ModelFormConversation;
use App\Conversations\PollsFormConversation;
use App\Conversations\QuestionConversation;
use Illuminate\Support\Facades\Log;


$this->hears("/start|.*Продолжить позже|.*Главное меню|.*Попробовать опять", MainConversation::class . "::start");
$this->hears("/current_profile", MainConversation::class . "::currentProfile");
$this->hears("/product_list ([0-9]+)|.*Фирменная продукция", MainConversation::class . "::brandedGoods");
$this->hears("/lma_courses_list ([0-9]+)|.*Курсы LMA", MainConversation::class . "::lmaCourses");
$this->hears("/lkc_courses_list ([0-9]+)|.*Курсы LKC", MainConversation::class . "::lkcCourses");
$this->hears("/equipment_rent_list ([0-9]+)|.*Аренда помещений . оборудования", MainConversation::class . "::equipmentRent");
$this->hears("/add_to_cart ([0-9]+)", MainConversation::class . "::addProductToCart");
$this->hears("/.Посмотреть мои товары", MainConversation::class . "::showProductInCart");
$this->hears("/.Фотопроекты на месяц", MainConversation::class . "::monthPhotoprojects");
$this->hears("/.*Корзина.([0-9]+).", MainConversation::class . "::cart");
$this->hears("/.*Очистить корзину", MainConversation::class . "::clearCart");
$this->hears("/remove_product_from_cart ([0-9]+)", MainConversation::class . "::removeFromCart");
$this->hears("/add_poll", PollsFormConversation::class . "::start");
$this->hears("/remove_poll ([0-9]+)", PollsFormConversation::class . "::remove");
$this->hears("/show_poll ([0-9]+)", PollsFormConversation::class . "::show");
$this->hears("/send_poll ([0-9]+) ([0-9]+)", PollsFormConversation::class . "::sendPoll");
$this->hears("/admin|.*Панель администратора", AdminConversation::class . "::start");
$this->hears("/all_profile_list ([0-9]+)|.*Управление анкетами", AdminConversation::class . "::profiles");
$this->hears("/all_polls_list ([0-9]+)|.*Управление опросами", AdminConversation::class . "::polls");
$this->hears("/all_question_list ([0-9]+)|.*Управление вопросами", AdminConversation::class . "::questions");
$this->hears("/answer_this_questions ([0-9]+)", AnswerQuestionConversation::class . "::start");
//$this->hears("/all_profile_list ([0-9]+)", AdminConversation::class."::profilesPerPage");
$this->hears("/.*Каналы администраторов", AdminConversation::class . "::channels");
$this->hears("/.*Lotus Model Agency", MainConversation::class . "::lmaMenu");
$this->hears("/.*Lotus Photostudio", MainConversation::class . "::lpMenu");
$this->hears("/.*Lotus Camp", MainConversation::class . "::lcMenu");
$this->hears("/.*Lotus Dance", MainConversation::class . "::ldMenu");
$this->hears("/.*Lotus Kids", MainConversation::class . "::lkMenu");
$this->hears("/.*Combo Photoprojekt", MainConversation::class . "::cpMenu");
$this->hears("/ask_question ([a-zA-Z]+)|.*Задать вопрос ([a-zA-Z]+)", QuestionConversation::class . "::start");
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

