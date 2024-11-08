<?php


use App\Models\PartnerSale\PartnerSale;
use App\Presenter\PartnerSalePresenter;
use App\Repositories\AbstractRepository;

class PartnerSaleRepository extends AbstractRepository
{
    protected $model;

    protected $fieldSearchable = [
        'partner_id',
        'manager_id',
        'warehouse_id',
        'product_item_id',
        'product_item_shipment_id',
        'season_id'
    ];

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return PartnerSale::class;
    }

    public function presenter(): string
    {
        return PartnerSalePresenter::class;
    }
}
