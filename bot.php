<?
//--Параметры для настройки бота--
// Токен бота
$bot_token              = '***';

// Подключение к БД
$hostname               = "web08-cp";
$username               = "i1583376"; 
$password               = "***"; 
$database               = "i1583376_dcd"; 
$link                   = mysqli_connect($hostname, $username, $password, $database);

// Пути к файлам вопросов
$path_questions         = "./questions.json";
$path_answers_to_task   = "./answer_to_task.json";

// Данные для создания задач в YouTrack
$project_id             = "0-1";
$subsystems             = [
                            "landing" => "Launch", 
                            "banner" => "Content", 
                            "smm" => "SMM", 
                            "3d" => "3D", 
                            "product" => "UX", 
                            "branding" => "Brand", 
                            "copywrite" => "Copywrite", 
                            "illustration" => "Content", 
                            "other" => "Content"
                        ];
$task_names_array       = [
                            "landing" => "Лэндинг", 
                            "banner" => "Баннеры", 
                            "smm" => "SMM", 
                            "3d" => "3D", 
                            "product" => "Продуктовое решение", 
                            "branding" => "Брендинг", 
                            "copywrite" => "Копирайт", 
                            "illustration" => "Иллюстрации", 
                            "other" => "Другое"
                        ];

//--------------------------------

header('Content-Type: text/html; charset=utf-8');

$question_number = 0;
$question_type = "";

$questions_array = json_decode(file_get_contents($path_questions), true);
$answers_to_task = json_decode(file_get_contents($path_answers_to_task), true);

$data = file_get_contents('php://input');
$data = json_decode($data, true);

// Обработка нажатий кнопок
if (isset($data['callback_query'])) {
        
    $selected_btn = $data['callback_query']['data'];
    $chat_id = $data['callback_query']['from']['id'];
        
    if ($selected_btn == "to_managers") {
        $text_return = "Менеджеры отдела Дизайна коммуникаций:\nМаша Блохина - @Mary_Blokhina\nАндрей Неграш - @a_negrash";
        message_to_telegram($bot_token, $chat_id, $text_return);
    } else if ($selected_btn == "to_copywriter") {
        $text_return = "Копирайтер:\nМишель Коржова @MichelRomanovna";
        message_to_telegram($bot_token, $chat_id, $text_return);
    } else if ($selected_btn == "no_youtrack") {
        $text_return = "Тебе нужно обратиться в Заботу для получения доступа одним из следующих способов: \n✅ через портал https://tracker.ptsecurity.com/servicedesk/customer/portals \n✅ через почту zabota@ptsecurity.com \n✅ через бот @pt_zabota_bot";
        message_to_telegram($bot_token, $chat_id, $text_return);
    } else if ($selected_btn == "something") {
        $text_return = "Нужна digital или печатная продукция?";
        $btns = 
        ['inline_keyboard' => [
            [['text' => '💻 Digital','callback_data' => 'other'],['text' => '🖨 Печатная','callback_data' => 'creative']],
            [['text' => '📽 Презентация','callback_data' => 'creative'],['text' => '📄 pdf-файл','callback_data' => 'creative']],
            [['text' => '🤔 Написать менеджеру','callback_data' => 'to_managers']]
            ]
        ];
        message_to_telegram($bot_token, $chat_id, $text_return, json_encode($btns));
    } else if ($selected_btn == "creative") {
        $text_return = "По данному запросу необходимо обратиться в отдел Креатива по почте CREATIVE@ptsecurity.com\n\nДля перезапуска бота напиши /start";
        message_to_telegram($bot_token, $chat_id, $text_return);
    } else if ($selected_btn == "do_brief") {
        mysqli_query($link, "UPDATE `answers` SET `state`='DO_BRIEF' WHERE `state`='WAIT' AND `id_user`='".$chat_id."'");
        message_to_telegram($bot_token, $chat_id, "Отлично! Прикрепи документ с брифом в ответном сообщении. В течении одного рабочего дня наши менеджеры обработают запрос и свяжутся с тобой для уточнения деталей");
    } else if ($selected_btn == "do_bot") {
        mysqli_query($link, "UPDATE `answers` SET `state`='IN_PROGRESS' WHERE `state` IN ('DO_BRIEF','WAIT') AND `id_user`='".$chat_id."'");
        $question_type = mysqli_fetch_array(mysqli_query($link, "SELECT `type` FROM `answers` WHERE `state`='IN_PROGRESS' AND `id_user`='".$chat_id."'"))[0];
        message_to_telegram($bot_token, $chat_id, "<b>Вопрос 1 из ".(int)count($questions_array[$question_type])."</b>\n".$questions_array[$question_type][0]);
    } else {
        mysqli_query($link, "UPDATE `answers` SET `state`='CANCELED' WHERE `state` IN ('IN_PROGRESS','DO_BRIEF','WAIT') AND `id_user`='".$chat_id."'");
        mysqli_query($link, "INSERT INTO `answers`(`id_user`, `type`, `state`, `question_number`) VALUES ('".$chat_id."','".$selected_btn."','WAIT',0)");
        
        if ($selected_btn == "copywrite") {
            $btns_selection = 
            ['inline_keyboard' => [
                [['text' => '📌 Прикрепить бриф','callback_data' => 'do_brief'],['text' => '🤖 Ответить через бота','callback_data' => 'do_bot']],
                [['text' => '🤔 Написать копирайтеру','callback_data' => 'to_copywriter']]
                ]
            ];
            message_to_telegram($bot_token, $chat_id, "Для создания задачи нужно ответить на несколько вопросов. Если у тебя есть заполненный бриф, можешь его прикрепить к своему запросу. А если возникнут сложности, ты всегда можешь связаться с нашим копирайтером.", json_encode($btns_selection));
        } else {
            $btns_selection = 
            ['inline_keyboard' => [
                [['text' => '📎 Прикрепить бриф','callback_data' => 'do_brief'],['text' => '🤖 Ответить через бота','callback_data' => 'do_bot']],
                [['text' => '🤔 Написать менеджеру','callback_data' => 'to_managers']]
                ]
            ];
            message_to_telegram($bot_token, $chat_id, "Для создания задачи нужно ответить на несколько вопросов. Если у тебя есть заполненный бриф, можешь его прикрепить к своему запросу. А если возникнут сложности, ты всегда можешь связаться с нашими менеджерами.", json_encode($btns_selection));
        }
    }
}

// Обработка текстовых сообщений пользователя
if (!empty($data['message']['text'])) {
    
    $chat_id = $data['message']['from']['id'];
    $user_name = $data['message']['from']['username'];
    $first_name = $data['message']['from']['first_name'];
    $last_name = $data['message']['from']['last_name'];
    $text = trim($data['message']['text']);
    
    // Команда: Перезапуск бота
    if ($text == '/start') {
        mysqli_query($link, "UPDATE `answers` SET `state`='CANCELED' WHERE `state` IN ('IN_PROGRESS','DO_BRIEF','WAIT') AND `id_user`='".$chat_id."'");
        
        $text_return = "Привет, $first_name!\nЯ — бот, который помогает быстро и просто создать задачу на дизайн в отдел Дизайна коммуникаций.\nВыбери, что именно нужно создать.";
        $btns = 
        ['inline_keyboard' => [
            [['text' => '🌐 Лэндинг','callback_data' => 'landing'],['text' => '🎯 Баннеры','callback_data' => 'banner']],
            [['text' => '📱 Картинку для SMM','callback_data' => 'smm'],['text' => '💎 3D-изображение','callback_data' => '3d']],
            [['text' => '⚙️ Продуктовое решение','callback_data' => 'product'],['text' => '✨ Брендинг','callback_data' => 'branding']],
            [['text' => '✏️ Копирайт','callback_data' => 'copywrite'],['text' => '🖼 Иллюстрация','callback_data' => 'illustration']],
            [['text' => '🔍 Что-то другое','callback_data' => 'something']],
            [['text' => '🤔 Написать менеджеру','callback_data' => 'to_managers']]
            ]
        ];
        message_to_telegram($bot_token, $chat_id, $text_return, json_encode($btns));
    
    // Команда: Просмотр списка задач
    } else if ($text == '/open_tasks') {
        $text_return = "У тебя нет открытых задач в проекте DCD.";
        
        message_to_telegram($bot_token, $chat_id, $text_return);
        
    // Другие сообщения
    } else {
        $question_data = mysqli_fetch_array(mysqli_query($link, "SELECT `type`, `question_number` FROM `answers` WHERE `state`='IN_PROGRESS' AND `id_user`='".$chat_id."'"));
        
        $question_number = (int)$question_data['question_number'];
        $question_type = $question_data['type'];
        
        if (strlen($question_type) == 0) {
            $text_return = "К сожалению, я не знаю такой команды 😢\nДля перезапуска бота напиши /start";
            message_to_telegram($bot_token, $chat_id, $text_return);
        } else {
            $question_number = $question_number + 1;
            mysqli_query($link, "UPDATE `answers` SET `question_number`=".$question_number.", `q".$question_number."`='".mysqli_real_escape_string($link, $text)."' WHERE `state`='IN_PROGRESS' AND `id_user`='".$chat_id."'");
            
            $all_q = count($questions_array[$question_type]);
            
            if ($question_number == $all_q) {
                $taskName = $task_names_array[$question_type].": задача из тг-бота";
                $taskDescription = "Привет!\n";
                $result = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM `answers` WHERE `state`='IN_PROGRESS' AND `id_user`='".$chat_id."'"));
                for ($i = 1; $i <= (int)$result['question_number']; $i++) {
                    $taskDescription .= "\n**".$answers_to_task[$question_type][$i]."**\n".(string)$result['q'.$i]."\n ";
                }
                $taskDescription .= "\nСпасибо!";
                
                $fields = array(
                    "project" => array("id"=>$project_id),
                    "summary" => $taskName,
                    "description" => $taskDescription,
                    "customFields" => array(
                        array(
                            "name" => "State",
                            "\$type" => "SingleEnumIssueCustomField",
                            "value" => array("name" => "Brief")
                        ),
                        array(
                            "name" => "Subsystem",
                            "\$type" => "SingleEnumIssueCustomField",
                            "value" => array("name" => $subsystems[$question_type])
                        )
                    )
                );
                
                mysqli_query($link, "UPDATE `answers` SET `state`='FINISHED' WHERE `state`='IN_PROGRESS' AND `id_user`='".$chat_id."'");
                
                $taskId = createTask($fields);
                
                $btns = 
                ['inline_keyboard' => [
                    [['text' => '👀 Посмотреть задачу', 'url' => 'https://ptdcd.youtrack.cloud/issue/'.$taskId],['text' => '💔 Нет доступа к YouTrack','callback_data' => 'no_youtrack']]
                    ]
                ];
                message_to_telegram($bot_token, $chat_id, "Готово!\nТвоя задача создана: https://ptdcd.youtrack.cloud/issue/".$taskId, json_encode($btns));
            } else {
                message_to_telegram($bot_token, $chat_id, "<b>Вопрос ".($question_number + 1)." из ".(int)$all_q."</b>\n".$questions_array[$question_type][$question_number]);
            }
        }
    }
}

// Функция отправки сообщения от бота
function message_to_telegram(
    $bot_token, 
    $chat_id, 
    $text, 
    $reply_markup = ''
) {
    $ch = curl_init();
    $ch_post = [
        CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendMessage',
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POSTFIELDS => [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text,
            'reply_markup' => $reply_markup,
        ]
    ];

    curl_setopt_array($ch, $ch_post);
    curl_exec($ch);
}

// Функция создания задачи в YouTrack
function createTask($fields) {
    $ch = curl_init('https://ptdcd.youtrack.cloud/api/issues?fields=idReadable');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Authorization: Bearer perm:YWRtaW4=.NDgtMA==.5eJibziVLN1MIMVwkzWCix1oensYXX', 'Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    
    $output = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $taskLink = json_decode($output, true);
    return $taskLink['idReadable'];
}
?>
