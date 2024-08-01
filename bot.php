<?
header('Content-Type: text/html; charset=utf-8');

$bot_token = '6878870699:AAFLY4kmonZogEko8l-OgH0_ODMg6hFzkKU';// токен бота
$data = file_get_contents('php://input');
$data = json_decode($data, true);

$hostname               = "web08-cp";
$username               = "i1583376"; 
$password               = "NEUT6mf9bp"; 
$database               = "i1583376_dcd"; 
$link                   = mysqli_connect($hostname, $username, $password, $database);

$question_number = 0;
$question_type = "";

$questions_array = 
[
    "landing" => 
        [
            "Вопрос 1 из 12",
            "Вопрос 2 из 12",
            "Вопрос 3 из 12",
            "Вопрос 4 из 12",
            "Вопрос 5 из 12",
            "Вопрос 6 из 12",
            "Вопрос 7 из 12",
            "Вопрос 8 из 12",
            "Вопрос 9 из 12",
            "Вопрос 10 из 12",
            "Вопрос 11 из 12",
            "Вопрос 12 из 12"
        ],
    "banner" =>
        [
            "Вопрос 1 из 11",
            "Вопрос 2 из 11",
            "Вопрос 3 из 11",
            "Вопрос 4 из 11",
            "Вопрос 5 из 11",
            "Вопрос 6 из 11",
            "Вопрос 7 из 11",
            "Вопрос 8 из 11",
            "Вопрос 9 из 11",
            "Вопрос 10 из 11",
            "Вопрос 11 из 11"
        ],
    "smm" =>
        [
            "Вопрос 1 из 10",
            "Вопрос 2 из 10",
            "Вопрос 3 из 10",
            "Вопрос 4 из 10",
            "Вопрос 5 из 10",
            "Вопрос 6 из 10",
            "Вопрос 7 из 10",
            "Вопрос 8 из 10",
            "Вопрос 9 из 10",
            "Вопрос 10 из 10"
        ],
    "3d" =>
        [
            "Вопрос 1 из 6",
            "Вопрос 2 из 6",
            "Вопрос 3 из 6",
            "Вопрос 4 из 6",
            "Вопрос 5 из 6",
            "Вопрос 6 из 6"
        ],
    "product" =>
        [
            "Вопрос 1 из 6",
            "Вопрос 2 из 6",
            "Вопрос 3 из 6",
            "Вопрос 4 из 6",
            "Вопрос 5 из 6",
            "Вопрос 6 из 6"
        ],
    "branding" =>
        [
            "Вопрос 1 из 13",
            "Вопрос 2 из 13",
            "Вопрос 3 из 13",
            "Вопрос 4 из 13",
            "Вопрос 5 из 13",
            "Вопрос 6 из 13",
            "Вопрос 7 из 13",
            "Вопрос 8 из 13",
            "Вопрос 9 из 13",
            "Вопрос 10 из 13",
            "Вопрос 11 из 13",
            "Вопрос 12 из 13",
            "Вопрос 13 из 13"
        ],
    "copywrite" =>
        [
            "Вопрос 1 из 3",
            "Вопрос 2 из 3",
            "Вопрос 3 из 3"
        ],
    "illustration" =>
        [
            "Вопрос 1 из 8",
            "Вопрос 2 из 8",
            "Вопрос 3 из 8",
            "Вопрос 4 из 8",
            "Вопрос 5 из 8",
            "Вопрос 6 из 8",
            "Вопрос 7 из 8",
            "Вопрос 8 из 8"
        ],
    "other" =>
        [
            "Максимально подробно опишите, что нужно сделать, и наш менеджер свяжется с вами для уточнения деталей."
        ]
];



    if (isset($data['callback_query'])) {
        
        $selected_btn = $data['callback_query']['data'];
        $chat_id = $data['callback_query']['from']['id'];
        
        if ($selected_btn == "to_managers") {
            $text_return = '
Менеджеры отдела Дизайна коммуникаций:
Маша Блохина - @Mary_Blokhina
Андрей Неграш - @a_negrash';
            message_to_telegram($bot_token, $chat_id, $text_return);
        } else if ($selected_btn == "something") {
            $text_return = '
Нужна digital или печатная продукция?';
            $btns = array(
                'inline_keyboard' => array(
                    array(
    	                array(
    		                'text' => 'Digital',
    		                'callback_data' => 'other',
    	                ),
    
                        array(
    		                'text' => 'Печатная',
    		                'callback_data' => 'creative',
    	                ),
    	            ),
    	            array(
    	                array(
    		                'text' => 'Презентация или pdf-файл',
    		                'callback_data' => 'creative',
    	                )
    	            ),
    	            array(
    	                array(
    		                'text' => 'Написать менеджеру',
    		                'callback_data' => 'to_managers',
    	                )
    	            )
                )
            );
            message_to_telegram($bot_token, $chat_id, $text_return, json_encode($btns));
        } else if ($selected_btn == "creative") {
            $text_return = '
По данному запросу необходимо обратиться в отдел Креатива по почте CREATIVE@ptsecurity.com

Для перезапуска бота напишите /start';
            message_to_telegram($bot_token, $chat_id, $text_return);
        } else {
            mysqli_query($link, "INSERT INTO `answers`(`id_user`, `type`, `state`, `question_number`) VALUES ('".$chat_id."','".$selected_btn."','IN_PROGRESS',0)");
            $question_number = 0;
            $btns = array(
                'inline_keyboard' => array(
    	            array(
    	                array(
    		                'text' => 'Написать менеджеру',
    		                'callback_data' => 'to_managers',
    	                )
    	            )
                )
            );
            message_to_telegram($bot_token, $chat_id, $questions_array[$selected_btn][0], json_encode($btns));
        }
    }

if (!empty($data['message']['text'])) {
    
    $chat_id = $data['message']['from']['id'];
    $user_name = $data['message']['from']['username'];
    $first_name = $data['message']['from']['first_name'];
    $last_name = $data['message']['from']['last_name'];
    $text = trim($data['message']['text']);
    
    $question_number = (int)mysqli_fetch_array(mysqli_query($link, "SELECT `question_number` FROM `answers` WHERE `state`='IN_PROGRESS' AND `id_user`='".$chat_id."'"))[0];
    $question_type = mysqli_fetch_array(mysqli_query($link, "SELECT `type` FROM `answers` WHERE `state`='IN_PROGRESS' AND `id_user`='".$chat_id."'"))[0];
    
    if ($text == '/start' or $text == 'start' or $text == 'старт') {
        if (strlen($question_type) == 0){
            $text_return = "
Привет, $first_name!
Я - бот, который помогает быстро и просто создать задачу на дизайн в отдел Дизайна коммуникаций. 
Выбери, что именно нужно создать.";
            $btns = array(
                'inline_keyboard' => array(
                    array(
    	                array(
    		                'text' => 'Лендинг',
    		                'callback_data' => 'landing',
    	                ),
    
                        array(
    		                'text' => 'Баннеры',
    		                'callback_data' => 'banner',
    	                ),
    	            ),
    	            array(
    	                array(
    		                'text' => 'Картинку для SMM',
    		                'callback_data' => 'smm',
    	                ),
    	                array(
    		                'text' => '3D-изображение',
    		                'callback_data' => '3d',
    	                ),
    	            ),
    	            array(
    	                array(
    		                'text' => 'Продуктовое решение',
    		                'callback_data' => 'product',
    	                ),
    	                array(
    		                'text' => 'Брендинг',
    		                'callback_data' => 'branding',
    	                ),
    	            ),
    	            array(
    	                array(
    		                'text' => 'Копирайт',
    		                'callback_data' => 'copywrite',
    	                ),
    	                array(
    		                'text' => 'Иллюстрация',
    		                'callback_data' => 'illustration',
    	                ),
    	            ),
    	            array(
    	                array(
    		                'text' => 'Что-то другое',
    		                'callback_data' => 'something',
    	                )
    	            ),
    	            array(
    	                array(
    		                'text' => 'Написать менеджеру',
    		                'callback_data' => 'to_managers',
    	                )
    	            )
                )
            );
            message_to_telegram($bot_token, $chat_id, $text_return, json_encode($btns));
        } else {
            mysqli_query($link, "UPDATE `answers` SET `state`='CANCELED' WHERE `state`='IN_PROGRESS' AND `id_user`='".$chat_id."'");
        }
    }
    else {
        if (strlen($question_type) == 0) {
            $text_return = "
К сожалению, я не знаю такой команды 😢
Для перезапуска бота напишите /start";
            message_to_telegram($bot_token, $chat_id, $text_return);
        } else {
            $question_number = $question_number + 1;
            mysqli_query($link, "UPDATE `answers` SET `question_number`=".$question_number.", `q".$question_number."`='".$text."' WHERE `state`='IN_PROGRESS' AND `id_user`='".$chat_id."'");
            
            $all_q = count($questions_array[$question_type]);
            
            if ($question_number == $all_q) {
                mysqli_query($link, "UPDATE `answers` SET `state`='FINISHED' WHERE `state`='IN_PROGRESS' AND `id_user`='".$chat_id."'");
                //create new task
                message_to_telegram($bot_token, $chat_id, "Ваша задача создана на нашей доске! Вот на неё ссылка: [ссылка]");
            } else {
                $btns = array(
                    'inline_keyboard' => array(
        	            array(
        	                array(
        		                'text' => 'Написать менеджеру',
        		                'callback_data' => 'to_managers',
        	                )
        	            )
                    )
                );
                message_to_telegram($bot_token, $chat_id, $questions_array[$question_type][$question_number], json_encode($btns));
            }
        }
    }

}

// функция отправки сообщени в от бота в диалог с юзером
function message_to_telegram($bot_token, $chat_id, $text, $reply_markup = '')
{
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

function get_type($user)
{
    return ;
}

function get_question($user)
{
    return ;
}
?>
