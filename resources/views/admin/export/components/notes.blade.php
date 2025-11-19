@if ($notes->count() > 0)
    <pagebreak />
    <div class="">
        <h4 style="text-align: center;">جميع الملاحظات</h4>
        <div class="notes-area">
            @foreach ($notes as $index => $note)
                @if ($loop->iteration % 6 == 1)
                    <div class="notes-group">
                @endif

                <div class="note">
                    <p class="note-title">{{ 'ملاحظة رقم: #' . ($index + 1) }}</p>
                    <p class="note-content">{{ $note->content }}</p>
                    <p class="note-metadata">
                        وقت إنشاء الملاحظة: {{ $note->created_at->format('Y-m-d H:i:s') }}<br>
                        بواسطة: {{ $note->user_name ?? 'غير معروف' }}
                    </p>
                </div>

                @if ($loop->iteration % 6 == 0 || $loop->last)
        </div>
        @if (!$loop->last)
            <pagebreak />
        @endif
@endif
@endforeach
</div>
</div>
@endif
