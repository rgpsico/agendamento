<?php

namespace App\Http\Controllers;

use App\Models\PaymentConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentConfigurationController extends Controller
{

    public function test(Request $request)
    {
        $data = $request->validate([
            'pix_enabled' => 'boolean',
            'cartao_enabled' => 'boolean',
            'pix_config' => 'array',
            'cartao_config' => 'array',
        ]);

        // Lógica para testar as configurações (ex.: verificar conexão com API de PIX ou cartão)
        try {
            // Exemplo: testar configurações de PIX ou cartão
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }
    /**
     * Display the payment configuration for a given empresa.
     *
     * @param  int  $empresaId
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $empresaId = Auth::user()->id;
        $paymentConfig = PaymentConfiguration::getForEmpresa($empresaId);

        return view('admin.empresas.configuracao', compact('paymentConfig'));
    }

    public function update(Request $request, $empresaId)
    {



        $request->validate([
            'pix_enabled' => 'boolean',
            'cartao_enabled' => 'boolean',
            'presencial_enabled' => 'boolean',
            'pix_config' => 'array',
            'cartao_config' => 'array',
            'presencial_config' => 'array',
        ]);


        $paymentConfig = PaymentConfiguration::getForEmpresa($empresaId);

        $paymentConfig->update([
            'pix_enabled' => $request->boolean('pix_enabled'),
            'cartao_enabled' => $request->boolean('cartao_enabled'),
            'presencial_enabled' => $request->boolean('presencial_enabled'),
            'pix_config' => $request->input('pix_config', []),
            'cartao_config' => $request->input('cartao_config', []),
            'presencial_config' => $request->input('presencial_config', []),
        ]);

        return redirect()->back()->with('success', 'Configurações de pagamento atualizadas com sucesso!');
    }


    public function store(Request $request)
    {
        $request->validate([
            'pix_enabled' => 'boolean',
            'cartao_enabled' => 'boolean',
            'presencial_enabled' => 'boolean',
            'pix_config' => 'array',
            'cartao_config' => 'array',
            'presencial_config' => 'array',
        ]);

        $paymentConfig = PaymentConfiguration::getForEmpresa($empresaId);

        $paymentConfig->update([
            'pix_enabled' => $request->boolean('pix_enabled'),
            'cartao_enabled' => $request->boolean('cartao_enabled'),
            'presencial_enabled' => $request->boolean('presencial_enabled'),
            'pix_config' => $request->input('pix_config', []),
            'cartao_config' => $request->input('cartao_config', []),
            'presencial_config' => $request->input('presencial_config', []),
        ]);

        return redirect()->back()->with('success', 'Configurações de pagamento atualizadas com sucesso!');
    }
}
