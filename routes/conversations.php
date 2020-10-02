<?php


use App\Conversations\AdminConversation;
use App\Conversations\AnswerQuestionConversation;
use App\Conversations\ConfirmOrderConversation;
use App\Conversations\ConfirmPhotoProjectConversation;
use App\Conversations\Conversation;
use App\Conversations\FeedbackConversation;
use App\Conversations\FeedbackPPConversation;
use App\Conversations\LotusCampOrderConversation;
use App\Conversations\LotusDanceOrderConversation;
use App\Conversations\MainConversation;
use App\Conversations\ModelFormConversation;
use App\Conversations\PollsFormConversation;
use App\Conversations\QuestionConversation;
use App\Conversations\SearchModelConversation;
use App\Conversations\StartDataConversation;
use App\Conversations\WannaFitnessConversation;
use Illuminate\Support\Facades\Log;

$this->ask("question_name", QuestionConversation::class . "::name")
    ->where("/[а-яёА-ЯЁ ]{5,20}/ui")
    ->fall(QuestionConversation::class . "::fallback",
        "Имя \"%s\" должно быть *на русском* и *длиной 5* и более символов!");

$this->ask("question_text", QuestionConversation::class . "::text")
    ->where("/[а-яёА-ЯЁ0-9!? ]{20,255}/ui")
    ->fall(QuestionConversation::class . "::fallback",
        "Текст вопроса должен быть *на русском* и не меньше *20 символов*!");

$this->ask("answer_response", AnswerQuestionConversation::class . "::response");

$this->ask("confirm_order_name", ConfirmOrderConversation::class . "::name")
    ->fall(ConfirmOrderConversation::class . "::fallback");
$this->ask("confirm_order_phone", ConfirmOrderConversation::class . "::phone")
    ->fall(ConfirmOrderConversation::class . "::fallback");
$this->ask("confirm_order_comment", ConfirmOrderConversation::class . "::comment")
    ->fall(ConfirmOrderConversation::class . "::fallback");

$this->ask("mf_full_name", ModelFormConversation::class . "::name")
    ->where("/[а-яёА-ЯЁ ]{5,20}/ui")
    ->fall(ModelFormConversation::class . "::fallback",
        "Имя \"%s\" должно быть *на русском* и *длиной 5* и более символов!");

$this->ask("mf_phone", ModelFormConversation::class . "::phone")
    ->fall(ModelFormConversation::class . "::fallback");

$this->ask("mf_sex", ModelFormConversation::class . "::sex")
    ->where("/(Мужской|Женский)/ui")
    ->fall(ModelFormConversation::class . "::fallback", "Попробуйте определиться кто же вы? *Парень*? Или *девушка*?");

$this->ask("mf_birth_day", ModelFormConversation::class . "::birthDay")
    ->where("/[0-9]{1,2}/ui")
    ->fall(ModelFormConversation::class . "::fallback",
        "Определенно что-то не так введено, хм"
        );

$this->ask("mf_birth_month", ModelFormConversation::class . "::birthMonth")
    ->where("/[0-9]{1,2}/ui")
    ->fall(ModelFormConversation::class . "::fallback",
        "А *%s* это точно название месяца в русском языке? Может вы путаете?");

$this->ask("mf_select_city", ModelFormConversation::class . "::selectCity")
    ->where("/[а-яёА-ЯЁ ]{5,20}/ui")
    ->fall(ModelFormConversation::class . "::fallback",
        "Город \"%s\" должно быть *на русском*!"
    );

$this->ask("mf_ask_city", ModelFormConversation::class . "::askCity")
    ->where("/[а-яёА-ЯЁ ]{5,20}/ui")
    ->fall(ModelFormConversation::class . "::fallback",
        "Город \"%s\" должно быть *на русском* и длиной не меньше 5 символов!"
    );

$this->ask("mf_age", ModelFormConversation::class . "::age")
    ->where("/[0-9]{1,2}/ui")
    ->fall(ModelFormConversation::class . "::fallback",
        "Хм, а %s точно ваш возраст?Введите, например, 18.");

$this->ask("mf_height", ModelFormConversation::class . "::height")
    ->where("/[0-9]{3}/ui")
    ->fall(ModelFormConversation::class . "::fallback",
        "Хм, а %s точно ваш рост?Введите, например, 180."
        );
$this->ask("mf_question_1", ModelFormConversation::class . "::question1")
    ->where("/(Да|Нет)/ui")
    ->fall(ModelFormConversation::class . "::fallback",
        "Хм, а %s точно ответ на наш вопрос?");

$this->ask("mf_question_2", ModelFormConversation::class . "::question2")
    ->where("/(Да|Нет)/ui")
    ->fall(ModelFormConversation::class . "::fallback",
        "Хм, а %s точно ответ на наш вопрос?");

$this->ask("mf_question_3", ModelFormConversation::class . "::question3")
    ->where("/(Да|Нет)/ui")
    ->fall(ModelFormConversation::class . "::fallback",
        "Хм, а %s точно ответ на наш вопрос?");

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
$this->ask("lotus_camp_order_child_name", LotusCampOrderConversation::class . "::childName");
$this->ask("lotus_camp_order_parent_name", LotusCampOrderConversation::class . "::parentName");
$this->ask("lotus_camp_order_age", LotusCampOrderConversation::class . "::age");
$this->ask("lotus_camp_order_phone", LotusCampOrderConversation::class . "::phone");
$this->ask("lotus_camp_order_comment", LotusCampOrderConversation::class . "::comment");
$this->ask("lotus_dance_order_type", LotusDanceOrderConversation::class . "::type");
$this->ask("lotus_dance_order_name", LotusDanceOrderConversation::class . "::name");
$this->ask("lotus_dance_order_age", LotusDanceOrderConversation::class . "::age");
$this->ask("lotus_dance_order_phone", LotusDanceOrderConversation::class . "::phone");
$this->ask("lotus_dance_order_comment", LotusDanceOrderConversation::class . "::comment");

$this->ask("fb1_question_1", FeedbackConversation::class . "::question1");
$this->ask("fb1_question_2", FeedbackConversation::class . "::question2");
$this->ask("fb1_question_3", FeedbackConversation::class . "::question3");
$this->ask("fb1_question_4", FeedbackConversation::class . "::question4");
$this->ask("fb1_question_5", FeedbackConversation::class . "::question5");
$this->ask("fb1_question_6", FeedbackConversation::class . "::question6");
$this->ask("fb1_question_7", FeedbackConversation::class . "::question7");

$this->ask("fb2_question_1", FeedbackPPConversation::class . "::question1");
$this->ask("fb2_question_2", FeedbackPPConversation::class . "::question2");
$this->ask("fb2_question_3", FeedbackPPConversation::class . "::question3");
$this->ask("fb2_question_4", FeedbackPPConversation::class . "::question4");
$this->ask("fb2_question_5", FeedbackPPConversation::class . "::question5");
$this->ask("fb2_question_6", FeedbackPPConversation::class . "::question6");
$this->ask("fb2_question_7", FeedbackPPConversation::class . "::question7");
$this->ask("fb2_question_8", FeedbackPPConversation::class . "::question8");

$this->ask("wf_name", WannaFitnessConversation::class . "::name");
$this->ask("wf_phone", WannaFitnessConversation::class . "::phone");
$this->ask("wf_age", WannaFitnessConversation::class . "::age");
$this->ask("wf_text", WannaFitnessConversation::class . "::text");


$this->ask("search_text", SearchModelConversation::class . "::text");
