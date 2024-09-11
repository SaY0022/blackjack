<?php

namespace Blackjack;

require_once('Deck.php');
require_once('Dealer.php');

class Guest {
    private int $moneyAmount = 10000;
    private int $moneyBet = 0;
    private $score = 0;
    private $cardId = 0;
    private $ACount = 0;
    private $cards = [];

    public function __construct(private string $name)
    {
    }

    public function betMoney()
    {
        echo $this->name . 'の所持金は' . $this->moneyAmount . 'ドルです。' . PHP_EOL;
        $arrayGuestMoneyBet = [10, 20, 50, 100, 200, 500, 1000, 2000];
        $this->moneyBet = $arrayGuestMoneyBet[array_rand($arrayGuestMoneyBet, 1)];
        echo $this->moneyBet . 'ドルを賭けます。' . PHP_EOL;
        fgets(STDIN);
    }

    public function guestDrawCards(Deck $deck)
    {
        $this->cards[$this->cardId] = $deck->drawCards();

        echo $this->name . 'の引いたカードは「' . $this->cards[$this->cardId][0] . 'の' . $this->cards[$this->cardId][1] . '」です。' . PHP_EOL;

        $this->score += $this->cards[$this->cardId][2];

        if ($this->cards[$this->cardId][1] === 'A') {
            $this->ACount++;
        }
        if ($this->score >= 22 && $this->ACount > 0) {
            $this->score -= 10;
            $this->ACount--;
        }
        // if ($this->cardId === 1) {
        //     echo $this->name . ' にカードが配られました。' . PHP_EOL;
        //     fgets(STDIN);
        // }
        $this->cardId++;
    }

    public function guestDrawMoreCards(Deck $deck)
    {
        echo $this->name . 'の現在の得点は';
        foreach ($this->cards as $key => $card) {
            echo '「' . $card[0] . 'の' . $card[1] . "」";
        }
        echo 'で' . $this->score . '点です。' . PHP_EOL;

        if ($this->score < 17) {
            while ($this->score < 17) {
                $this->cards[$this->cardId] = $deck->drawCards();

                echo $this->name . 'がもう１枚引いたカードは「' . $this->cards[$this->cardId][0] . 'の' . $this->cards[$this->cardId][1] . '」です。' . PHP_EOL;
                // echo $this->name . 'はもう１枚カードを引きました。' . PHP_EOL;

                $this->score += $this->cards[$this->cardId][2];

                if ($this->cards[$this->cardId][1] === 'A') {
                    $this->ACount++;
                }
                if ($this->score >= 22 && $this->ACount > 0) {
                    $this->score -= 10;
                    $this->ACount--;
                }
                $this->cardId++;
                // fgets(STDIN);
            }
        } else {
            echo $this->name . 'はカードを追加しませんでした。' . PHP_EOL;
        }
        fgets(STDIN);
    }

    public function judgeScore(Dealer $dealer)
    {
        echo $this->name . 'の得点は';
        foreach ($this->cards as $key => $card) {
            echo '「' . $card[0] . 'の' . $card[1] . "」";
        }
        echo 'で' . $this->score . '点です。' . PHP_EOL;

        if ($this->score >= 22) {
            $this->score = 0;
        }

        if ($this->score > $dealer->getScore()) {
            $moneyAmount = $this->moneyAmount + $this->moneyBet;
            echo '　勝ち！（' . $this->name . 'の所持金は　' . $this->moneyAmount . '　->　' . $moneyAmount . '　ドルになりました）' .  PHP_EOL;
            $this->moneyAmount = $moneyAmount;
        } elseif ($this->score < $dealer->getScore()) {
            $moneyAmount = $this->moneyAmount - $this->moneyBet;
            echo '　負け！（' . $this->name . 'の所持金は　' . $this->moneyAmount . '　->　' . $moneyAmount . '　ドルになりました）' .  PHP_EOL;
            $this->moneyAmount = $moneyAmount;
        } else {
            echo '　引き分け！（' . $this->name . 'の所持金は　' . $this->moneyAmount . 'ドルのままです）' .  PHP_EOL;
        }

        fgets(STDIN);
    }

    public function initValues()
    {
        $this->score = 0;
        $this->cardId = 0;
        $this->ACount = 0;
        $this->cards = [];
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMoney()
    {
        return $this->moneyAmount;
    }
}
