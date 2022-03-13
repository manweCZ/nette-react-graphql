import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import App from './App';
import {createClient, Provider} from "urql";

const client = createClient({
    url: 'http://localhost:8000/api',
});

ReactDOM.render(
    <React.StrictMode>
        <Provider value={client}>
            <App/>
        </Provider>
    </React.StrictMode>,
    document.getElementById('root')
);
