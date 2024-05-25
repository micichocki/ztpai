import React, { useState, useEffect } from 'react';
import axios from 'axios';

const Home = () => {
    const [data, setData] = useState(null);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await axios.get('https://localhost:8000/api/recipes');
                setData(response.data);
            } catch (error) {
                setError(error);
            }
        };

        fetchData();
    }, []);

    return (
        <div>
            <h1>Home</h1>
            {error && <p>Error: {error.message}</p>}
            {data && (
                <div>
                    <h2>Data from backend:</h2>
                    <pre>{JSON.stringify(data, null, 2)}</pre>
                </div>
            )}
        </div>
    );
};

export default Home;