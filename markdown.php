<?php

require "vendor/autoload.php";
use Botkaplus\BotClient;
use Botkaplus\Message;

echo "start\n";

$token = "token_bot";
$inData = file_get_contents('php://input');
$Data = json_decode($inData);

$bot = new BotClient(token: $token, rData:$Data);

$bot->onMessage(null, function(BotClient $bot, Message $message) {
    $text = <<<'EOT'
        🎉**تست کامل Markdown**

        این یک متن __ایتالیک__ و این هم --زیر خط دار-- است.
        همچنین میتوانیم ~~خط خورده~~ و ||اسپویلر|| داشته باشیم!

        ##این یک quote چند خطی است
        که شامل __فرمت های__ مختلف می شود
        و حتی `کد` هم دارد!##
        **لیست امکانات:**
        * کد تک خطی: `echo Hello`
        * لینک: [روبیکا](https://rubika.ir)
        * ایموجی: 🎉🎊🎭🎪🎨🎲🎯
        ```php
        function test_markdown() {
            return "این یک بلوک کد است"
        }```


        __نکته مهم__: تمام فرمت ها با هم ترکیب می شوند! ✨
        **~~ترکیب فرمت ها~~** و ||--اسپویلر خط--||🎁

        این برای تست کامل پردازش Markdown در کتابخانه باتکا پلاس است.🎊
        
        EOT;
    $message->replyMessage(text:$text);
});

$bot->run();

?>