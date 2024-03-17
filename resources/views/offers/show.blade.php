<!-- resources/views/offers/show.blade.php -->

    <h1>تفاصيل العرض</h1>

    <p><strong>نوع الحركة:</strong> {{ $offer->movement_type_offer->type }}</p>
    <p><strong>العرض:</strong> {{ $offer->offer }}</p>
    <p><strong>قيمة الخصم:</strong> {{ $offer->value_of_discount }}</p>
    <p><strong>تاريخ الصلاحية:</strong> {{ $offer->valide_date }}</p>

    <a href="{{ route('offers.index') }}" class="btn btn-secondary">رجوع</a>
