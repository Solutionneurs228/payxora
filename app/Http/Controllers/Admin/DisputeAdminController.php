<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DisputeStatus;
use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Services\DisputeService;
use Illuminate\Http\Request;

class DisputeAdminController extends Controller
{
    public function __construct(
        private DisputeService $disputeService
    ) {}

    public function index()
    {
        $disputes = Dispute::with(['transaction.seller', 'transaction.buyer', 'initiator'])
            ->latest()
            ->paginate(20);

        return view('admin.disputes.index', compact('disputes'));
    }

    public function show(Dispute $dispute)
    {
        $dispute->load(['transaction', 'messages.user', 'initiator']);

        return view('admin.disputes.show', compact('dispute'));
    }

    public function arbitrate(Request $request, Dispute $dispute)
    {
        $validated = $request->validate([
            'resolution' => ['required', 'in:refund_buyer,release_seller'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $this->disputeService->arbitrate(
            $dispute,
            $validated['resolution'],
            $validated['notes'] ?? null
        );

        return redirect()->route('admin.disputes.show', $dispute)
            ->with('success', 'Litige arbitre avec succes.');
    }

    public function close(Dispute $dispute)
    {
        $this->disputeService->close($dispute);

        return redirect()->route('admin.disputes.index')
            ->with('success', 'Litige ferme.');
    }
}
