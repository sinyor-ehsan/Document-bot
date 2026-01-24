<?php

require "vendor/autoload.php";
use Rubing\BotClient;
use Rubing\Filters;
use Rubing\Message;
use Rubing\InlineKeypad;

// Your sender_id = Array["String", "String"] or "String"
$admin_sender_id = "";

echo "start\n";

$token = "token_bot";

$bot = new BotClient(token: $token);
// $bot->setWebhook('https://yourdomain.com/HiddenSender.php');

$bot->onMessage(Filters::and(Filters::group(), Filters::senderId($admin_sender_id), Filters::photo()), function(BotClient $bot, Message $message) {
    try {
        $message->deleteMessage();
        $bot->downloadFile(file_id:$message->file_id,chunk_size:$message->file_size, file_name:$message->file_name);
        $bot->sendImage(chat_id:$message->chat_id, file_path:$message->file_name, caption:$message->text, reply_to_message:$message->reply_to_message_id, metadata:$message->metadata);
        unlink($message->file_name);
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
        unlink($message->file_name);
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
    $keypad = new InlineKeypad();

    // ردیف اول
    $keypad->addRow([
        InlineKeypad::buttonSimple("Rubing_1", "Rubing 1")
    ]);

    // ردیف دوم
    $keypad->addRow([
        InlineKeypad::buttonSimple("Rubing_2", "Rubing 2"),
        InlineKeypad::buttonSimple("Rubing_3", "Rubing 3")
    ]);

    $inline_keypad = $keypad->build();
    $message->replyMessage("**hello __from ~~[Rubing!](https://github.com/sinyor-ehsan/Rubika)~~__**", inline_keypad:$inline_keypad);
});

$bot->onInlineMessage(null, function(BotClient $bot, Message $message) {
    if ($message->button_id === "Rubing_1"){$message->replyMessage("clicked on Rubing 1.");}
    else if ($message->button_id === "Rubing_2"){$message->replyMessage("clicked on Rubing 2.");}
    else if ($message->button_id === "Rubing_3"){$message->replyMessage("clicked on Rubing 3.");}
});

$bot->run();

?>
