<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    private function decrDay($item) {
        if ($item->name != 'Sulfuras, Hand of Ragnaros') {
            $item->sellIn -= 1;
        }
    }

    private function isExpired($day) {
        return $day <= 0;
    }

    private function isZeroQuality($quality) {
        return $quality <= 0;
    }

    private function isMaxQuality($quality) {
        return $quality == 50;
    }

    private function updateNormalItem($item) {
        $isExpired = $this->isExpired($item->sellIn);
        $isZeroQuality = $this->isZeroQuality($item->quality);
        if (!$isZeroQuality) {
            if (!$isExpired) {
                $item->quality -= 1;
            } else {
                $item->quality -= 2;
            }
        }
        
        // Ensure quality doesn't drop below 0
        if ($item->quality < 0) {
            $item->quality = 0;
        }
    }

    private function updateAgedBrie($item) {
        $isExpired = $this->isExpired($item->sellIn);
        $isMaxQuality = $this->isMaxQuality($item->quality);
        if(!$isMaxQuality) {
            if (!$isExpired) {
                $item->quality += 1;
            } else {
                $item->quality += 2;
            }
        }
        
        // Ensure quality doesn't exceed 50
        if ($item->quality > 50) {
            $item->quality = 50;
        }
    }

    private function updateBackstagePasses($item) {
        $isExpired = $this->isExpired($item->sellIn);
        $isMaxQuality = $this->isMaxQuality($item->quality);
        $remainingDays = $item->sellIn;

        if (!$isMaxQuality) {
            $item->quality += 1;
            if ($remainingDays <= 10) {
                $item->quality += 1;
            }
            if ($remainingDays <= 5) {
                $item->quality += 1;
            }
            
            // Ensure quality doesn't exceed 50
            if ($item->quality > 50) {
                $item->quality = 50;
            }
        }

        if ($isExpired) {
            $item->quality = 0;
        }
    }

    private function updateConjured($item) {
        $isExpired = $this->isExpired($item->sellIn);
        $isZeroQuality = $this->isZeroQuality($item->quality);
        if (!$isZeroQuality) {
            if (!$isExpired) {
                $item->quality -= 2;
            } else {
                $item->quality -= 4;
            }
        }
        
        // Ensure quality doesn't drop below 0
        if ($item->quality < 0) {
            $item->quality = 0;
        }
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            switch ($item->name) {
                case "Aged Brie":
                    $this->updateAgedBrie($item);
                    break;
                case "Backstage passes to a TAFKAL80ETC concert":
                    $this->updateBackstagePasses($item);
                    break;
                case "Conjured Mana Cake":
                    $this->updateConjured($item);
                    break;    
                case "Sulfuras, Hand of Ragnaros":
                    break;
                default:
                    $this->updateNormalItem($item);
                    break;
            }

            $this->decrDay($item);
        }
    }
}
