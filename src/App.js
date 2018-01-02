import React, { Component } from 'react';
import './App.css';
import Header from './routes/Header';
import Main from './routes/Main';

class App extends Component {
  render() {
    return (
      <div className="imperius-container">
        <Header />
        <Main />
      </div>
    );
  }
}

// class App extends Component {
//   render() {
//     return (
//       <div className="App">
//         <header className="App-header">
//           <img src={logo} className="App-logo" alt="logo" />
//           <h1 className="App-title">Welcome to React</h1>
//         </header>
//         <p className="App-intro">
//           To get started, edit <code>src/App.js</code> and save to reload.
//         </p>
//       </div>
//     );
//   }
// }

export default App;
