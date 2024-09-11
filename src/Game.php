<?php

namespace Blackjack;

require_once('Deck.php');
require_once('Player.php');
require_once('Guest.php');
require_once('Dealer.php');

class Game
{
    public function __construct()
    {
    }

    public function start()
    {
        $player1 = new Player('あなた');
        $guest1 = new Guest('ゲスト１');
        $guest2 = new Guest('ゲスト２');
        $dealer = new Dealer('ディーラー');
        $deck = new Deck();

        echo 'ブラックジャックを開始します。';
        fgets(STDIN);
        echo PHP_EOL;

        while (true) {

            $player1->betMoney();
            $guest1->betMoney();
            $guest2->betMoney();

            $player1->drawCards($deck);
            $player1->drawCards($deck);

            // $player1->doSplit();

            fgets(STDIN);

            $guest1->guestDrawCards($deck);
            $guest1->guestDrawCards($deck);
            fgets(STDIN);

            $guest2->guestDrawCards($deck);
            $guest2->guestDrawCards($deck);
            fgets(STDIN);

            $dealer->drawCards($deck);
            $dealer->dealerSecondDrawCards($deck);
            fgets(STDIN);

            if (true) {
                if ($player1->getFirstCardNumber() === $player1->getSecondCardNumber()) {
                    while (true) {
                        echo 'スプリットしますか？（y/n）';
                        $input = trim(fgets(STDIN));
                        if ($input === 'y') {
                            echo 'スプリットしました！' . PHP_EOL;
                            fgets(STDIN);
                            $player1->isSplit();
                            $player1Split = new PlayerSplit();
                            $player1Split->copyBetCard($player1);
                            $player1->drawCards($deck);
                            $player1Split->drawCards($deck, $player1);
                            break;
                        } elseif ($input === 'n') {
                            $player1->notSplit();
                            break;
                        }
                    }
                }
            }

            $player1->drawMoreCards($deck);
            if (isset($player1Split)) {
                $player1Split->drawMoreCards($deck, $player1);
            }
            $guest1->guestDrawMoreCards($deck);
            $guest2->guestDrawMoreCards($deck);
            $dealer->dealerDrawMoreCards($deck);
            fgets(STDIN);

            $player1->judgeScore($dealer);
            if (isset($player1Split)) {
                $player1Split->judgeScore($dealer, $player1);
            }
            $guest1->judgeScore($dealer);
            $guest2->judgeScore($dealer);

            while (true) {
                echo 'ブラックジャックを続けますか？（y/n）';
                $input = trim(fgets(STDIN));

                if ($input === 'y'){
                    echo PHP_EOL;
                    break;
                } elseif ($input === 'n') {
                    echo PHP_EOL;
                    $money = [$player1->getMoney(), $guest1->getMoney(), $guest2->getMoney()];
                    echo $player1->getName() . 'の所持金は、' . $player1->getMoney() . 'ドル！' . PHP_EOL;
                    echo $guest1->getName() . 'の所持金は、' . $guest1->getMoney() . 'ドル！' . PHP_EOL;
                    echo $guest2->getName() . 'の所持金は、' . $guest2->getMoney() . 'ドル！' . PHP_EOL;
                    fgets(STDIN);

                    if ($money[0] === max($money)) {
                        echo $player1->getName() . 'の優勝です！おめでとうございます！' . PHP_EOL;
                    } elseif ($money[1] === max($money)) {
                        echo $guest1->getName() . 'の優勝です！' . PHP_EOL;
                    } elseif ($money[2] === max($money)) {
                        echo $guest2->getName() . 'の優勝です！' . PHP_EOL;
                    }
                    echo 'ブラックジャックを終了します。' . PHP_EOL . PHP_EOL;
                    exit(0);
                }
            }

            $player1->initValues();
            if (isset($player1Split)) {
                $player1Split = null;
            }
            $guest1->initValues();
            $guest2->initValues();
            $dealer->initValues();
        }
    }
}
