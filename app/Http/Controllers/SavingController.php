<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavingController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $keyword = $request->query('q');
        $today = now()->toDateString();

        $savings = $user->savings()
            ->search($keyword)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $total = (float) $user->savings()->sum('amount');
        $setting = $user->resolveSavingSetting();
        $target = (float) $setting->target_amount;
        $progress = $target > 0 ? min(100, ($total / $target) * 100) : 0;

        return view('savings.index', [
            'savings' => $savings,
            'total' => $total,
            'target' => $target,
            'progress' => $progress,
            'setting' => $setting,
            'keyword' => $keyword,
            'hasSavedToday' => $user->savings()->whereDate('date', $today)->exists(),
            'today' => $today,
        ]);
    }

    public function create(Request $request): View
    {
        $setting = $request->user()->resolveSavingSetting();

        return view('savings.form', [
            'saving' => null,
            'defaultAmount' => (float) $setting->daily_amount,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->user()->savings()->create($this->validated($request));

        return redirect()->route('savings.index')
            ->with('success', 'Setoran tabungan berhasil dicatat.');
    }

    /**
     * One-click deposit for today, used by the dashboard reminder.
     */
    public function storeToday(Request $request): RedirectResponse
    {
        $amount = $request->input('amount');

        if (! is_numeric($amount) || (float) $amount < 1) {
            return back()->with('error', 'Nominal setoran tidak valid. Silakan coba lagi.');
        }

        $request->user()->savings()->create([
            'amount' => $amount,
            'date' => now()->toDateString(),
            'note' => $request->input('note', 'Setoran harian'),
        ]);

        return back()->with('success', 'Terima kasih! Menabung hari ini berhasil dicatat.');
    }

    public function show(Request $request, $saving): View
    {
        return view('savings.show', [
            'saving' => $this->resolve($request, $saving),
        ]);
    }

    public function edit(Request $request, $saving): View
    {
        return view('savings.form', [
            'saving' => $this->resolve($request, $saving),
            'defaultAmount' => 0,
        ]);
    }

    public function update(Request $request, $saving): RedirectResponse
    {
        $saving = $this->resolve($request, $saving);
        $saving->update($this->validated($request));

        return redirect()->route('savings.index')
            ->with('success', 'Data tabungan berhasil diperbarui.');
    }

    public function destroy(Request $request, $saving): RedirectResponse
    {
        $this->resolve($request, $saving)->delete();

        return redirect()->route('savings.index')
            ->with('success', 'Data tabungan berhasil dihapus.');
    }

    public function editSettings(Request $request): View
    {
        return view('savings.settings', [
            'setting' => $request->user()->resolveSavingSetting(),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'target_amount' => ['required', 'numeric', 'min:1', 'max:999999999999999'],
            'daily_amount' => ['required', 'numeric', 'min:1', 'max:999999999999999'],
        ]);

        $user = $request->user();

        if ($user->savingSetting) {
            $user->savingSetting->update($data);
        } else {
            $user->savingSetting()->create($data);
        }

        return redirect()->route('savings.settings')
            ->with('success', 'Target tabungan berhasil diperbarui.');
    }

    /**
     * Load a saving record only from the authenticated user's own records.
     */
    private function resolve(Request $request, $saving): Saving
    {
        return $request->user()
            ->savings()
            ->findOrFail((int) $saving);
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999999999'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
