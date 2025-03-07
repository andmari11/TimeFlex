import datetime
import json
from typing import List, Optional

class WorkerPreference:
    def __init__(self, user_id: int, holidays: Optional[List[datetime.datetime]] = None, preferred_shift_types: Optional[List[int]] = None, past_satisfaction: Optional[List[float]] = None):
        self.user_id = user_id
        self.holidays = holidays or []
        self.preferred_shift_types = preferred_shift_types or []
        self.past_satisfaction = past_satisfaction or []

    def __repr__(self):
        return f"WorkerPreference(user_id={self.user_id}, holidays={self.holidays})"

def process_worker_preferences(data:json) -> List[WorkerPreference]:

    worker_preferences = []

    for item in data:
        user_id = item.get("user_id")
        date_strings = json.loads(item.get('holidays'))
        # date_strings = item.get('holidays')
        holidays = [datetime.datetime.strptime(date_str, "%Y-%m-%d %H:%M:%S") for date_str in date_strings]
        preferred_shift_types = item.get('preferred_shift_types')
        past_satisfaction = item.get('past_satisfaction')
        worker_preference = WorkerPreference(user_id=user_id, holidays=holidays, preferred_shift_types=preferred_shift_types, past_satisfaction=past_satisfaction)
        worker_preferences.append(worker_preference)

    return worker_preferences

