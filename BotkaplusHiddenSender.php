<?php

require "vendor/autoload.php";
use Botkaplus\BotClient;
use Botkaplus\Filters;
use Botkaplus\Message;
use Botkaplus\KeypadInline;

// Your sender_id ([Array] or "String")
$admin_sender_id = "";

echo "start\n";

$token = "token_bot";
$inData = file_get_contents('php://input');
$Data = json_decode($inData);

$bot = new BotClient(token: $token, rData:$Data);
// $bot->setWebhook('https://domin.com/BotkaplusHiddenSender.php');

$bot->onMessage(Filters::and(Filters::group(), Filters::senderId($admin_sender_id), Filters::photo()), function(BotClient $bot, Message $message) {
    try {
        $message->deleteMessage();
        $bot->downloadFile(file_id:$message->file_id,chunk_size:$message->file_size, file_name:$message->file_name);
        $bot->sendImage(chat_id:$message->chat_id, file_path:$message->file_name, caption:$message->text, reply_to_message:$message->reply_to_message_id, metadata:$message->metadata);
    } catch (\Exception $e) {
        echo 'Eror: ' . $e->getMessage() . PHP_EOL;
    }
    $bot->stopPropagation();
});

$bot->onMessage(Filters::and(Filters::group(), Filters::senderId($admin_sender_id), Filters::video()), function(BotClient $bot, Message $message) {
    try {
        $message->deleteMessage();
        $bot->downloadFile(file_id:$message->file_id,chunk_size:$message->file_size, file_name:$message->file_name);
        $bot->sendVideo(chat_id:$message->chat_id, file_path:$message->file_name, caption:$message->text, reply_to_message:$message->reply_to_message_id, metadata:$message->metadata);
    } catch (\Exception $e) {
        echo 'Eror: ' . $e->getMessage() . PHP_EOL;
    }
    $bot->stopPropagation();
});

$bot->onMessage(Filters::and(Filters::group(), Filters::senderId($admin_sender_id), Filters::text()), function(BotClient $bot, Message $message) {
    try {
        $message->deleteMessage();
        $bot->sendMessage(chat_id:$message->chat_id, text:$message->text . "\n[Send from]($message->sender_id)", reply_to_message:$message->reply_to_message_id, metadata:$message->metadata);
    } catch (\Exception $e) {
        echo 'Eror: ' . $e->getMessage() . PHP_EOL;
    }
});

$bot->onMessage(Filters::and(Filters::private(), Filters::text('اینلاین')), function(BotClient $bot, Message $message) {
    $keypad = new KeypadInline();

    // ردیف اول
    $keypad->addRow([
        KeypadInline::simpleButton("Botkaplus_1", "Botkaplus 1")
    ]);

    // ردیف دوم
    $keypad->addRow([
        KeypadInline::simpleButton("Botkaplus_2", "Botkaplus 2"),
        KeypadInline::simpleButton("Botkaplus_3", "Botkaplus 3")
    ]);

    $inline_keypad = $keypad->build();
    $message->replyMessage("**hello __from ~~[Botkaplus!](https://github.com/sinyor-ehsan/Rubika)~~__**", inline_keypad:$inline_keypad);
});

$bot->onInlineMessage(null, function(BotClient $bot, Message $message) {
    if ($message->button_id === "Botkaplus_1"){$message->replyMessage("clicked on Botkaplus 1.");}
    else if ($message->button_id === "Botkaplus_2"){$message->replyMessage("clicked on Botkaplus 2.");}
    else if ($message->button_id === "Botkaplus_3"){$message->replyMessage("clicked on Botkaplus 3.");}
});

$bot->run();

?>
