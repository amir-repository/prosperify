@if ($food->pivot->food_rescue_status_id == 1)
    <x-heroicon-o-bookmark class="w-6 h-6" />
@elseif ($food->pivot->food_rescue_status_id == 2)
    <x-heroicon-o-paper-airplane class="w-6 h-6" />
@elseif ($food->pivot->food_rescue_status_id == 3)
    <x-heroicon-o-cog class="w-6 h-6" />
@elseif ($food->pivot->food_rescue_status_id == 4)
    <x-heroicon-o-user-group class="w-6 h-6" />
@elseif ($food->pivot->food_rescue_status_id == 5)
    <x-heroicon-o-truck class="w-6 h-6" />
@elseif ($food->pivot->food_rescue_status_id == 6)
    <x-heroicon-o-archive-box-arrow-down class="w-6 h-6" />
@else
    <x-heroicon-o-trash class="w-6 h-6" />
@endif
