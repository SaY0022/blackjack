<?php

namespace Blackjack;

require_once('Card.php');

class Deck
{
    public array $cards;
    private int $drawId = -1;

    public function __construct()
    {
        foreach (['C', 'H', 'S', 'D'] as $suit) {
            foreach (range(1, 13) as $number) {
                $this->cards[] = new Card($suit, $number);
            }
        }
        shuffle($this->cards);
    }

    public function drawCards(): array
    {
        $this->drawId++;
        $card = $this->cards[$this->drawId];
        return [$card->getSuit(), $card->getNumber(), $card->getRank()];
    }
}
