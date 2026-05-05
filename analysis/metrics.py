import pandas as pd
import numpy as np

def compute_accuracy_by_session(df_checks):
    """
    Compute per-session accuracy from 'check' events.
    
    Parameters:
        df_checks (pd.DataFrame): Dataframe with 'correct' column (True/False) and 'session' column.
    
    Returns:
        pd.DataFrame: Each row: session, total_attempts, correct_attempts, accuracy.
    """
    if df_checks.empty:
        return pd.DataFrame(columns=['session', 'total_attempts', 'correct_attempts', 'accuracy'])
    grouped = df_checks.groupby('session').agg(
        total_attempts=('correct', 'count'),
        correct_attempts=('correct', 'sum')
    ).reset_index()
    grouped['accuracy'] = grouped['correct_attempts'] / grouped['total_attempts']
    return grouped

def compute_accuracy_by_activity(df):
    """
    Compute accuracy per activity from all 'check' events.
    """
    checks = df[df['event'] == 'check'].copy()
    if checks.empty:
        return pd.DataFrame(columns=['activity', 'total_attempts', 'correct_attempts', 'accuracy'])
    grouped = checks.groupby('activity').agg(
        total_attempts=('correct', 'count'),
        correct_attempts=('correct', 'sum')
    ).reset_index()
    grouped['accuracy'] = grouped['correct_attempts'] / grouped['total_attempts']
    return grouped

def compute_response_time_stats(df):
    """
    Compute mean, median, std of reactionTime from 'check' events.
    """
    checks = df[(df['event'] == 'check') & (df['reaction_time'].notna())]
    if checks.empty:
        return {}
    return {
        'mean': checks['reaction_time'].mean(),
        'median': checks['reaction_time'].median(),
        'std': checks['reaction_time'].std(),
        'min': checks['reaction_time'].min(),
        'max': checks['reaction_time'].max()
    }

def compute_group_metrics(df, group_col='experiment_group'):
    """
    Compute mean accuracy and mean score per experimental group.
    """
    checks = df[df['event'] == 'check'].copy()
    if checks.empty:
        return pd.DataFrame(columns=[group_col, 'accuracy', 'mean_score'])
    # Accuracy per group
    acc = checks.groupby(group_col)['correct'].mean().reset_index()
    acc.columns = [group_col, 'accuracy']
    # Mean points per group
    points = checks.groupby(group_col)['points'].mean().reset_index()
    points.columns = [group_col, 'mean_score']
    merged = pd.merge(acc, points, on=group_col)
    return merged