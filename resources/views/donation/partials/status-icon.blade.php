@if ($donation->donation_status_id == 1)
    <x-heroicon-o-bookmark class="w-[18px] h-[18px]" />
@elseif ($donation->donation_status_id == 2)
    <x-heroicon-o-user-group class="w-[18px] h-[18px]" />
@elseif ($donation->donation_status_id == 3)
    <x-heroicon-o-cog class="w-[18px] h-[18px]" />
@elseif ($donation->donation_status_id == 4)
    <x-heroicon-o-shield-check class="w-[18px] h-[18px]" />
@else
    <x-heroicon-o-trash class="w-[18px] h-[18px]" />
@endif
