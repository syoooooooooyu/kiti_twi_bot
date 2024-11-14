<?php

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Command\Command;
use Discord\Parts\Interactions\Interaction as CommandInteraction;
use Discord\WebSockets\Intents;

include __DIR__."/vendor/autoload.php";

class main
{

    public function random(): string{
        $txt = file_get_contents(__DIR__."/kiti.txt");
        $rand = rand(1, 100);
        $sub = substr($txt, (strpos($txt, $rand.".") + strlen($rand.".")));
        $sub = substr($sub, 0, strpos($sub, ($rand+1)."."));

        $res = str_replace("*", "\*", $sub);
        $res = str_replace("_", "\_", $res);
        $res = str_replace("~", "\~", $res);
        $res = str_replace("||", "\|\|", $res);
        $res = str_replace("`", "\`", $res);
        $res = str_replace(">", "\>", $res);
        return $res;
    }

    public function run(): void
    {
        $token = file_get_contents(__DIR__ . "/token/discord_token.txt");

        $discord = new Discord([
            "token" => $token,
            "intents" => Intents::getDefaultIntents()
        ]);

        $discord->on("ready", function (Discord $discord){
            var_dump(1);

            foreach ($discord->guilds as $guild){
                $command = new Command($discord, ["name" => "kiti", "description" => "死ね"]);
                $guild->commands->save($command);

                $discord->listenCommand("kiti", function(CommandInteraction $interaction) use($discord, $guild){
                    $interaction->respondWithMessage(MessageBuilder::new()->setContent($this->random()));
                });
            }
        });

        $discord->run();
    }
}

$bot = new main();
$bot->run();
