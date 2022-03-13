import React from 'react';
import logo from './logo.svg';
import './App.css';
import {useQuery} from "urql";
import {Test, TestQuery, TestQueryVariables} from "./generated/graphql";

function App() {
    // const callQuery = () => {
        const [result, reexecuteQuery] = useQuery<TestQuery>({
            query: Test,
            variables: {

            } as TestQueryVariables
        });
    // }

    if(result.data){
        console.log(result.data);
    }

    // useEffect(() => {
    //     callQuery();
    // }, []);

    return (
        <div className="App">
            <header className="App-header">
                <img src={logo} className="App-logo" alt="logo"/>
                <p>
                    Edit <code>src/App.tsx</code> and save to reload.
                </p>
                <a
                    className="App-link"
                    href="https://reactjs.org"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Learn React
                </a>
            </header>
        </div>
    );
}

export default App;
