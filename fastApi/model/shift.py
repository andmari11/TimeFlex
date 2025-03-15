from datetime import datetime
from typing import List, Optional
import json


class Shift:
    def __init__(self, shift_id, schedule_id, start, end, users_needed, type):
        self.shift_id = shift_id
        self.schedule_id = schedule_id
        self.start = start if isinstance(start, datetime) else datetime.strptime(start, '%Y-%m-%d %H:%M:%S')
        self.end = end if isinstance(end, datetime) else datetime.strptime(end, '%Y-%m-%d %H:%M:%S')
        self.users_needed = users_needed
        self.type = type


    def __repr__(self):
        return (
            f"Shift(id={self.shift_id}, schedule_id={self.schedule_id}, start={self.start}, "
            f"end={self.end}, users_needed={self.users_needed})"
        )
    def __str__(self):
        return f"Shift {self.shift_id} (Schedule {self.schedule_id}): {self.start} - {self.end}, Users Needed: {self.users_needed}, Type: {self.type}\n"


def process_shifts_from_json(data:json) -> List[Shift]:
    shifts = []
    for entry in data:
        shift = Shift(
            shift_id=entry['id'],
            schedule_id=entry['schedule_id'],
            start=entry['start'],
            end=entry['end'],
            users_needed=entry['users_needed'],
            type=entry.get('type',"0")
        )
        shifts.append(shift)
    return shifts

# # Ejemplo
# shift = Shift(
#     shift_id=1,
#     schedule_id=101,
#     start='2024-11-15 08:00:00',
#     end='2024-11-15 16:00:00',
#     users_needed=5,
#     notes="Morning shift"
# )
