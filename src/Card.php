<?php

namespace Blackjack;

class Card
{
    const CARD_SUIT = [
        'S' => 'スペード',
        'H' => 'ハート',
        'C' => 'クラブ',
        'D' => 'ダイヤ',
    ];

    const CARD_NUMBER = [
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        '10' => 10,
        '11' => 'J',
        '12' => 'Q',
        '13' => 'K',
        '1' => 'A',
    ];

    const CARD_RANK = [
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        '10' => 10,
        '11' => 10,
        '12' => 10,
        '13' => 10,
        '1' => 11,
    ];


    public function __construct(private string $suit, private int $number)
    {
    }

    public function getSuit(): string
    {
        return self::CARD_SUIT[$this->suit];
    }

    public function getNumber(): string
    {
        return self::CARD_NUMBER[$this->number];
    }

    public function getRank(): int
    {
        return self::CARD_RANK[$this->number];
    }
}
