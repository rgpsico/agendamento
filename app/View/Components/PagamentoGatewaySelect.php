<?php

namespace App\View\Components;

use App\Models\PagamentoGateway;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PagamentoGatewaySelect extends Component
{
    public $pagamento;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->pagamento = PagamentoGateway::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.payment-gateway-select');
    }
}
