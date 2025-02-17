import datetime
import json
from typing import List, Optional

class WorkerPreference:
    def __init__(self, user_id: int, holiday_start: datetime.datetime, holiday_end: datetime.datetime):
        self.user_id = user_id
        self.holiday_start = holiday_start
        self.holiday_end = holiday_end

    def __repr__(self):
        return f"WorkerPreference(user_id={self.user_id}, holiday_start={self.holiday_start}, holiday_end={self.holiday_end})"




def process_worker_preferences(file_path: str) -> List[WorkerPreference]:
    print("Procesando preferencias...")
    with open(file_path, 'r') as file:
        data = json.load(file)

    worker_preferences = []
    for item in data:
        user_id = item['user_id']
        holiday_start = datetime.datetime.strptime(item['holiday_start'], "%Y-%m-%d %H:%M:%S")
        holiday_end = datetime.datetime.strptime(item['holiday_end'], "%Y-%m-%d %H:%M:%S")
        worker_preference = WorkerPreference(user_id=user_id, holiday_start=holiday_start, holiday_end=holiday_end)
        worker_preferences.append(worker_preference)

    return worker_preferences

