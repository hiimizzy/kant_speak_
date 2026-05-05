from scipy import stats
import pandas as pd

def t_test_groups(df_checks, group_col='experiment_group', groups=('control', 'adaptive'), metric='correct'):
    """
    Perform independent t-test between two experimental groups on a metric.
    
    Parameters:
        df_checks (pd.DataFrame): Dataframe with 'check' events.
        group_col (str): Column name for group assignment.
        groups (tuple): Two group names to compare.
        metric (str): Column name for the metric (e.g., 'correct' for accuracy, 'points').
    
    Returns:
        dict: t-statistic, p-value, degrees of freedom.
    """
    group1 = df_checks[df_checks[group_col] == groups[0]][metric]
    group2 = df_checks[df_checks[group_col] == groups[1]][metric]
    if len(group1) == 0 or len(group2) == 0:
        return {'error': 'One of the groups has no data'}
    t_stat, p_val = stats.ttest_ind(group1, group2, nan_policy='omit')
    return {'t_statistic': t_stat, 'p_value': p_val, 'df': len(group1)+len(group2)-2}

def anova_activities(df_checks):
    """
    One-way ANOVA to compare accuracy across different activities.
    """
    from scipy.stats import f_oneway
    groups = [group['correct'].dropna().values for name, group in df_checks.groupby('activity')]
    if len(groups) < 2:
        return {'error': 'Need at least two activities'}
    f_stat, p_val = f_oneway(*groups)
    return {'f_statistic': f_stat, 'p_value': p_val}