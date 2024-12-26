import React, { useState, useEffect } from 'react';
import axios from 'axios';
import TaskList from './TaskList';
import { formatTime } from '../utils';

const TaskTracker = () => {
    const [taskName, setTaskName] = useState('');
    const [currentTask, setCurrentTask] = useState(null);
    const [tasks, setTasks] = useState([]);
    const [elapsedTime, setElapsedTime] = useState(0);
    const [timerRunning, setTimerRunning] = useState(false);
    const [todayWorkedHours, setTodayWorkedHours] = useState('');
    const [error, setError] = useState(null);

    const fetchTasks = async () => {
        try {
            const response = await axios.get('/api/task/list');
            setTasks(response.data);
        } catch (err) {
            console.error('Error fetching tasks:', err);
        }
    };

    const fetchTodayWorkedHours = async () => {
        try {
            const response = await axios.get('/api/task/hours');
            setTodayWorkedHours(response.data);
        } catch (err) {
            console.error('Error fetching today\'s worked hours:', err);
        }
    };

    useEffect(() => {
        const fetchData = async () => {
            await fetchTasks();
            await fetchTodayWorkedHours();
        };
        fetchData();
    }, []);

    useEffect(() => {
        let timer;
        if (timerRunning) {
            timer = setInterval(() => setElapsedTime((prev) => prev + 1), 1000);
        }
        return () => clearInterval(timer);
    }, [timerRunning]);

    const handleStartTask = async () => {
        if (taskName.trim() === '') return;

        try {
            const response = await axios.post('/api/task/start', { taskName });
            const newTask = {
                id: response.data.taskId,
                name: response.data.taskName,
                startTime: new Date(response.data.startTime.date),
                totalTime: 0,
            };

            setCurrentTask(newTask);
            setTaskName('');
            setElapsedTime(0);
            setTimerRunning(true);
        } catch (err) {
            console.error('Error starting task:', err);
        }
    };

    const handleStopTask = async () => {
        if (!currentTask) return;

        try {
            const response = await axios.post('/api/task/stop', {
                taskName: currentTask.name,
            });
            const updatedTask = response.data;

            setTasks((prevTasks) =>
                prevTasks.map((task) => (task.id === currentTask.id ? updatedTask : task))
            );

            setTimerRunning(false);
            setCurrentTask(null);

            await fetchTasks();
            await fetchTodayWorkedHours();
        } catch (err) {
            console.error('Error stopping task:', err);
        }
    };

    return (
        <div>
            <div className="task-tracker">
                <h1 className="task-tracker__title">Task Tracker</h1>

                <div className="task-tracker__controls">
                    <input
                        type="text"
                        value={taskName}
                        onChange={(e) => setTaskName(e.target.value)}
                        placeholder="Enter task name"
                        className="task-tracker__input"
                    />
                    <button onClick={handleStartTask} className="task-tracker__button">
                        Start Task
                    </button>
                </div>

                {currentTask && (
                    <div className="task-tracker__current-task">
                        <h3 className="task-tracker__task-name">
                            {currentTask.name} : {formatTime(elapsedTime)}
                        </h3>
                        <button
                            onClick={handleStopTask}
                            className="task-tracker__button task-tracker__button--stop"
                        >
                            Stop Task
                        </button>
                    </div>
                )}
            </div>

            {error && <p className="error-message">{error}</p>}

            <div className="today-worked">
                <p>
                    <strong>Today's Worked Hours</strong>: {todayWorkedHours ? todayWorkedHours : "No hours worked"}
                </p>
            </div>

            <TaskList tasks={tasks} />
        </div>
    );
};

export default TaskTracker;
