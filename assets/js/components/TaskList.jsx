import React from 'react';
import { formatTime } from '../utils';

const TaskList = ({ tasks }) => {
    return (
        <div className="task-history">
            <h2 className="task-history-title">History</h2>
            <ul className="task-history__list">
                {tasks.length === 0 ? (
                    <li className="task-history__list__item">No tasks</li>
                ) : (
                    tasks.map((task) => (
                        <li key={task.id} className="task-history__list__item">
                            <strong>{task.name}</strong> - {formatTime(task.totalTime)}
                        </li>
                    ))
                )}
            </ul>
        </div>
    );
};

export default TaskList;
