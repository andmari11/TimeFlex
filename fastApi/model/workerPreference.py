import datetime
import json
from typing import List, Optional

DEFAULT_HOLIDAYS_WEIGHT = 1
DEFAULT_PREFERRED_SHIFTS_WEIGHT = 1

class WorkerPreference:
    def __init__(
        self, 
        user_id: int, 
        holidays: Optional[List[datetime.datetime]] = None, 
        holidays_weight:Optional[int]=DEFAULT_HOLIDAYS_WEIGHT, 
        preferred_shift_types: Optional[List[int]] = None, 
        preferred_shift_weight:Optional[List[int]] = None, 
        past_satisfaction: Optional[List[float]] = None
    ):
        self.user_id = user_id
        self.holidays = holidays or []
        self.preferred_shift_types = preferred_shift_types or []
        self.past_satisfaction = past_satisfaction or []
        self.holidays_weight = holidays_weight
        self.preferred_shift_weight = (
            [DEFAULT_PREFERRED_SHIFTS_WEIGHT] * len(self.preferred_shift_types)
            if preferred_shift_weight is None
            else preferred_shift_weight
        )
    def __repr__(self):
        return f"WorkerPreference(user_id={self.user_id}, holidays={self.holidays})"
    
    def __str__(self):
        return f"WorkerPreference(user_id={self.user_id}, holidays={self.holidays}, preferred_shift_types={self.preferred_shift_types}, past_satisfaction={self.past_satisfaction}, holidays_weight={self.holidays_weight}, preferred_shift_weight={self.preferred_shift_weight})\n"

def process_worker_preferences(data:json) -> List[WorkerPreference]:

    worker_preferences = []

    for item in data:
        user_id = item.get("user_id")
        date_strings = json.loads(item.get('holidays'))
        holidays = [datetime.datetime.strptime(date_str, "%Y-%m-%d %H:%M:%S") for date_str in date_strings if date_str]
        holidays = holidays if holidays else None
        preferred_shift_types = json.loads(item.get('preferred_shift_types'))
        past_satisfaction = json.loads(item.get('past_satisfaction'))
        holidays_weight = json.loads(item.get('holidays_weight'))
        preferred_shift_weight = item.get('preferred_shift_weight', DEFAULT_PREFERRED_SHIFTS_WEIGHT)
        worker_preference = WorkerPreference(
            user_id=user_id, 
            holidays=holidays, 
            preferred_shift_types=preferred_shift_types, 
            past_satisfaction=past_satisfaction,
            holidays_weight=holidays_weight,
            preferred_shift_weight=preferred_shift_weight
        )
        worker_preferences.append(worker_preference)

    return worker_preferences

