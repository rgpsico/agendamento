<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Services\CRM\LeadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PipelineController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Lead::class);

        $tenantId = $this->tenantId();
        $statuses = Lead::$pipelineStatus;
        $leads = Lead::forTenant($tenantId)
            ->with(['campanha', 'responsavel', 'historicos'])
            ->latest()
            ->get()
            ->groupBy(fn (Lead $lead) => $lead->pipeline_status ?: 'novo_lead');

        return view('crm.pipeline.index', compact('statuses', 'leads'));
    }

    public function move(Request $request, Lead $lead, LeadService $service)
    {
        $this->authorize('move', $lead);

        $validated = $request->validate([
            'pipeline_status' => 'required|string|in:' . implode(',', array_keys(Lead::$pipelineStatus)),
            'observacao' => 'nullable|string|max:1000',
        ]);

        $lead = $service->moverPipeline($lead, $validated['pipeline_status'], Auth::id(), $validated['observacao'] ?? null);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Lead movido com sucesso.',
                'lead' => [
                    'id' => $lead->id,
                    'pipeline_status' => $lead->pipeline_status,
                    'pipeline_status_label' => $lead->pipeline_status_label,
                ],
            ]);
        }

        return back()->with('success', 'Lead movido com sucesso.');
    }

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403);

        return (int) Auth::user()->empresa->id;
    }
}
