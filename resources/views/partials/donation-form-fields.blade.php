{{--
    Shared donation form used by all three payment tabs on /donate.
    Expects:
      $channel     - 'online' | 'qr' | 'bank' — tells the controller which
                     PayMongo payment_method_types to request.
      $helperText  - short trust line shown under the submit button.

    Relies on the Alpine state declared on the outer x-data wrapper in
    donate.blade.php: amount, customAmount, setAmount().
--}}
<form method="POST" action="{{ route('donate.checkout') }}">
    @csrf
    <input type="hidden" name="channel" value="{{ $channel }}">

    {{-- Amount Selection --}}
    <label class="donate-label">Select Amount</label>
    <div class="amount-grid">
        <button type="button" class="amount-btn" :class="amount === 10000 && 'selected'" @click="setAmount(10000)">₱100</button>
        <button type="button" class="amount-btn" :class="amount === 50000 && 'selected'" @click="setAmount(50000)">₱500</button>
        <button type="button" class="amount-btn" :class="amount === 100000 && 'selected'" @click="setAmount(100000)">₱1,000</button>
        <button type="button" class="amount-btn" :class="amount === 500000 && 'selected'" @click="setAmount(500000)">₱5,000</button>
    </div>

    <div style="margin-bottom:20px;">
        <label class="donate-label">Or Enter Custom Amount (min ₱20)</label>
        <div style="position:relative;">
            <span style="position:absolute;left:16px;top:50%;transform:translateY(-50%);font-weight:700;color:rgba(13,42,82,0.3);font-size:15px;">₱</span>
            <input type="number" class="donate-input" style="padding-left:36px;" placeholder="0.00" min="20" step="1"
                   x-model="customAmount"
                   @input="amount = Math.round(parseFloat($event.target.value || 0) * 100); custom = true">
        </div>
    </div>

    {{-- Purpose selector --}}
    <div style="margin-bottom:20px;">
        <label class="donate-label">Purpose of Donation</label>
        <select name="purpose" class="donate-input" required>
            <option value="General Donation">General Donation</option>
            <option value="Church Maintenance">Church Maintenance</option>
            <option value="Outreach">Community Outreach</option>
            <option value="Youth Ministry">Youth Ministry</option>
        </select>
    </div>

    <input type="hidden" name="amount" :value="amount">

    {{-- Optional Info --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
        <div>
            <label class="donate-label">Name (Optional)</label>
            <input type="text" name="donor_name" class="donate-input" placeholder="Your name">
        </div>
        <div>
            <label class="donate-label">Email (Optional)</label>
            <input type="email" name="donor_email" class="donate-input" placeholder="your@email.com">
        </div>
    </div>
    <div style="margin-bottom:24px;">
        <label class="donate-label">Prayer / Message (Optional)</label>
        <textarea name="message" class="donate-input" rows="2" placeholder="Leave a prayer intention or message..." style="resize:vertical;"></textarea>
    </div>

    {{-- Submit --}}
    <button type="submit" class="donate-submit" :disabled="amount < 2000">
        <span x-text="amount >= 2000 ? 'Donate ₱' + (amount / 100).toLocaleString('en-PH', {minimumFractionDigits: 2}) : 'Select an amount'"></span>
    </button>

    <div style="margin-top:16px;display:flex;align-items:center;justify-content:center;gap:12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,0.3)" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <span style="font-size:11px;color:rgba(13,42,82,0.35);">{{ $helperText }}</span>
    </div>
</form>
