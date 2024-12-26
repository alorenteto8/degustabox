export const formatTime = (totalTimeInSeconds) => {
    const hours = Math.floor(totalTimeInSeconds / 3600);
    const minutes = Math.floor((totalTimeInSeconds % 3600) / 60);
    const seconds = totalTimeInSeconds % 60;

    return `${hours}h ${minutes}m ${seconds}s`;
};
