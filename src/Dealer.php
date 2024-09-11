<?php

namespace Blackjack;

require_once('Deck.php');

class Dealer {
    private $score = 0;
    private $cardId = 0;
    private $ACount = 0;
    private $cards = [];

    public function __construct(private string $name)
    {
    }

    public function drawCards(Deck $deck)
    {
        $this->cards[] = $deck->drawCards();

        echo $this->name . 'の引いたカードは「' . $this->cards[$this->cardId][0] . 'の' . $this->cards[$this->cardId][1] . '」です。' . PHP_EOL;

        $this->score += $this->cards[$this->cardId][2];

        if ($this->cards[$this->cardId][1] === 'A') {
            $this->ACount++;
        }
        if ($this->score >= 22 && $this->ACount > 0) {
            $this->score -= 10;
            $this->ACount--;
        }
        $this->cardId++;
    }

    public function dealerSecondDrawCards(Deck $deck)
    {
        $this->cards[] = $deck->drawCards();

        echo $this->name . 'の引いた２番目のカードは分かりません。' . PHP_EOL;
    }

    public function dealerDrawMoreCards(Deck $deck)
    {
        echo $this->name . 'の引いた２枚目のカードは「' . $this->cards[$this->cardId][0] . 'の' . $this->cards[$this->cardId][1] . '」でした。' . PHP_EOL;

        $this->score += $this->cards[$this->cardId][2];

        if ($this->cards[$this->cardId][1] === 'A') {
            $this->ACount++;
        }
        if ($this->score >= 22 && $this->ACount > 0) {
            $this->score -= 10;
            $this->ACount--;
        }
        $this->cardId++;

        echo $this->name . 'の現在の得点は';
        foreach ($this->cards as $key => $card) {
            echo '「' . $card[0] . 'の' . $card[1] . "」";
        }
        echo 'で' . $this->score . '点です。';
        fgets(STDIN);

        while ($this->score < 17) {
            $this->cards[] = $deck->drawCards();

            echo $this->name . 'がもう１枚引いたカードは「' . $this->cards[$this->cardId][0] . 'の' . $this->cards[$this->cardId][1] . '」です。' . PHP_EOL;

            $this->score += $this->cards[$this->cardId][2];

            if ($this->cards[$this->cardId][1] === 'A') {
                $this->ACount++;
            }
            if ($this->score >= 22 && $this->ACount > 0) {
                $this->score -= 10;
                $this->ACount--;
            }
            if ($this->score >= 22) {
                echo '得点が21を超えてしまいました。（バースト）'. PHP_EOL;
                fgets(STDIN);
                break;
            }
            $this->cardId++;
            echo $this->name . 'の現在の得点は' . $this->score . 'です。';
            fgets(STDIN);
        }
        echo $this->name . 'の得点は';
            foreach ($this->cards as $key => $card) {
                echo '「' . $card[0] . 'の' . $card[1] . "」";
            }
        echo 'の' . $this->score . '点で確定しました。' . PHP_EOL . PHP_EOL;
    }

    public function getScore()
    {
        if ($this->score >= 22) {
            $this->score = 1;
        }

        return $this->score;
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
}
