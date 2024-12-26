import React from 'react';
import TaskTracker from './components/TaskTracker';
import ReactDOM from "react-dom/client";


function App() {
    return (
        <div className="App">
            <TaskTracker />
        </div>
    );
}

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(<App />);
