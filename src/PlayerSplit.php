<?php

namespace Blackjack;

// use Blackjack\Dealer;

require_once('Deck.php');
require_once('Dealer.php');
require_once('Player.php');

class PlayerSplit {
    private int $moneyAmount = 0;
    private int $moneyBet = 0;
    private $score = 0;
    private $cardId = 0;
    private $ACount = 0;
    private $cards = [];
    private $doubleDown = 0;
    private $surrender = 0;
    private $action = '';


    public function __construct()
    {
    }

    // public function betMoney()
    // {
    //     while (true) {
    //         echo $this->name . 'の所持金は' . $this->moneyAmount . 'ドルです。' . PHP_EOL;
    //         echo '賭け金を入力してください(半角数字)。：';
    //         $this->moneyBet = (int)trim(fgets(STDIN));

    //         if ($this->moneyBet > $this->moneyAmount) {
    //             echo '賭け金は所持金以内で入力してください。' . PHP_EOL . PHP_EOL;
    //         } elseif ($this->moneyBet < 1) {
    //             echo '賭け金は1ドル以上で入力してください。' . PHP_EOL . PHP_EOL;
    //         } else {
    //             echo $this->moneyBet . 'ドルを賭けます。' . PHP_EOL;
    //             break;
    //         }
    //     }
    //     fgets(STDIN);
    // }

    public function copyBetCard(Player $player)
    {
        $this->moneyBet = $player->getBet();
        $this->cards[0] = $player->getSecondCard();
        $this->cardId = 1;
        $this->score += $this->cards[0][2];
    }

    public function drawCards(Deck $deck, Player $player)
    {
        $this->cards[$this->cardId] = $deck->drawCards();

        echo $player->getName() . '（２デッキ目）の引いたカードは「' . $this->cards[$this->cardId][0] . 'の' . $this->cards[$this->cardId][1] . '」です。' . PHP_EOL;

        $this->score += $this->cards[$this->cardId][2];

        // var_dump($this->cards);

        if ($this->cards[$this->cardId][1] === 'A') {
            $this->ACount++;
        }
        if ($this->score >= 22 && $this->ACount > 0) {
            $this->score -= 10;
            $this->ACount--;
        }
        $this->cardId++;
    }

    public function drawMoreCards(Deck $deck, Player $player)
    {
        if ($this->cardId === 2) {

            echo $player->getName() . '（２デッキ目）の現在の得点は';
            foreach ($this->cards as $key => $card) {
                echo '「' . $card[0] . 'の' . $card[1] . "」";
            }
            echo 'で' . $this->score . '点です。' . PHP_EOL;

            while (true) {
                echo 'ダブルダウン(d) ・ サレンダー(s) ・ どちらもしない(n)  のどれにしますか？（d/s/n）';
                $input = trim(fgets(STDIN));
                if ($input === 'd') {
                    $this->action = '（ダブルダウン）';
                    $this->moneyBet *= 2;
                    $this->doubleDown = 1;
                    echo '賭け金が２倍になりました。';
                    fgets(STDIN);
                    break;
                } elseif ($input === 's') {
                    $this->action = '（サレンダー）';
                    $this->surrender = 1;
                    echo 'サレンダー（降参）しました。' . PHP_EOL;
                    fgets(STDIN);
                    break;
                } elseif ($input === 'n') {
                    break;
                }
            }
        }
        while (true) {
            if ($this->surrender === 1) {
                break;
            }

            if ($this->doubleDown === 0) {
                echo $player->getName() . '（２デッキ目）の現在の得点は' . $this->score . 'です。カードを引きますか？（y/n）';
                $input = trim(fgets(STDIN));
            } else {
            }

            if ($input === 'y' || $this->doubleDown === 1) {
                $this->cards[$this->cardId] = $deck->drawCards();
                echo $player->getName() . '（２デッキ目）の引いたカードは「' . $this->cards[$this->cardId][0] . 'の' . $this->cards[$this->cardId][1] . '」です。' . PHP_EOL;

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
                if ($this->doubleDown === 1) {
                    echo $player->getName() . '（２デッキ目）の得点は' . $this->score . 'です。' . PHP_EOL;
                    fgets(STDIN);
                    break;
                }
                $this->cardId++;
            } elseif ($input === 'n') {
                echo PHP_EOL;
                break;
            }
        }
    }

    public function judgeScore(Dealer $dealer, Player $player)
    {
        echo $player->getName() . '（２デッキ目）の得点は';
        foreach ($this->cards as $key => $card) {
            echo '「' . $card[0] . 'の' . $card[1] . "」";
        }
        echo 'で' . $this->score . '点です。' . $this->action . PHP_EOL;

        if ($this->score >= 22) {
            $this->score = 0;
        }

        if ($this->surrender === 1) {
            $moneyAmount = $player->getMoney() - $this->moneyBet / 2;
            echo '　降参！（' . $player->getName() . 'の所持金は　' . $player->getMoney() . '　->　' . $moneyAmount . '　ドルになりました）（賭け金を半分返却）' .  PHP_EOL;
        } elseif ($this->score > $dealer->getScore()) {
            $moneyAmount = $player->getMoney() + $this->moneyBet;
            echo '　勝ち！（' . $player->getName() . 'の所持金は　' . $player->getMoney() . '　->　' . $moneyAmount . '　ドルになりました）' .  PHP_EOL;
        } elseif ($this->score < $dealer->getScore()) {
            $moneyAmount = $player->getMoney() - $this->moneyBet;
            echo '　負け！（' . $player->getName() . 'の所持金は　' . $player->getMoney() . '　->　' . $moneyAmount . '　ドルになりました）' .  PHP_EOL;
        } else {
            echo '　引き分け！（' . $player->getName() . 'の所持金は　' . $player->getMoney() . 'ドルのままです）' .  PHP_EOL;
        }
        $this->moneyAmount = $moneyAmount;

        $player->getSplitMoney($this);
        fgets(STDIN);
    }

    public function initValues()
    {
        $this->score = 0;
        $this->cardId = 0;
        $this->ACount = 0;
        $this->cards = [];
        $this->doubleDown = 0;
        $this->surrender = 0;

    }

    // public function getName()
    // {
    //     return $this->name;
    // }

    // public function getMoney()
    // {
    //     return $this->moneyAmount;
    // }

    // public function getFirstCard()
    // {
    //     return $this->cards[0][1];
    // }

    // public function getSecondCard()
    // {
    //     return $this->cards[1][1];
    // }

    public function getSplitMoney()
    {
        return $this->moneyAmount;
    }


}
