@if ($foodRescueStatus->id == 1)
    Submitted
@elseif($foodRescueStatus->id == 2)
    Processed
@elseif($foodRescueStatus->id == 3)
    Assigned
@elseif($foodRescueStatus->id == 4)
    Taken
@elseif($foodRescueStatus->id == 5)
    Stored
@elseif($foodRescueStatus->id == 6)
    Rejected
@endif
