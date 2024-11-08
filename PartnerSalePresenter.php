<?php


use App\Transformers\PartnerSaleTransformer;

class PartnerSalePresenter extends \App\Presenter\AbstractPresenter
{
    /**
     * @inheritDoc
     */
    public function getTransformer(): string
    {
        return PartnerSaleTransformer::class;
    }
}
