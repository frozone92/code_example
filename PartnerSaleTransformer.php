<?php


use App\Models\PartnerSale\PartnerSale;
use App\Transformers\Product\ProductItemTransformer;
use League\Fractal\Resource\{Item, Primitive};

class PartnerSaleTransformer extends \App\Transformers\AbstractTransformer
{
    public array $defaultIncludes = [
        'partner',
        'manager',
        'warehouse',
        'product_item',
        'product_item_shipment',
        'season'
    ];

    public function transform(PartnerSale $partnerSale): array
    {
        return [
            'id' => $partnerSale->id,
            'datetime' => $partnerSale->datetime,
            'sum' => $partnerSale->sum,
            'amount' => $partnerSale->amount
        ];
    }

    public function includePartner(PartnerSale $partnerSale): Item
    {
        return $this->item($partnerSale->partner, new \App\Transformers\ModelTransformer());
    }

    public function includeManager(PartnerSale $partnerSale): Primitive|Item
    {
        return $this->nullableItem($partnerSale->manager, new \App\Transformers\SimpleProfileTransformer());
    }

    public function includeWarehouse(PartnerSale $partnerSale): Item
    {
        return $this->item($partnerSale->warehouse, new \App\Transformers\ModelTransformer());
    }

    public function includeProductItem(PartnerSale $partnerSale): Item
    {
        return $this->item($partnerSale->product_item, new ProductItemTransformer());
    }

    public function includeProductItemShipment(PartnerSale $partnerSale): Item
    {
        return $this->item($partnerSale->product_item_shipment, new \App\Transformers\ModelTransformer());
    }

    public function includeSeason(PartnerSale $partnerSale): Primitive|Item
    {
        return $this->nullableItem($partnerSale->season, new \App\Transformers\ModelTransformer());
    }
}
