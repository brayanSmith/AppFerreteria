<?php

namespace App\Observers;

use App\Models\DetalleCompra;

class DetalleCompraObserver
{
    /**
     * Handle the DetalleCompra "created" event.
     */
    public function created(DetalleCompra $detalleCompra): void
    {
        //
    }

    /**
     * Handle the DetalleCompra "updated" event.
     */
    public function updated(DetalleCompra $detalleCompra): void
    {
        //
    }

    /**
     * Handle the DetalleCompra "deleted" event.
     */
    public function deleted(DetalleCompra $detalleCompra): void
    {
        //
    }

    /**
     * Handle the DetalleCompra "restored" event.
     */
    public function restored(DetalleCompra $detalleCompra): void
    {
        //
    }

    /**
     * Handle the DetalleCompra "force deleted" event.
     */
    public function forceDeleted(DetalleCompra $detalleCompra): void
    {
        //
    }
}
