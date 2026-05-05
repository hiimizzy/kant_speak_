import matplotlib.pyplot as plt
import seaborn as sns
import pandas as pd
import numpy as np

def plot_learning_curves(df_checks, activity=None, save_path=None):
    """
    Plot learning curves (cumulative accuracy over attempts) per session or per activity.
    """
    if activity:
        df_checks = df_checks[df_checks['activity'] == activity]
    if df_checks.empty:
        print("No data to plot")
        return
    # Sort by session and timestamp
    df_checks = df_checks.sort_values(['session', 'datetime'])
    # Compute cumulative accuracy per session
    fig, ax = plt.subplots(figsize=(10, 6))
    for session, group in df_checks.groupby('session'):
        group = group.sort_values('datetime')
        group['attempt_number'] = range(1, len(group)+1)
        group['cumulative_accuracy'] = group['correct'].expanding().mean()
        ax.plot(group['attempt_number'], group['cumulative_accuracy'], label=f'Session {session}', alpha=0.7)
    ax.set_xlabel('Attempt Number')
    ax.set_ylabel('Cumulative Accuracy')
    ax.set_title(f'Learning Curves{" for "+activity if activity else ""}')
    ax.legend(loc='best', fontsize='small')
    ax.grid(True, linestyle='--', alpha=0.5)
    if save_path:
        plt.savefig(save_path, dpi=150, bbox_inches='tight')
    plt.show()

def plot_accuracy_by_activity(df_checks, save_path=None):
    """
    Bar plot of accuracy per activity.
    """
    from metrics import compute_accuracy_by_activity
    acc_df = compute_accuracy_by_activity(df_checks)
    if acc_df.empty:
        print("No accuracy data to plot")
        return
    plt.figure(figsize=(10, 6))
    sns.barplot(data=acc_df, x='activity', y='accuracy', palette='viridis')
    plt.ylim(0, 1)
    plt.ylabel('Accuracy')
    plt.xlabel('Activity')
    plt.title('Accuracy by Activity')
    plt.xticks(rotation=45)
    if save_path:
        plt.savefig(save_path, dpi=150, bbox_inches='tight')
    plt.show()

def plot_group_comparison(df_checks, save_path=None):
    """
    Boxplot of accuracy by experimental group.
    """
    if df_checks['experiment_group'].isna().all():
        print("No experiment group data available")
        return
    plt.figure(figsize=(8, 6))
    sns.boxplot(data=df_checks, x='experiment_group', y='correct', palette='Set2')
    plt.ylabel('Accuracy (0/1)')
    plt.xlabel('Experimental Group')
    plt.title('Performance Comparison Between Groups')
    if save_path:
        plt.savefig(save_path, dpi=150, bbox_inches='tight')
    plt.show()