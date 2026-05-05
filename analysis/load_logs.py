import json
import glob
import os
import pandas as pd

def load_all_sessions(data_dir="../data/sessions/"):
    """
    Load all JSON session logs from the specified directory.

    Parameters:
        data_dir (str): Path to the directory containing session JSON files.

    Returns:
        pd.DataFrame: Combined dataframe with all events.
    """
    all_events = []
    for filepath in glob.glob(os.path.join(data_dir, "*.json")):
        with open(filepath, 'r', encoding='utf-8') as f:
            events = json.load(f)
            for ev in events:
                all_events.append(ev)
    df = pd.DataFrame(all_events)
    if df.empty:
        return df
    # Convert timestamp to datetime
    df['datetime'] = pd.to_datetime(df['timestamp'], unit='s')
    # Extract nested data fields
    df['correct'] = df['data'].apply(lambda x: x.get('correct', None) if isinstance(x, dict) else None)
    df['reaction_time'] = df['data'].apply(lambda x: x.get('reactionTime', None) if isinstance(x, dict) else None)
    df['points'] = df['data'].apply(lambda x: x.get('pointsEarned', 0) if isinstance(x, dict) else 0)
    df['syllable'] = df['data'].apply(lambda x: x.get('syllable', None) if isinstance(x, dict) else None)
    # Extract experiment group if present
    df['experiment_group'] = df['data'].apply(lambda x: x.get('experiment_group', None) if isinstance(x, dict) else None)
    return df

def filter_activity(df, activity_name):
    """Filter events by activity name."""
    return df[df['activity'] == activity_name]

def filter_event(df, event_type):
    """Filter events by event type (e.g., 'check', 'drop', 'session_start')."""
    return df[df['event'] == event_type]