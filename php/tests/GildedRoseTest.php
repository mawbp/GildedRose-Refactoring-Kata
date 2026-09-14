<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    /**
     * @dataProvider itemProvider
    */
    public function testUpdateQualityBehavior(string $name, int $sellIn, int $quality, int $expectedSellIn, int $expectedQuality): void
    {
        $items = [new Item($name, $sellIn, $quality)];
        $gildedRose = new GildedRose($items);
        
        $gildedRose->updateQuality();

        $this->assertSame($expectedSellIn, $items[0]->sellIn);
        $this->assertSame($expectedQuality, $items[0]->quality);
    }

    public static function itemProvider(): array
    {
        return [

            'Items: Quality tidak pernah negatif' => [
                'Item', 5, 0, 4, 0
            ],

            'Aged Brie: Quality maksimal 50' => [
                'Aged Brie', 5, 50, 4, 50
            ],


            // --- STANDARD ITEMS ---
            'Standard Item: Penurunan normal' => [
                'Normal Item', 10, 20, 9, 19
            ],
            'Standard Item: Kadaluarsa berkurang 2x lebih cepat' => [
                'Normal Item', 0, 20, -1, 18
            ],

            // --- AGED BRIE ---
            'Aged Brie: Quality naik seiring waktu' => [
                'Aged Brie', 10, 10, 9, 11
            ],
            'Aged Brie: Quality naik 2x setelah kadaluarsa' => [
                'Aged Brie', 0, 10, -1, 12
            ],

            // --- SULFURAS ---
            'Sulfuras: SellIn dan Quality tidak berubah' => [
                'Sulfuras, Hand of Ragnaros', 5, 80, 5, 80
            ],

            // --- CONJURED ---
            'Conjured: SellIn dan Quality berkurang 2x dari normal item' => [
                'Conjured Mana Cake', 5, 20, 4, 18
            ],
            'Conjured: SellIn dan Quality berkurang 2x dari normal item' => [
                'Conjured Mana Cake', 0, 15, -1, 11
            ],

            // --- BACKSTAGE PASSES ---
            'Backstage Passes: Naik 1 jika sellIn > 10' => [
                'Backstage passes to a TAFKAL80ETC concert', 15, 20, 14, 21
            ],
            'Backstage Passes: Naik 2 jika sellIn <= 10' => [
                'Backstage passes to a TAFKAL80ETC concert', 10, 20, 9, 22
            ],
            'Backstage Passes: Naik 3 jika sellIn <= 5' => [
                'Backstage passes to a TAFKAL80ETC concert', 5, 20, 4, 23
            ],
            'Backstage Passes: Drop ke 0 setelah konser' => [
                'Backstage passes to a TAFKAL80ETC concert', 0, 20, -1, 0
            ],
        ];
    }
}
